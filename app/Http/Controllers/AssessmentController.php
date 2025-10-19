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
			'student_id' => ['required', 'integer', 'exists:students,id'],
			'score' => ['required', 'numeric', 'min:0'],
		]);
		
		\App\Models\AssessmentScore::updateOrCreate(
			[
				'assessment_id' => $assessment->id,
				'student_id' => $request->student_id
			],
			[
				'score_value' => $request->score,
				'score_max_value' => $assessment->total_points ?? 100,
			]
		);
		
		return redirect()->route('assessments.scores', $assessment->id)
			->with('success', 'Assessment score saved successfully!');
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
	
	private function getQuestionTypesForQuiz($topic, $questionCount)
	{
		if (str_contains(strtolower($topic), 'scratch')) {
			return ['multiple_choice', 'image_question', 'multiple_choice', 'image_question', 'text_question'];
		}
		return ['multiple_choice', 'multiple_choice', 'multiple_choice', 'text_question'];
	}

	private function getQuestionTypesForTest($topic, $questionCount)
	{
		if (str_contains(strtolower($topic), 'scratch')) {
			return ['multiple_choice', 'image_question', 'text_question', 'practical_project', 'image_question'];
		}
		return ['multiple_choice', 'multiple_choice', 'text_question', 'practical_project'];
	}

	private function getQuestionTypesForAssignment($topic, $questionCount)
	{
		if (str_contains(strtolower($topic), 'scratch')) {
			return ['image_question', 'practical_project', 'text_question', 'image_question'];
		}
		return ['text_question', 'practical_project', 'text_question'];
	}

	private function getQuestionTypesForProject($topic, $questionCount)
	{
		if (str_contains(strtolower($topic), 'scratch')) {
			return ['image_question', 'practical_project', 'practical_project', 'text_question'];
		}
		return ['practical_project', 'practical_project', 'text_question'];
	}

	private function generateAIAssessment($topic, $difficulty, $questionCount, $assessmentType, $clubName)
	{
		$difficultyMultiplier = match($difficulty) {
			'beginner' => 1,
			'intermediate' => 1.5,
			'advanced' => 2,
		};
		
		$questions = [];
		$totalPoints = 0;
		
		// Get topic-specific content
		$topicContent = $this->getTopicContent($topic);
		
		// Generate different types of questions based on assessment type and topic
		$questionTypes = match($assessmentType) {
			'quiz' => $this->getQuestionTypesForQuiz($topic, $questionCount),
			'test' => $this->getQuestionTypesForTest($topic, $questionCount),
			'assignment' => $this->getQuestionTypesForAssignment($topic, $questionCount),
			'project' => $this->getQuestionTypesForProject($topic, $questionCount),
		};
		
		for ($i = 0; $i < $questionCount; $i++) {
			$questionType = $questionTypes[$i % count($questionTypes)];
			$points = (int)(5 * $difficultyMultiplier);
			$totalPoints += $points;
			
			$question = [
				'type' => $questionType,
				'text' => $this->generateQuestionText($topic, $topicContent, $i + 1, $questionType, $difficulty),
				'points' => $points,
			];
			
			switch ($questionType) {
				case 'multiple_choice':
					$question['options'] = $this->generateMultipleChoiceOptions($topic, $topicContent, $difficulty);
					$question['correct_answer'] = $question['options']['correct'];
					unset($question['options']['correct']);
					break;
					
				case 'practical_project':
					$question['instructions'] = $this->generateProjectInstructions($topic, $topicContent, $clubName, $difficulty);
					$question['requirements'] = $this->generateProjectRequirements($topic, $topicContent, $difficulty);
					$question['output_format'] = $topicContent['output_format'];
					break;
					
				case 'text_question':
					$question['correct_answer'] = $this->generateTextAnswer($topic, $topicContent, $difficulty);
					break;
					
				case 'image_question':
					$question['image_description'] = $this->generateImageQuestionDescription($topic, $topicContent, $difficulty);
					$question['correct_answer'] = $this->generateImageQuestionAnswer($topic, $topicContent, $difficulty);
					break;
			}
			
			$questions[] = $question;
		}
		
		return [
			'title' => "{$topic} Assessment - {$difficulty} Level",
			'description' => "AI-generated assessment on {$topic} for {$clubName} students at {$difficulty} level",
			'total_points' => $totalPoints,
			'questions' => $questions,
		];
	}
	
	private function getTopicContent($topic)
	{
		$content = [
			'scratch_basics' => [
				'concepts' => ['sprites', 'motion blocks', 'events', 'loops', 'costumes', 'stage', 'backdrop'],
				'output_format' => 'scratch_project',
				'questions' => [
					'What is the purpose of motion blocks in Scratch?',
					'How do you make a sprite move continuously?',
					'What is the difference between forever and repeat loops?'
				],
				'block_types' => ['motion', 'looks', 'sound', 'events', 'control', 'sensing', 'operators', 'variables']
			],
			'scratch_animations' => [
				'concepts' => ['animation', 'costumes', 'timing', 'transitions', 'effects', 'sprites'],
				'output_format' => 'scratch_project',
				'questions' => [
					'How do you create smooth animations in Scratch?',
					'What is the purpose of costume changes?',
					'How do you control animation timing?'
				],
				'block_types' => ['looks', 'motion', 'control', 'events']
			],
			'scratch_games' => [
				'concepts' => ['game mechanics', 'scoring', 'collision detection', 'levels', 'player interaction'],
				'output_format' => 'scratch_project',
				'questions' => [
					'How do you detect collisions between sprites?',
					'What is the purpose of scoring systems?',
					'How do you create different game levels?'
				],
				'block_types' => ['sensing', 'control', 'variables', 'operators', 'motion']
			],
			'scratch_storytelling' => [
				'concepts' => ['narrative', 'dialogue', 'scenes', 'characters', 'interaction'],
				'output_format' => 'scratch_project',
				'questions' => [
					'How do you create interactive stories?',
					'What is the purpose of dialogue in storytelling?',
					'How do you manage scene transitions?'
				],
				'block_types' => ['looks', 'sound', 'control', 'events']
			],
			'python_basics' => [
				'concepts' => ['variables', 'loops', 'functions', 'conditionals', 'data types'],
				'output_format' => 'python_file',
				'questions' => [
					'What is the difference between a variable and a constant?',
					'How do you create a function in Python?',
					'What are the different data types in Python?'
				]
			],
			'robotics_basics' => [
				'concepts' => ['sensors', 'motors', 'programming', 'circuitry', 'automation'],
				'output_format' => 'robotics_project',
				'questions' => [
					'How do sensors help robots make decisions?',
					'What is the purpose of motors in robotics?',
					'How do you program a robot to follow a line?'
				]
			],
			'html_basics' => [
				'concepts' => ['tags', 'elements', 'attributes', 'structure', 'forms'],
				'output_format' => 'html_file',
				'questions' => [
					'What is the purpose of HTML tags?',
					'How do you create a hyperlink in HTML?',
					'What is the difference between div and span elements?'
				]
			]
		];
		
		return $content[$topic] ?? [
			'concepts' => ['programming', 'logic', 'problem solving'],
			'output_format' => 'scratch_project',
			'questions' => ['What is programming?', 'How do you solve problems with code?']
		];
	}
	
	private function generateQuestionText($topic, $content, $number, $type, $difficulty)
	{
		$questions = $content['questions'];
		$baseQuestion = $questions[($number - 1) % count($questions)];
		
		if ($type === 'practical_project') {
			return "Create a {$topic} project that demonstrates your understanding of the concepts we've covered.";
		}
		
		if ($type === 'image_question') {
			return "Q{$number}: Look at the image below and answer the following question: {$baseQuestion}";
		}
		
		return "Q{$number}: {$baseQuestion}";
	}
	
	private function generateMultipleChoiceOptions($topic, $content, $difficulty)
	{
		$concepts = $content['concepts'];
		$correctAnswer = $concepts[array_rand($concepts)];
		
		$options = ['A', 'B', 'C', 'D'];
		$wrongAnswers = array_diff($concepts, [$correctAnswer]);
		
		if (count($wrongAnswers) < 3) {
			$wrongAnswers = array_merge($wrongAnswers, ['variables', 'functions', 'loops', 'conditions']);
		}
		
		$selectedWrong = array_slice(array_values($wrongAnswers), 0, 3);
		$allOptions = array_merge([$correctAnswer], $selectedWrong);
		shuffle($allOptions);
		
		$result = [];
		foreach ($options as $key => $option) {
			$result[$option] = $allOptions[$key];
			if ($allOptions[$key] === $correctAnswer) {
				$result['correct'] = $option;
			}
		}
		
		return $result;
	}
	
	private function generateProjectInstructions($topic, $content, $clubName, $difficulty)
	{
		$baseInstructions = [
			'Scratch Basics' => "Create an interactive Scratch project that demonstrates your understanding of sprites, motion, and events. Your project should be engaging and educational.",
			'Python Basics' => "Write a Python program that demonstrates your understanding of variables, loops, and functions. Include comments explaining your code.",
			'Robotics Projects' => "Design and program a robot that can perform a specific task using sensors and motors. Document your design process.",
			'HTML Basics' => "Create a complete HTML webpage that demonstrates proper use of tags, elements, and structure. Make it visually appealing."
		];
		
		return $baseInstructions[$topic] ?? "Create a project that demonstrates your understanding of {$topic} concepts.";
	}
	
	private function generateProjectRequirements($topic, $content, $difficulty)
	{
		$baseRequirements = [
			'Scratch Basics' => [
				"Use at least 3 different types of motion blocks",
				"Include at least one event trigger",
				"Use loops to create continuous movement",
				"Add sound effects or visual feedback"
			],
			'Python Basics' => [
				"Define at least 2 functions",
				"Use variables to store data",
				"Include at least one loop",
				"Add comments explaining your code"
			],
			'Robotics Projects' => [
				"Use at least 2 different sensors",
				"Program the robot to respond to sensor input",
				"Create a clear task objective",
				"Document the programming logic"
			],
			'HTML Basics' => [
				"Use proper HTML5 structure",
				"Include at least 5 different HTML tags",
				"Add a form with input elements",
				"Ensure the page is well-organized"
			]
		];
		
		return $baseRequirements[$topic] ?? [
			"Demonstrate understanding of core concepts",
			"Include proper documentation",
			"Make the project interactive or engaging"
		];
	}
	
	private function generateTextAnswer($topic, $content, $difficulty)
	{
		$answers = [
			'scratch_basics' => "Scratch uses visual blocks to create programs. Sprites are the characters that perform actions, motion blocks control movement, events trigger actions, and loops repeat commands.",
			'scratch_animations' => "Animations in Scratch are created by changing costumes, using timing blocks, and controlling sprite movement. Costumes allow sprites to change appearance, creating visual effects.",
			'scratch_games' => "Game development in Scratch involves collision detection, scoring systems, level progression, and player interaction. Games use sensing blocks to detect user input and sprite interactions.",
			'scratch_storytelling' => "Interactive storytelling in Scratch combines narrative elements with user interaction. Stories use dialogue, scene changes, character movement, and sound to create engaging experiences.",
			'python_basics' => "Python is a programming language that uses variables to store data, functions to organize code, loops to repeat actions, and conditionals to make decisions.",
			'robotics_basics' => "Robotics combines programming, engineering, and problem-solving. Robots use sensors to gather information, processors to make decisions, and actuators to perform actions.",
			'html_basics' => "HTML (HyperText Markup Language) is used to create web pages. It uses tags to define elements, attributes to provide additional information, and structure to organize content."
		];
		
		return $answers[$topic] ?? "This topic involves understanding programming concepts, problem-solving, and creating digital solutions.";
	}

	private function generateImageQuestionDescription($topic, $content, $difficulty)
	{
		$descriptions = [
			'scratch_basics' => [
				"Identify which Scratch block category is shown in the image: motion, looks, sound, events, control, sensing, operators, or variables.",
				"Look at the Scratch blocks in the image and identify what action this code would perform.",
				"Examine the Scratch interface in the image and identify the different components: stage, sprite area, blocks palette, and scripts area.",
				"Identify which type of loop block is shown in the image: repeat, forever, or repeat until."
			],
			'scratch_animations' => [
				"Look at the costume changes in the image and identify what animation technique is being used.",
				"Examine the timing blocks in the image and determine how long the animation will last.",
				"Identify which visual effect block is being used in the image: change color, set size, or change transparency.",
				"Look at the sprite movement pattern in the image and identify the type of motion being created."
			],
			'scratch_games' => [
				"Identify which sensing block in the image would detect when two sprites touch.",
				"Look at the scoring system in the image and identify how points are being tracked.",
				"Examine the game mechanics in the image and identify what type of game is being created.",
				"Identify which operator block in the image would be used to compare two values."
			],
			'scratch_storytelling' => [
				"Look at the dialogue blocks in the image and identify what the character is saying.",
				"Examine the scene transition in the image and identify how the backdrop is being changed.",
				"Identify which sound block in the image would play background music.",
				"Look at the character interaction in the image and identify what event triggers the dialogue."
			]
		];

		$topicDescriptions = $descriptions[$topic] ?? [
			"Examine the programming concept shown in the image and identify what it represents.",
			"Look at the code structure in the image and identify the programming pattern being used."
		];

		return $topicDescriptions[array_rand($topicDescriptions)];
	}

	private function generateImageQuestionAnswer($topic, $content, $difficulty)
	{
		$answers = [
			'scratch_basics' => [
				"Motion blocks control sprite movement and rotation.",
				"Events blocks trigger actions when something happens.",
				"Control blocks manage program flow and loops.",
				"Sensing blocks detect user input and sprite interactions."
			],
			'scratch_animations' => [
				"Costume changes create the illusion of movement.",
				"Timing blocks control animation speed and duration.",
				"Visual effects enhance the appearance of animations.",
				"Motion blocks create smooth sprite movement."
			],
			'scratch_games' => [
				"Collision detection determines when sprites interact.",
				"Scoring systems track player progress and achievements.",
				"Game mechanics define how players interact with the game.",
				"Operator blocks perform mathematical and logical operations."
			],
			'scratch_storytelling' => [
				"Dialogue blocks display character speech and thoughts.",
				"Scene transitions change the story setting and mood.",
				"Sound blocks add audio effects and background music.",
				"Event blocks trigger story progression and character interactions."
			]
		];

		$topicAnswers = $answers[$topic] ?? [
			"This image shows a fundamental programming concept.",
			"The code structure demonstrates good programming practices."
		];

		return $topicAnswers[array_rand($topicAnswers)];
	}
}


