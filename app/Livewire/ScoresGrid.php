<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\AssessmentScore;
use Livewire\Component;

class ScoresGrid extends Component
{
	public int $assessment_id;
	public array $score_value_by_student = [];
	public array $score_max_value_by_student = [];

	public function mount(int $assessment_id): void
	{
		$this->assessment_id = $assessment_id;
		$this->load_scores();
	}

	public function load_scores(): void
	{
		$assessment = Assessment::with(['club.students', 'scores'])->findOrFail($this->assessment_id);
		$this->score_value_by_student = [];
		$this->score_max_value_by_student = [];
		foreach ($assessment->club->students as $student) {
			$score = $assessment->scores->firstWhere('student_id', $student->id);
			$this->score_value_by_student[$student->id] = $score?->score_value ?? null;
			$this->score_max_value_by_student[$student->id] = $score?->score_max_value ?? 100;
		}
	}

	public function save_scores(): void
	{
		$assessment = Assessment::with('club')->findOrFail($this->assessment_id);
		foreach ($this->score_value_by_student as $student_id => $value) {
			$max = $this->score_max_value_by_student[$student_id] ?? 100;
			AssessmentScore::updateOrCreate(
				['assessment_id' => $this->assessment_id, 'student_id' => $student_id],
				['score_value' => $value, 'score_max_value' => $max]
			);
		}
		$this->dispatch('scores-saved');
	}

	public function render()
	{
		$assessment = Assessment::with('club.students')->findOrFail($this->assessment_id);
		return view('livewire.scores-grid', compact('assessment'));
	}
}


