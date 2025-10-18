<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\SessionSchedule;
use App\Models\AttendanceRecord;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
	public function show_grid(int $club_id)
	{
		$club = Club::with(['students', 'school'])->findOrFail($club_id);
		$week = (int) request('week', 1);
		
		// Create a session if it doesn't exist for this week
		$session = SessionSchedule::where('club_id', $club->id)
			->where('session_week_number', $week)
			->first();
			
		if (!$session) {
			$session = SessionSchedule::create([
				'club_id' => $club->id,
				'session_week_number' => $week,
				'session_date' => now()->addWeeks($week - 1),
			]);
		}
		
		// Get existing attendance records for this week
		$attendanceRecords = AttendanceRecord::where('session_id', $session->id)
			->with('student')
			->get()
			->keyBy('student_id');
			
		// Debug: Log the students count
		\Log::info('Attendance Grid - Club ID: ' . $club->id . ', Students count: ' . $club->students->count());
		
		return view('attendance.grid', compact('club', 'week', 'session', 'attendanceRecords'));
	}

	public function index()
	{
		$clubs = Club::with(['school', 'students'])
			->withCount(['students', 'sessions'])
			->orderBy('club_name')
			->paginate(20);
			
		return view('attendance.index', compact('clubs'));
	}

	public function store(Request $request, int $club_id)
	{
		$request->validate([
			'student_id' => ['required', 'exists:students,id'],
			'session_id' => ['required', 'exists:sessions_schedule,id'],
			'status' => ['required', 'in:present,absent,late,excused'],
			'notes' => ['nullable', 'string', 'max:500'],
		]);

		AttendanceRecord::updateOrCreate(
			[
				'student_id' => $request->student_id,
				'session_id' => $request->session_id,
			],
			[
				'attendance_status' => $request->status,
				'attendance_notes' => $request->notes,
			]
		);

		return redirect()->back()->with('success', 'Attendance recorded successfully!');
	}

	public function update(Request $request, int $attendance_id)
	{
		$attendance = AttendanceRecord::findOrFail($attendance_id);
		
		$request->validate([
			'status' => ['required', 'in:present,absent,late,excused'],
			'notes' => ['nullable', 'string', 'max:500'],
		]);

		$attendance->update([
			'attendance_status' => $request->status,
			'attendance_notes' => $request->notes,
		]);

		return redirect()->back()->with('success', 'Attendance updated successfully!');
	}

	public function destroy(int $attendance_id)
	{
		$attendance = AttendanceRecord::findOrFail($attendance_id);
		$attendance->delete();

		return redirect()->back()->with('success', 'Attendance record deleted successfully!');
	}

	public function bulk_update(Request $request, int $club_id)
	{
		$request->validate([
			'session_id' => ['required', 'exists:sessions_schedule,id'],
			'attendance' => ['required', 'array'],
			'attendance.*.student_id' => ['required', 'exists:students,id'],
			'attendance.*.status' => ['required', 'in:present,absent,late,excused'],
			'attendance.*.notes' => ['nullable', 'string', 'max:500'],
		]);

		foreach ($request->attendance as $record) {
			AttendanceRecord::updateOrCreate(
				[
					'student_id' => $record['student_id'],
					'session_id' => $request->session_id,
				],
				[
					'attendance_status' => $record['status'],
					'attendance_notes' => $record['notes'] ?? null,
				]
			);
		}

		return redirect()->back()->with('success', 'Bulk attendance updated successfully!');
	}

	public function update_attendance(Request $request, int $club_id)
	{
		$request->validate([
			'student_id' => ['required', 'exists:students,id'],
			'session_id' => ['required', 'exists:sessions_schedule,id'],
			'status' => ['required', 'in:present,absent,late,excused'],
			'notes' => ['nullable', 'string', 'max:500'],
		]);

		AttendanceRecord::updateOrCreate(
			[
				'student_id' => $request->student_id,
				'session_id' => $request->session_id,
			],
			[
				'attendance_status' => $request->status,
				'attendance_notes' => $request->notes,
			]
		);

		return response()->json(['success' => true]);
	}
}


