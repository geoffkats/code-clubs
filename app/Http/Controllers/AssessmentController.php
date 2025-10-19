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
		$assessments = Assessment::with(['club.school', 'questions'])
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
			'questions' => ['nullable', 'array'],
			'questions.*.type' => ['required_with:questions', 'in:multiple_choice,practical_project,image_question,text_question'],
			'questions.*.question_text' => ['required_with:questions'],
			'questions.*.points' => ['required_with:questions', 'integer', 'min:1'],
			'questions.*.image_file' => ['nullable', 'image', 'max:5120'], // 5MB max for images
		]);
		
		$data['club_id'] = $club->id;
		$assessment = Assessment::create($data);
		
		// Handle questions creation
		if ($request->has('questions') && is_array($request->questions)) {
			foreach ($request->questions as $index => $questionData) {
				// Ensure question_type is set
				$questionType = $questionData['type'] ?? null;
				if (!$questionType) {
					continue; // Skip if no question type
				}
				
				$questionData['assessment_id'] = $assessment->id;
				$questionData['order'] = $index + 1;
				$questionData['question_type'] = $questionType;
				
				// Handle different question types
				switch ($questionType) {
					case 'multiple_choice':
						$questionData['question_options'] = [
							'A' => $questionData['option_a'] ?? '',
							'B' => $questionData['option_b'] ?? '',
							'C' => $questionData['option_c'] ?? '',
							'D' => $questionData['option_d'] ?? '',
						];
						unset($questionData['option_a'], $questionData['option_b'], $questionData['option_c'], $questionData['option_d']);
						break;
						
					case 'practical_project':
						if (isset($questionData['project_requirements'])) {
							$requirements = explode("\n", $questionData['project_requirements']);
							$questionData['project_requirements'] = array_map('trim', array_filter($requirements));
						}
						break;
						
					case 'image_question':
						// Handle image upload
						if ($request->hasFile("questions.{$index}.image_file")) {
							$imageFile = $request->file("questions.{$index}.image_file");
							$filename = time() . '_' . $imageFile->getClientOriginalName();
							$path = $imageFile->storeAs('assessment_images', $filename, 'public');
							
							$questionData['image_url'] = $path;
							$questionData['image_filename'] = $imageFile->getClientOriginalName();
						}
						break;
				}
				
				// Remove fields that are not database columns
				unset($questionData['type'], $questionData['image_file']);
				
				\App\Models\AssessmentQuestion::create($questionData);
			}
		}
		
		// Handle file uploads
		if ($request->hasFile('attachments')) {
			$fileUploadService->uploadMultipleFiles(
				$request->file('attachments'),
				$assessment,
				'Assessment attachment'
			);
		}
		
		return redirect()->route('assessments.index')->with('success', 'Assessment created successfully with ' . count($request->questions ?? []) . ' questions!');
	}

	public function show(int $assessment_id)
	{
		$assessment = Assessment::with(['club.school', 'questions', 'scores.student'])
			->findOrFail($assessment_id);
			
		return view('assessments.show', compact('assessment'));
	}

	public function edit(int $assessment_id)
	{
		$assessment = Assessment::with(['club.school', 'questions'])
			->findOrFail($assessment_id);
		$clubs = Club::with('school')->orderBy('club_name')->get();
		
		return view('assessments.edit', compact('assessment', 'clubs'));
	}

	public function update(Request $request, int $assessment_id, FileUploadService $fileUploadService)
	{
		$assessment = Assessment::findOrFail($assessment_id);
		
		$data = $request->validate([
			'assessment_type' => ['required', 'in:quiz,assignment,test,project'],
			'assessment_name' => ['required', 'string'],
			'total_points' => ['nullable', 'integer', 'min:1'],
			'due_date' => ['nullable', 'date'],
			'description' => ['nullable', 'string'],
			'attachments.*' => ['nullable', 'file', 'max:10240'],
			'questions' => ['nullable', 'array'],
			'questions.*.type' => ['required_with:questions', 'in:multiple_choice,practical_project,image_question,text_question'],
			'questions.*.question_text' => ['required_with:questions'],
			'questions.*.points' => ['required_with:questions', 'integer', 'min:1'],
			'questions.*.image_file' => ['nullable', 'image', 'max:5120'],
		]);
		
		// Update assessment
		$assessment->update($data);
		
		// Handle questions update
		if ($request->has('questions') && is_array($request->questions)) {
			// Delete existing questions
			$assessment->questions()->delete();
			
			// Create new questions
			foreach ($request->questions as $index => $questionData) {
				$questionType = $questionData['type'] ?? null;
				if (!$questionType) {
					continue;
				}
				
				$questionData['assessment_id'] = $assessment->id;
				$questionData['order'] = $index + 1;
				$questionData['question_type'] = $questionType;
				
				// Handle different question types
				switch ($questionType) {
					case 'multiple_choice':
						$questionData['question_options'] = [
							'A' => $questionData['option_a'] ?? '',
							'B' => $questionData['option_b'] ?? '',
							'C' => $questionData['option_c'] ?? '',
							'D' => $questionData['option_d'] ?? '',
						];
						unset($questionData['option_a'], $questionData['option_b'], $questionData['option_c'], $questionData['option_d']);
						break;
						
					case 'practical_project':
						if (isset($questionData['project_requirements'])) {
							$requirements = explode("\n", $questionData['project_requirements']);
							$questionData['project_requirements'] = array_map('trim', array_filter($requirements));
						}
						break;
						
					case 'image_question':
						// Handle image upload
						if ($request->hasFile("questions.{$index}.image_file")) {
							$imageFile = $request->file("questions.{$index}.image_file");
							$filename = time() . '_' . $imageFile->getClientOriginalName();
							$path = $imageFile->storeAs('assessment_images', $filename, 'public');
							
							$questionData['image_url'] = $path;
							$questionData['image_filename'] = $imageFile->getClientOriginalName();
						}
						break;
				}
				
				// Remove fields that are not database columns
				unset($questionData['type'], $questionData['image_file']);
				
				\App\Models\AssessmentQuestion::create($questionData);
			}
		}
		
		// Handle file uploads
		if ($request->hasFile('attachments')) {
			$fileUploadService->uploadMultipleFiles(
				$request->file('attachments'),
				$assessment,
				'Assessment attachment'
			);
		}
		
		return redirect()->route('assessments.show', $assessment->id)
			->with('success', 'Assessment updated successfully!');
	}

	public function destroy(int $assessment_id)
	{
		$assessment = Assessment::findOrFail($assessment_id);
		$assessment->delete();
		
		return redirect()->route('assessments.index')
			->with('success', 'Assessment deleted successfully!');
	}

	public function scores(int $assessment_id)
	{
		$assessment = Assessment::with(['club.school', 'scores.student'])->findOrFail($assessment_id);
		$scores = $assessment->scores;
		$students = $assessment->club->students;
		
		return view('assessments.scores', compact('assessment', 'scores', 'students'));
	}
}


