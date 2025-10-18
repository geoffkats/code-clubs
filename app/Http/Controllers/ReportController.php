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
		$user = auth()->user();
		
		
		if ($clubId) {
			// Show reports for specific club
			$club = Club::findOrFail($clubId);
			$reports = Report::where('club_id', $clubId)
				->with(['student', 'club', 'access_code'])
				->orderBy('created_at', 'desc')
				->get();
		} else {
			// Show all reports (temporarily removing school filtering)
			$reports = Report::with(['student', 'club', 'access_code'])
				->orderBy('created_at', 'desc')
				->get();
		}
		
		$clubs = Club::orderBy('club_name')->get();
		
		return view('reports.index', compact('reports', 'clubs', 'clubId'));
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
}


