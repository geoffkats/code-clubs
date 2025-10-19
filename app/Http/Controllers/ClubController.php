<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
	public function index(Request $request)
	{
		$schoolId = $request->get('school');
		
		$clubs = Club::query()
			->when($schoolId, function($query, $schoolId) {
				return $query->where('school_id', $schoolId);
			})
			->with(['school', 'students'])
			->withCount(['students', 'sessions', 'assessments'])
			->orderBy('club_start_date', 'desc')
			->paginate(20);
			
		$schools = \App\Models\School::orderBy('school_name')->get();
		
		return view('clubs.index', compact('clubs', 'schools'));
	}

	public function create()
	{
		$schools = \App\Models\School::orderBy('school_name')->get();
		return view('clubs.create', compact('schools'));
	}

	public function show(int $club_id)
	{
		$club = Club::with(['school', 'students', 'sessions', 'assessments'])
			->withCount(['students', 'sessions', 'assessments'])
			->findOrFail($club_id);
			
		// Get recent assessments with scores and questions
		$recentAssessments = $club->assessments()
			->with(['scores' => function($query) {
				$query->with('student')->orderBy('created_at', 'desc');
			}, 'questions'])
			->orderBy('created_at', 'desc')
			->limit(5)
			->get();
			
		// Get attendance summary
		$attendanceSummary = [
			'total_sessions' => $club->sessions->count(),
			'attended_sessions' => 0, // This would need to be calculated from attendance records
			'average_attendance' => 0, // This would need to be calculated
		];
		
		return view('clubs.show', compact('club', 'recentAssessments', 'attendanceSummary'));
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'school_id' => ['required', 'exists:schools,id'],
			'club_name' => ['required', 'string'],
			'club_level' => ['nullable', 'string'],
			'club_duration_weeks' => ['nullable', 'integer', 'min:1', 'max:52'],
			'club_start_date' => ['nullable', 'date'],
		]);
		
		// Ensure user has access to the selected school
		$userSchoolId = auth()->user()->school_id;
		if ($data['school_id'] != $userSchoolId) {
			// For now, we'll allow creating clubs for any school
			// In a more restrictive system, you might want to check permissions here
		}
		
		$club = Club::create($data);
		return redirect()->route('clubs.index', ['school' => $club->school_id])
			->with('success', 'Club created successfully!');
	}

	public function update(Request $request, int $club_id)
	{
		$data = $request->validate([
			'school_id' => ['required', 'exists:schools,id'],
			'club_name' => ['required', 'string'],
			'club_level' => ['nullable', 'string'],
			'club_duration_weeks' => ['nullable', 'integer', 'min:1', 'max:52'],
			'club_start_date' => ['nullable', 'date'],
		]);

		$club = Club::findOrFail($club_id);
		$club->update($data);
		return redirect()->route('clubs.index', ['school' => $club->school_id])->with('success', 'Club updated successfully!');
	}

	public function destroy(int $club_id)
	{
		\Log::info('ClubController destroy called with ID: ' . $club_id);
		$club = Club::findOrFail($club_id);
		$schoolId = $club->school_id;
		$club->delete();
		return redirect()->route('clubs.index', ['school' => $schoolId])->with('success', 'Club deleted successfully!');
	}
}


