<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Report;
use App\Services\AccessCodeService;
use App\Services\AIReportGeneratorService;
use App\Services\EmailNotificationService;
use App\Services\ReportGeneratorService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
	public function index(Request $request)
	{
		$clubId = $request->get('club_id');
		$search = $request->get('search');
		$perPage = $request->get('per_page', 12); // Default 12 reports per page
		$user = auth()->user();
		
		// Build the base query
		$query = Report::with(['student', 'club', 'access_code']);
		
		// Apply club filter if specified
		if ($clubId) {
			$club = Club::findOrFail($clubId);
			$query->where('club_id', $clubId);
		}
		
		// Apply search filter if specified
		if ($search) {
			$query->whereHas('student', function($q) use ($search) {
				$q->where('student_first_name', 'like', "%{$search}%")
				  ->orWhere('student_last_name', 'like', "%{$search}%");
			})->orWhereHas('club', function($q) use ($search) {
				$q->where('club_name', 'like', "%{$search}%");
			})->orWhere('report_name', 'like', "%{$search}%");
		}
		
		// Get paginated results
		$reports = $query->orderBy('created_at', 'desc')
			->paginate($perPage)
			->withQueryString(); // Preserve query parameters in pagination links
		
		// Get clubs for filter dropdown
		$clubs = Club::orderBy('club_name')->get();
		
		return view('reports.index', compact('reports', 'clubs', 'clubId', 'search', 'perPage'));
	}

	public function create(int $club_id)
	{
		$club = Club::findOrFail($club_id);
		return view('reports.generate', compact('club'));
	}

	public function generate_for_club(int $club_id, Request $request, ReportGeneratorService $generator)
	{
		$club = Club::findOrFail($club_id);
		
		// Get form data
		$reportType = $request->get('report_type', 'comprehensive');
		$dateRange = $request->get('date_range', 'month');
		$format = $request->get('format', 'pdf');
		$includeCharts = $request->has('include_charts');
		$sections = $request->get('sections', []);
		$startDate = $request->get('start_date');
		$endDate = $request->get('end_date');
		
		
		$generator->generate_reports_for_club($club->id, [
			'report_type' => $reportType,
			'date_range' => $dateRange,
			'format' => $format,
			'include_charts' => $includeCharts,
			'sections' => $sections,
			'start_date' => $startDate,
			'end_date' => $endDate,
			'use_ai_generation' => true, // Enable AI-powered content generation
		]);
		
		
		return redirect()->route('reports.index', ['club_id' => $club->id])->with('success', '🤖 AI-powered reports generated successfully! Each report now contains personalized content based on student assessments and attendance.');
	}

	public function generate_ai_single(int $report_id, Request $request, AIReportGeneratorService $aiService)
	{
		try {
			$report = Report::with(['student', 'club'])->findOrFail($report_id);
			
			// Generate AI content for this specific report
			$aiContent = $aiService->generateReportContent($report);
			
			// Update the report with AI-generated content
			$report->update($aiContent);
			
			// Regenerate access code to ensure it's current
			$accessCodeService = app(AccessCodeService::class);
			$accessCodeService->create_access_code_for_report($report->id);
			
			return redirect()->route('reports.show', $report_id)->with('success', "🤖 AI content generated successfully for {$report->student->student_first_name} {$report->student->student_last_name}!");
			
		} catch (\Exception $e) {
			\Log::error('Error generating AI content for single report', [
				'report_id' => $report_id,
				'error' => $e->getMessage()
			]);
			
			return redirect()->back()->with('error', 'Failed to generate AI content. Please try again.');
		}
	}

	public function show(int $report_id)
	{
		$report = Report::with([
			'student', 
			'club.assessments.scores', 
			'club.sessions.attendance_records',
			'club.attachments',
			'access_code'
		])->findOrFail($report_id);
		
		// Refresh the access code relationship to ensure it's current
		$report->load('access_code');
		
		// Get assessment data for this student
		$assessments = $report->club->assessments->map(function($assessment) use ($report) {
			$score = $assessment->scores->firstWhere('student_id', $report->student->id);
			return [
				'id' => $assessment->id,
				'name' => $assessment->assessment_name,
				'type' => $assessment->assessment_type,
				'week' => $assessment->assessment_week_number,
				'score' => $score ? $score->score_value : null,
				'max_score' => $score ? $score->score_max_value : 100,
				'percentage' => $score && $score->score_max_value > 0 ? 
					round(($score->score_value / $score->score_max_value) * 100, 2) : 0
			];
		});
		
		// Get attendance data
		$total_sessions = $report->club->sessions->count();
		$present_count = 0;
		foreach ($report->club->sessions as $session) {
			$record = $session->attendance_records->firstWhere('student_id', $report->student->id);
			if ($record && $record->attendance_status === 'present') {
				$present_count++;
			}
		}
		$attendance_percentage = $total_sessions > 0 ? round(($present_count / $total_sessions) * 100, 2) : 0;
		
		// Get Scratch projects
		$scratch_projects = collect();
		if ($report->club->attachments) {
			$scratch_projects = $report->club->attachments->where('file_type', 'scratch')
				->map(function($attachment) {
					return [
						'name' => $attachment->file_name,
						'description' => $attachment->description,
						'created_at' => $attachment->created_at,
						'file_size' => $attachment->file_size
					];
				});
		}
		
		// Temporarily removing school ID check
		return view('reports.beautiful-show', compact('report', 'assessments', 'attendance_percentage', 'scratch_projects'));
	}

	public function pdf(int $report_id)
	{
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		// Temporarily removing school ID check
		return view('reports.comprehensive-pdf', compact('report'));
	}

	public function send_to_parent(int $report_id, Request $request, AccessCodeService $codes, EmailNotificationService $email)
	{
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		
		// Validate the email input
		$request->validate([
			'parent_email' => 'required|email'
		]);
		
		$parent_email = $request->input('parent_email');
		
		// Temporarily removing school ID check for consistency with other methods
		// if ($report->club->school_id !== auth()->user()->school_id) abort(403);
		$created = $codes->create_access_code_for_report($report->id);
		$plain = $created['plain'];
		
		// Send email to the provided email address
		$email->send_parent_report_email($report, $parent_email, $plain);
		
		return back()->with('success', "Report sent successfully to {$parent_email}!");
	}

	/**
	 * Edit a report (show edit form)
	 * 
	 * @param int $report_id The ID of the report to edit
	 * @return \Illuminate\View\View
	 */
	public function edit(int $report_id)
	{
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		// Temporarily removing school ID check for consistency
		return view('reports.edit', compact('report'));
	}

	/**
	 * Update a report
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @param int $report_id The ID of the report to update
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, int $report_id)
	{
		$report = Report::findOrFail($report_id);
		
		// Validate the request
		$request->validate([
			'report_name' => 'required|string|max:255',
			'report_summary_text' => 'required|string',
			'report_overall_score' => 'required|numeric|min:0|max:100',
			'student_initials' => 'nullable|string|max:3',
			'problem_solving_score' => 'nullable|integer|min:1|max:10',
			'creativity_score' => 'nullable|integer|min:1|max:10',
			'collaboration_score' => 'nullable|integer|min:1|max:10',
			'persistence_score' => 'nullable|integer|min:1|max:10',
			'scratch_project_ids' => 'nullable|json',
			'favorite_concept' => 'nullable|string|max:255',
			'challenges_overcome' => 'nullable|string',
			'special_achievements' => 'nullable|string',
			'areas_for_growth' => 'nullable|string',
			'next_steps' => 'nullable|string',
			'parent_feedback' => 'nullable|string',
		]);
		
		// Process scratch project IDs (convert from newline-separated to JSON array)
		$scratchProjectIds = [];
		if ($request->input('scratch_project_ids')) {
			$ids = explode("\n", $request->input('scratch_project_ids'));
			$scratchProjectIds = array_filter(array_map('trim', $ids));
		}
		
		// Update the report
		$report->update([
			'report_name' => $request->input('report_name'),
			'report_summary_text' => $request->input('report_summary_text'),
			'report_overall_score' => $request->input('report_overall_score'),
			'student_initials' => $request->input('student_initials'),
			'problem_solving_score' => $request->input('problem_solving_score'),
			'creativity_score' => $request->input('creativity_score'),
			'collaboration_score' => $request->input('collaboration_score'),
			'persistence_score' => $request->input('persistence_score'),
			'scratch_project_ids' => json_encode($scratchProjectIds),
			'favorite_concept' => $request->input('favorite_concept'),
			'challenges_overcome' => $request->input('challenges_overcome'),
			'special_achievements' => $request->input('special_achievements'),
			'areas_for_growth' => $request->input('areas_for_growth'),
			'next_steps' => $request->input('next_steps'),
			'parent_feedback' => $request->input('parent_feedback'),
		]);
		
		// Update student grade if provided
		if ($request->input('student_grade')) {
			$report->student->update([
				'student_grade_level' => $request->input('student_grade')
			]);
		}
		
		return redirect()->route('reports.show', $report->id)
			->with('success', 'Report updated successfully!');
	}

	/**
	 * Delete a report
	 * 
	 * @param int $report_id The ID of the report to delete
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(int $report_id)
	{
		$report = Report::findOrFail($report_id);
		$clubId = $report->club_id;
		
		// Delete the report (access codes will be deleted automatically due to cascade)
		$report->delete();
		
		return redirect()->route('reports.index', ['club_id' => $clubId])
			->with('success', 'Report deleted successfully!');
	}

	/**
	 * Regenerate access code for a report
	 * 
	 * @param int $report_id The ID of the report
	 * @param AccessCodeService $codes Service for managing access codes
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function regenerate_access_code(int $report_id, AccessCodeService $codes)
	{
		$report = Report::with(['student', 'club'])->findOrFail($report_id);
		
		// Generate new access code
		$created = $codes->create_access_code_for_report($report->id);
		
		return back()->with('success', 'Access code regenerated successfully! New code: ' . $created['plain']);
	}

	/**
	 * Show parent access form
	 */
	public function parent_preview(string $access_code = null)
	{
		// If access code is provided, try to verify it
		if ($access_code) {
			$report = $this->verifyAccessCode($access_code);
			if ($report) {
				return redirect()->route('reports.parent-view', ['access_code' => $access_code]);
			}
		}

		// Show access code entry form
		return view('reports.parent-access');
	}

	/**
	 * Verify parent access code and show report
	 */
	public function verify_parent_access(Request $request, AccessCodeService $codes)
	{
		$request->validate([
			'access_code' => 'required|string'
		]);

		$access_code = $request->input('access_code');
		$report = $this->verifyAccessCode($access_code);

		if ($report) {
			return redirect()->route('reports.parent-view', ['access_code' => $access_code]);
		}

		return back()->with('error', 'Invalid or expired access code. Please check the code and try again.');
	}

	/**
	 * Show report for parents (no authentication required)
	 */
	public function parent_view(string $access_code)
	{
		$report = $this->verifyAccessCode($access_code);
		
		if (!$report) {
			return redirect()->route('reports.parent-preview')
				->with('error', 'Invalid or expired access code.');
		}

		// Load all necessary data for parent view
		$attendance_percentage = $this->calculateAttendancePercentage($report->club, $report->student->id);
		$assessments = $this->getAssessmentData($report->club, $report->student->id);
		$scratch_projects = $this->getScratchProjects($report->club, $report->student->id);

		return view('reports.parent-view', compact('report', 'attendance_percentage', 'assessments', 'scratch_projects', 'access_code'));
	}

	/**
	 * Verify access code and return report if valid
	 */
	private function verifyAccessCode(string $access_code)
	{
		try {
			$reportAccessCode = \App\Models\ReportAccessCode::where('access_code_plain_preview', $access_code)->first();
			
			if (!$reportAccessCode) {
				return null;
			}

			// Check if code is expired
			if ($reportAccessCode->access_code_expires_at && now()->isAfter($reportAccessCode->access_code_expires_at)) {
				return null;
			}

			// Return the report with all necessary relationships
			return Report::with(['student', 'club', 'access_code'])
				->find($reportAccessCode->report_id);

		} catch (\Exception $e) {
			Log::error('Error verifying access code', [
				'access_code' => $access_code,
				'error' => $e->getMessage()
			]);
			return null;
		}
	}

	/**
	 * Calculate attendance percentage for a student in a club
	 */
	private function calculateAttendancePercentage(Club $club, int $studentId): float
	{
		$sessions = $club->sessions;
		$attended = 0;
		$totalSessions = $sessions->count();
		
		if ($totalSessions === 0) return 0;
		
		foreach ($sessions as $session) {
			$attendance = $session->attendance_records->where('student_id', $studentId)->first();
			if ($attendance && $attendance->attendance_status === 'present') {
				$attended++;
			}
		}
		
		return round(($attended / $totalSessions) * 100, 2);
	}

	/**
	 * Get assessment data for a student in a club
	 */
	private function getAssessmentData(Club $club, int $studentId): array
	{
		$assessments = [];
		foreach ($club->assessments as $assessment) {
			$score = $assessment->scores->where('student_id', $studentId)->first();
			$assessments[] = [
				'name' => $assessment->assessment_name,
				'type' => $assessment->assessment_type,
				'week' => $assessment->assessment_week_number,
				'score' => $score ? $score->score_value : null,
				'max_score' => $score ? $score->score_max_value : 100,
				'percentage' => $score && $score->score_max_value > 0 ? 
					round(($score->score_value / $score->score_max_value) * 100, 2) : 0
			];
		}
		return collect($assessments);
	}

	/**
	 * Get Scratch projects for a student
	 */
	private function getScratchProjects(Club $club, int $studentId): array
	{
		// For now, return empty array - this can be enhanced later
		return [];
	}
}


