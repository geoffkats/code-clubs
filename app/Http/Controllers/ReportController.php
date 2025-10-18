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
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		// Temporarily removing school ID check
		return view('reports.beautiful-show', compact('report'));
	}

	public function pdf(int $report_id)
	{
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		// Temporarily removing school ID check
		return view('reports.comprehensive-pdf', compact('report'));
	}

	public function send_to_parent(int $report_id, AccessCodeService $codes, EmailNotificationService $email)
	{
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		if ($report->club->school_id !== auth()->user()->school_id) abort(403);
		$created = $codes->create_access_code_for_report($report->id);
		$plain = $created['plain'];
		if ($report->student->student_parent_email) {
			$email->send_parent_report_email($report, $report->student->student_parent_email, $plain);
		}
		return back();
	}
}


