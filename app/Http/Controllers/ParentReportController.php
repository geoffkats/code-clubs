<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\AccessCodeService;
use Illuminate\Http\Request;

class ParentReportController extends Controller
{
	/**
	 * Show parent welcome page
	 */
	public function welcome()
	{
		return view('parent-welcome');
	}

	/**
	 * Verify access code and redirect to report
	 */
	public function verify_access_code(Request $request, AccessCodeService $codes)
	{
		$accessCode = $request->input('access_code');
		
		if (!$accessCode) {
			return redirect()->route('parent.welcome')->with('error', 'Please enter an access code');
		}
		
		// Find report with this access code
		$report = Report::whereHas('access_code', function($query) use ($accessCode) {
			$query->where('access_code_plain_preview', $accessCode);
		})->with(['student', 'club.school', 'access_code'])->first();
		
		if (!$report) {
			return redirect()->route('parent.welcome')->with('error', 'Invalid access code');
		}
		
		// Verify the access code is valid and not expired
		if (!$codes->verify_access_code($report, $accessCode)) {
			return redirect()->route('parent.welcome')->with('error', 'Invalid or expired access code');
		}
		
		// Redirect to the report view
		return redirect()->route('reports.public', ['report_id' => $report->id, 'code' => $accessCode]);
	}

	public function show_public(int $report_id, Request $request, AccessCodeService $codes)
	{
		try {
			$code = (string) $request->query('code');
			$report = Report::with(['student', 'club.school', 'access_code'])->findOrFail($report_id);
			
			// Log the access attempt for debugging
			\Log::info('Parent access attempt', [
				'report_id' => $report_id,
				'code' => $code,
				'has_access_code' => $report->access_code ? true : false,
				'access_code_id' => $report->access_code?->id
			]);
			
			if (!$code) {
				\Log::warning('Parent access failed - no code provided', ['report_id' => $report_id]);
				abort(403, 'Access code is required');
			}
			
			if (!$report->access_code) {
				\Log::warning('Parent access failed - no access code found for report', ['report_id' => $report_id]);
				abort(403, 'No access code found for this report');
			}
			
			if (!$codes->verify_access_code($report, $code)) {
				\Log::warning('Parent access failed - invalid code', [
					'report_id' => $report_id,
					'provided_code' => $code,
					'expected_code' => $report->access_code->access_code_plain_preview ?? 'N/A'
				]);
				abort(403, 'Invalid or expired access code');
			}
			
			\Log::info('Parent access successful', [
				'report_id' => $report_id,
				'student_name' => $report->student->student_first_name ?? 'Unknown'
			]);
			
			// Load additional data needed for the parent view
			$attendance_percentage = $this->calculateAttendancePercentage($report->club, $report->student->id);
			$assessments = $this->getAssessmentData($report->club, $report->student->id);
			$scratchProjects = $this->getScratchProjects($report);
			
			return view('reports.parent-view', compact('report', 'attendance_percentage', 'assessments', 'scratchProjects'));
			
		} catch (\Exception $e) {
			\Log::error('Parent access error', [
				'report_id' => $report_id,
				'error' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
			]);
			
			abort(500, 'An error occurred while accessing the report');
		}
	}
	
	/**
	 * Calculate attendance percentage for a student in a club
	 */
	private function calculateAttendancePercentage($club, int $studentId): float
	{
		try {
			$sessions = $club->sessions->take(50);
			$attended = 0;
			$totalSessions = $sessions->count();
			
			if ($totalSessions === 0) return 0;
			
			foreach ($sessions as $session) {
				$attendance = $session->attendance_records->where('student_id', $studentId)->first();
				if ($attendance && $attendance->attendance_status === 'present') {
					$attended++;
				}
			}
			
			return ($attended / $totalSessions) * 100;
			
		} catch (\Exception $e) {
			\Log::warning('Error calculating attendance percentage in ParentReportController', [
				'club_id' => $club->id,
				'student_id' => $studentId,
				'error' => $e->getMessage()
			]);
			
			return 85.0; // Default value
		}
	}
	
	/**
	 * Get assessment data for a student
	 */
	private function getAssessmentData($club, int $studentId)
	{
		try {
			$assessments = collect();
			
			foreach ($club->assessments->take(10) as $assessment) {
				$score = $assessment->scores->where('student_id', $studentId)->first();
				$assessments->push([
					'name' => $assessment->assessment_name,
					'type' => $assessment->assessment_type,
					'score' => $score ? $score->score_value : null,
					'max_score' => $score ? $score->score_max_value : $assessment->max_score ?? 100,
					'percentage' => $score ? ($score->score_value / $score->score_max_value) * 100 : 0,
				]);
			}
			
			return $assessments;
			
		} catch (\Exception $e) {
			\Log::warning('Error getting assessment data in ParentReportController', [
				'club_id' => $club->id,
				'student_id' => $studentId,
				'error' => $e->getMessage()
			]);
			
			return collect(); // Return empty collection
		}
	}
	
	/**
	 * Get Scratch projects for a report
	 */
	private function getScratchProjects(Report $report)
	{
		try {
			$projectIds = json_decode($report->scratch_project_ids ?? '[]', true) ?? [];
			
			return collect($projectIds)->map(function ($projectId) {
				return [
					'id' => $projectId,
					'url' => "https://scratch.mit.edu/projects/{$projectId}",
				];
			});
			
		} catch (\Exception $e) {
			\Log::warning('Error getting Scratch projects in ParentReportController', [
				'report_id' => $report->id,
				'error' => $e->getMessage()
			]);
			
			return collect(); // Return empty collection
		}
	}
}


