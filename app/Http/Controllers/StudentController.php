<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Club;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
	public function index()
	{
		$students = Student::with(['clubs.school'])
			->paginate(20);
		$clubs = Club::with('school')->orderBy('club_name')->get();
		$schools = School::orderBy('school_name')->get();
		return view('students.index', compact('students', 'clubs', 'schools'));
	}

	public function create()
	{
		$clubs = Club::with('school')->orderBy('club_name')->get();
		return view('students.create', compact('clubs'));
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'club_id' => ['required', 'exists:clubs,id'],
			'student_first_name' => ['required', 'string'],
			'student_last_name' => ['required', 'string'],
			'student_grade_level' => ['required', 'string'],
			'student_parent_name' => ['required', 'string'],
			'student_parent_email' => ['required', 'email'],
			'student_parent_phone' => ['nullable', 'string'],
			'student_medical_info' => ['nullable', 'string'],
		]);

		// Get the club to determine the school
		$club = Club::findOrFail($data['club_id']);
		$data['school_id'] = $club->school_id;

		// Create the student
		$student = Student::create($data);

		// Enroll the student in the club
		$student->clubs()->attach($data['club_id']);

		// Redirect back to the page that called this (clubs or students)
		if ($request->has('redirect_to')) {
			return redirect($request->input('redirect_to'))->with('success', 'Student enrolled successfully!');
		}

		return redirect()->route('students.index')->with('success', 'Student added and enrolled in club successfully!');
	}

	public function show(Student $student)
	{
		$student->load(['clubs.school']);
		return view('students.show', compact('student'));
	}

	public function edit(Student $student)
	{
		$clubs = Club::with('school')->orderBy('club_name')->get();
		$student->load(['clubs']);
		return view('students.edit', compact('student', 'clubs'));
	}

	public function update(Request $request, Student $student)
	{
		$data = $request->validate([
			'student_first_name' => ['required', 'string'],
			'student_last_name' => ['required', 'string'],
			'student_grade_level' => ['required', 'string'],
			'student_parent_name' => ['required', 'string'],
			'student_parent_email' => ['required', 'email'],
			'student_parent_phone' => ['nullable', 'string'],
			'student_medical_info' => ['nullable', 'string'],
		]);

		$student->update($data);

		return redirect()->route('students.index')->with('success', 'Student updated successfully!');
	}

	public function destroy(Student $student)
	{
		$student->delete();
		return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
	}

	/**
	 * Bulk enroll all students in a school into a club
	 */
	public function bulkEnroll(Request $request)
	{
		$data = Validator::make($request->all(), [
			'school_id' => ['required', 'exists:schools,id'],
			'club_id' => ['required', 'exists:clubs,id'],
		])->validate();

		$schoolId = (int) $data['school_id'];
		$clubId = (int) $data['club_id'];

		// Get student IDs for the school
		$studentIds = Student::where('school_id', $schoolId)->pluck('id');

		if ($studentIds->isEmpty()) {
			return back()->with('error', 'No students found for the selected school.');
		}

		// Insert missing enrollments only
		$now = now();
		$rows = [];
		foreach ($studentIds as $studentId) {
			$rows[] = [
				'club_id' => $clubId,
				'student_id' => $studentId,
				'created_at' => $now,
				'updated_at' => $now,
			];
		}

		// Avoid duplicates by ignoring existing pairs
		DB::table('club_enrollments')->upsert(
			$rows,
			['club_id', 'student_id'],
			['updated_at']
		);

		return back()->with('success', 'Students enrolled into the club successfully.');
	}
}


