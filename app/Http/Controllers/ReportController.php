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
		
		// Debug: Let's see what's in the database
		$allReports = Report::with(['student', 'club'])->get();
		$totalReports = Report::count();
		$userSchoolReports = Report::whereHas('club', function($q) use ($user) {
			$q->where('school_id', $user->school_id);
		})->count();
		
		// Debug output
		\Log::info('Reports Debug', [
			'user_school_id' => $user->school_id,
			'total_reports' => $totalReports,
			'user_school_reports' => $userSchoolReports,
			'club_id_filter' => $clubId,
			'all_reports' => $allReports->toArray()
		]);
		
		if ($clubId) {
			// Show reports for specific club
			$club = Club::findOrFail($clubId);
			$reports = Report::where('club_id', $clubId)
				->with(['student', 'club', 'access_code'])
				->orderBy('created_at', 'desc')
				->get();
		} else {
			// Show all reports for user's school
			$reports = Report::whereHas('club', function($q) use ($user) {
				$q->where('school_id', $user->school_id);
			})->with(['student', 'club', 'access_code'])
				->orderBy('created_at', 'desc')
				->get();
		}
		
		$clubs = Club::where('school_id', $user->school_id)->orderBy('club_name')->get();
		
		return view('reports.index', compact('reports', 'clubs', 'clubId'));
	}

	public function create(int $club_id)
	{
		$club = Club::findOrFail($club_id);
		return view('reports.generate', compact('club'));
	}

	public function generate_for_club(int $club_id, ReportGeneratorService $generator)
	{
		$club = Club::findOrFail($club_id);
		
		// Debug: Check club and students before generation
		\Log::info('Generating reports for club', [
			'club_id' => $club->id,
			'club_name' => $club->club_name,
			'students_count' => $club->students()->count(),
			'students' => $club->students()->get()->toArray()
		]);
		
		$generator->generate_reports_for_club($club->id);
		
		// Debug: Check reports after generation
		$generatedReports = Report::where('club_id', $club->id)->get();
		\Log::info('Reports generated', [
			'club_id' => $club->id,
			'reports_count' => $generatedReports->count(),
			'reports' => $generatedReports->toArray()
		]);
		
		return redirect()->route('reports.index', ['club_id' => $club->id])->with('success', 'Reports generated successfully!');
	}

	public function show(int $report_id)
	{
		$report = Report::with(['student', 'club', 'access_code'])->findOrFail($report_id);
		if ($report->club->school_id !== auth()->user()->school_id) abort(403);
		return view('reports.beautiful-show', compact('report'));
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


