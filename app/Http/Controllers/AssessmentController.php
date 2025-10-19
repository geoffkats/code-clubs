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
			// Get existing questions to preserve image data
			$existingQuestions = $assessment->questions->keyBy('order');
			
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
						} else {
							// Preserve existing image data if no new image uploaded
							$existingQuestion = $existingQuestions->get($index + 1);
							if ($existingQuestion && $existingQuestion->question_type === 'image_question') {
								$questionData['image_url'] = $existingQuestion->image_url;
								$questionData['image_filename'] = $existingQuestion->image_filename;
							}
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
		$assessment = Assessment::with(['club.school', 'scores.student', 'questions'])->findOrFail($assessment_id);
		$scores = $assessment->scores;
		$students = $assessment->club->students;
		
		return view('assessments.scores', compact('assessment', 'scores', 'students'));
	}

	public function store_scores(Request $request, int $assessment_id)
	{
		$assessment = Assessment::findOrFail($assessment_id);
		
		$request->validate([
			'scores' => ['required', 'array'],
			'scores.*.student_id' => ['required', 'integer', 'exists:students,id'],
			'scores.*.score' => ['required', 'numeric', 'min:0', 'max:100'],
			'scores.*.feedback' => ['nullable', 'string', 'max:1000'],
		]);
		
		foreach ($request->scores as $scoreData) {
			\App\Models\AssessmentScore::updateOrCreate(
				[
					'assessment_id' => $assessment->id,
					'student_id' => $scoreData['student_id']
				],
				[
					'score' => $scoreData['score'],
					'feedback' => $scoreData['feedback'] ?? '',
				]
			);
		}
		
		return redirect()->route('assessments.scores', $assessment->id)
			->with('success', 'Assessment scores saved successfully!');
	}

	public function ai_generate(Request $request, int $club_id)
	{
		$club = Club::with('students')->findOrFail($club_id);
		
		$request->validate([
			'topic' => ['required', 'string', 'max:255'],
			'difficulty' => ['required', 'in:beginner,intermediate,advanced'],
			'question_count' => ['required', 'integer', 'min:3', 'max:20'],
			'assessment_type' => ['required', 'in:quiz,assignment,test,project'],
		]);
		
		// AI-generated assessment data
		$aiData = $this->generateAIAssessment(
			$request->topic,
			$request->difficulty,
			$request->question_count,
			$request->assessment_type,
			$club->club_name
		);
		
		// Create the assessment
		$assessment = Assessment::create([
			'club_id' => $club->id,
			'assessment_type' => $request->assessment_type,
			'assessment_name' => $aiData['title'],
			'description' => $aiData['description'],
			'total_points' => $aiData['total_points'],
			'due_date' => now()->addDays(7), // Default due in 1 week
		]);
		
		// Create questions
		foreach ($aiData['questions'] as $index => $questionData) {
			\App\Models\AssessmentQuestion::create([
				'assessment_id' => $assessment->id,
				'question_type' => $questionData['type'],
				'question_text' => $questionData['text'],
				'question_options' => $questionData['options'] ?? null,
				'correct_answer' => $questionData['correct_answer'] ?? null,
				'project_instructions' => $questionData['instructions'] ?? null,
				'project_requirements' => $questionData['requirements'] ?? null,
				'project_output_format' => $questionData['output_format'] ?? null,
				'points' => $questionData['points'],
				'order' => $index + 1,
			]);
		}
		
		return redirect()->route('assessments.show', $assessment->id)
			->with('success', "AI-generated assessment '{$assessment->assessment_name}' created successfully with {$request->question_count} questions!");
	}
	
	private function generateAIAssessment($topic, $difficulty, $questionCount, $assessmentType, $clubName)
	{
		// This is a simplified AI generation - in a real implementation, you'd use an AI service
		$difficultyMultiplier = match($difficulty) {
			'beginner' => 1,
			'intermediate' => 1.5,
			'advanced' => 2,
		};
		
		$questions = [];
		$totalPoints = 0;
		
		// Generate different types of questions based on assessment type
		$questionTypes = match($assessmentType) {
			'quiz' => ['multiple_choice', 'multiple_choice', 'multiple_choice', 'text_question'],
			'test' => ['multiple_choice', 'multiple_choice', 'text_question', 'practical_project'],
			'assignment' => ['text_question', 'practical_project', 'text_question'],
			'project' => ['practical_project', 'practical_project', 'text_question'],
		};
		
		for ($i = 0; $i < $questionCount; $i++) {
			$questionType = $questionTypes[$i % count($questionTypes)];
			$points = (int)(5 * $difficultyMultiplier);
			$totalPoints += $points;
			
			$question = [
				'type' => $questionType,
				'text' => "Question " . ($i + 1) . " about {$topic}",
				'points' => $points,
			];
			
			switch ($questionType) {
				case 'multiple_choice':
					$question['options'] = [
						'A' => "Option A for {$topic}",
						'B' => "Option B for {$topic}",
						'C' => "Option C for {$topic}",
						'D' => "Option D for {$topic}",
					];
					$question['correct_answer'] = ['A', 'B', 'C', 'D'][array_rand(['A', 'B', 'C', 'D'])];
					break;
					
				case 'practical_project':
					$question['instructions'] = "Create a {$topic} project for {$clubName}";
					$question['requirements'] = [
						"Must demonstrate understanding of {$topic}",
						"Should be interactive and engaging",
						"Must include proper documentation",
					];
					$question['output_format'] = 'scratch_project';
					break;
					
				case 'text_question':
					$question['correct_answer'] = "Sample answer for {$topic} question";
					break;
			}
			
			$questions[] = $question;
		}
		
		return [
			'title' => "{$difficulty} {$topic} Assessment",
			'description' => "AI-generated assessment on {$topic} for {$clubName} students at {$difficulty} level",
			'total_points' => $totalPoints,
			'questions' => $questions,
		];
	}
}


