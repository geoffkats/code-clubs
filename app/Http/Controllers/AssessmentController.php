<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Club;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
	public function index()
	{
		$assessments = Assessment::with(['club.school'])
			->withCount(['scores'])
			->orderBy('created_at', 'desc')
			->paginate(20);
			
		$clubs = Club::with('school')->orderBy('club_name')->get();
		
		return view('assessments.index', compact('assessments', 'clubs'));
	}

	public function create(int $club_id)
	{
		$club = Club::findOrFail($club_id);
		return view('assessments.create', compact('club'));
	}

	public function store(Request $request, int $club_id, FileUploadService $fileUploadService)
	{
		$club = Club::findOrFail($club_id);
		$data = $request->validate([
			'assessment_type' => ['required', 'in:quiz,assignment,test,project'],
			'assessment_name' => ['required', 'string'],
			'total_points' => ['nullable', 'integer', 'min:1'],
			'due_date' => ['nullable', 'date'],
			'description' => ['nullable', 'string'],
			'attachments.*' => ['nullable', 'file', 'max:10240'], // 10MB max
		]);
		$data['club_id'] = $club->id;
		$assessment = Assessment::create($data);
		
		// Handle file uploads
		if ($request->hasFile('attachments')) {
			$fileUploadService->uploadMultipleFiles(
				$request->file('attachments'),
				$assessment,
				'Assessment attachment'
			);
		}
		
		return redirect()->route('assessments.index')->with('success', 'Assessment created successfully!');
	}

	public function scores(int $assessment_id)
	{
		$assessment = Assessment::with(['club.school', 'scores.student'])->findOrFail($assessment_id);
		$scores = $assessment->scores;
		$students = $assessment->club->students;
		
		return view('assessments.scores', compact('assessment', 'scores', 'students'));
	}
}


