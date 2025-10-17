<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Club;
use Illuminate\Http\Request;

class StudentController extends Controller
{
	public function index()
	{
		$students = Student::with(['clubs.school'])
			->paginate(20);
		$clubs = Club::with('school')->orderBy('club_name')->get();
		return view('students.index', compact('students', 'clubs'));
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
}


