<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Club;
use App\Models\Report;
use App\Models\SessionSchedule;

class ReportGeneratorService
{
	public function generate_reports_for_club(int $club_id, array $options = []): void
	{
		$club = Club::with([
			'students', 
			'assessments.scores', 
			'sessions.attendance_records',
			'attachments'
		])->findOrFail($club_id);

		$reportType = $options['report_type'] ?? 'comprehensive';
		$dateRange = $options['date_range'] ?? 'month';
		$includeCharts = $options['include_charts'] ?? true;
		$sections = $options['sections'] ?? [];

		foreach ($club->students as $student) {
			$attendance_percent = $this->calculate_attendance_percent($club, $student->id);
			$overall_score = $this->calculate_overall_score($club, $student->id);
			$summary_text = $this->build_summary_text($club, $attendance_percent, $overall_score, $options);
			
			// Get detailed assessment data
			$assessment_data = $this->get_assessment_data($club, $student->id);
			
			// Get Scratch project attachments
			$scratch_projects = $this->get_scratch_projects($club, $student->id);

			Report::updateOrCreate(
				['club_id' => $club->id, 'student_id' => $student->id],
				[
					'report_name' => $student->student_first_name.' '.$student->student_last_name.' - '.$club->club_name.' Report',
					'report_summary_text' => $summary_text,
					'report_overall_score' => $overall_score,
					'report_generated_at' => now(),
				]
			);
		}
	}

	private function calculate_attendance_percent(Club $club, int $student_id): float
	{
		$total_sessions = max(1, $club->sessions->count());
		$present = 0;
		foreach ($club->sessions as $session) {
			$record = $session->attendance_records->firstWhere('student_id', $student_id);
			if ($record && $record->attendance_status === 'present') {
				$present++;
			}
		}
		return round(($present / $total_sessions) * 100, 2);
	}

	private function calculate_overall_score(Club $club, int $student_id): float
	{
		$assessments = $club->assessments;
		if ($assessments->isEmpty()) {
			return 0.0;
		}
		$total = 0.0;
		$count = 0;
		foreach ($assessments as $assessment) {
			$score = $assessment->scores->firstWhere('student_id', $student_id);
			if ($score && $score->score_max_value > 0) {
				$total += ($score->score_value / $score->score_max_value) * 100.0;
				$count++;
			}
		}
		return $count > 0 ? round($total / $count, 2) : 0.0;
	}

	private function build_summary_text(Club $club, float $attendance_percent, float $overall_score, array $options = []): string
	{
		$band = $overall_score >= 85 ? 'Excellent' : ($overall_score >= 70 ? 'Good' : ($overall_score >= 50 ? 'Developing' : 'Foundational'));
		$reportType = $options['report_type'] ?? 'comprehensive';
		$dateRange = $options['date_range'] ?? 'month';
		
		$baseText = sprintf(
			"%s level %s club. Attendance: %s%%. Performance: %s (%s%%).",
			$club->club_name,
			$club->club_level ?: 'beginner',
			number_format($attendance_percent, 2),
			$band,
			number_format($overall_score, 2)
		);
		
		// Add report type and date range info
		$typeText = match($reportType) {
			'progress' => 'This is a progress report',
			'attendance' => 'This is an attendance report',
			'assessment' => 'This is an assessment report',
			'comprehensive' => 'This is a comprehensive report',
			default => 'This is a custom report'
		};
		
		$dateText = match($dateRange) {
			'week' => 'covering the last week',
			'month' => 'covering the last month',
			'quarter' => 'covering the last quarter',
			'year' => 'covering the last year',
			'custom' => 'covering the specified date range',
			default => 'covering the last month'
		};
		
		return $typeText . ' ' . $dateText . '. ' . $baseText;
	}

	private function get_assessment_data(Club $club, int $student_id): array
	{
		$assessments = [];
		foreach ($club->assessments as $assessment) {
			$score = $assessment->scores->firstWhere('student_id', $student_id);
			$assessments[] = [
				'name' => $assessment->assessment_name,
				'type' => $assessment->assessment_type,
				'week' => $assessment->assessment_week_number,
				'score' => $score ? $score->score_value : null,
				'max_score' => $score ? $score->score_max_value : 100,
				'percentage' => $score && $score->score_max_value > 0 ? 
					round(($score->score_value / $score->score_max_value) * 100, 2) : 0
			];
		}
		return $assessments;
	}

	private function get_scratch_projects(Club $club, int $student_id): array
	{
		// Get attachments that are Scratch projects for this student
		$scratch_projects = \App\Models\Attachment::where('attachable_type', 'App\\Models\\Assessment')
			->whereHas('attachable', function($q) use ($club) {
				$q->where('club_id', $club->id);
			})
			->where(function($q) {
				$q->where('file_type', 'scratch')
					->orWhere('file_name', 'like', '%.sb3')
					->orWhere('description', 'like', '%scratch%');
			})
			->get()
			->map(function($attachment) {
				return [
					'name' => $attachment->file_name,
					'description' => $attachment->description,
					'file_path' => $attachment->file_path,
					'created_at' => $attachment->created_at,
					'file_size' => $attachment->file_size
				];
			})
			->toArray();

		return $scratch_projects;
	}
}


