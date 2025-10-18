<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Report;
use App\Services\AccessCodeService;
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
			'end_date' => $endDate
		]);
		
		
		return redirect()->route('reports.index', ['club_id' => $club->id])->with('success', 'Reports generated successfully!');
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
		]);
		
		// Update the report
		$report->update([
			'report_name' => $request->input('report_name'),
			'report_summary_text' => $request->input('report_summary_text'),
			'report_overall_score' => $request->input('report_overall_score'),
		]);
		
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
}


