<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
	public function index()
	{
		$schools = School::query()
			->withCount(['clubs', 'students'])
			->orderBy('school_name')
			->paginate(20);
		return view('schools.index', compact('schools'));
	}

	public function create()
	{
		return view('schools.create');
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'school_name' => ['required', 'string'],
			'address' => ['nullable', 'string'],
			'contact_email' => ['nullable', 'email'],
		]);
		School::create($data);
		return redirect()->route('schools.index')->with('success', 'School created successfully!');
	}

	public function update(Request $request, int $school_id)
	{
		$data = $request->validate([
			'school_name' => ['required', 'string'],
			'address' => ['nullable', 'string'],
			'contact_email' => ['nullable', 'email'],
		]);
		$school = School::findOrFail($school_id);
		$school->update($data);
		return redirect()->route('schools.index')->with('success', 'School updated successfully!');
	}

	public function destroy(int $school_id)
	{
		$school = School::findOrFail($school_id);
		$school->delete();
		return redirect()->route('schools.index')->with('success', 'School deleted successfully!');
	}
}


