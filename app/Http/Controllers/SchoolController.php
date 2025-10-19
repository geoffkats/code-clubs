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

	public function edit(int $school_id)
	{
		$school = School::findOrFail($school_id);
		return view('schools.edit', compact('school'));
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
		\Log::info('SchoolController::update called with school_id: ' . $school_id);
		\Log::info('Request data: ' . json_encode($request->all()));
		
		$data = $request->validate([
			'school_name' => ['required', 'string'],
			'address' => ['nullable', 'string'],
			'contact_email' => ['nullable', 'email'],
		]);
		$school = School::findOrFail($school_id);
		$school->update($data);
		\Log::info('School updated successfully');
		return redirect()->route('schools.index')->with('success', 'School updated successfully!');
	}

	public function destroy(int $school_id)
	{
		\Log::info('SchoolController::destroy called with school_id: ' . $school_id);
		$school = School::findOrFail($school_id);
		$school->delete();
		\Log::info('School deleted successfully');
		return redirect()->route('schools.index')->with('success', 'School deleted successfully!');
	}
}


