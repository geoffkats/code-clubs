<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Club;
use App\Models\Report;
use App\Models\SessionSchedule;

class ReportGeneratorService
{
	public function generate_reports_for_club(int $club_id): void
	{
		$club = Club::with(['students', 'assessments.scores', 'sessions.attendance_records'])
			->findOrFail($club_id);

		foreach ($club->students as $student) {
			$attendance_percent = $this->calculate_attendance_percent($club, $student->id);
			$overall_score = $this->calculate_overall_score($club, $student->id);
			$summary_text = $this->build_summary_text($club, $attendance_percent, $overall_score);

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

	private function build_summary_text(Club $club, float $attendance_percent, float $overall_score): string
	{
		$band = $overall_score >= 85 ? 'Excellent' : ($overall_score >= 70 ? 'Good' : ($overall_score >= 50 ? 'Developing' : 'Foundational'));
		return sprintf(
			"%s level %s club. Attendance: %s%%. Performance: %s (%s%%).",
			$club->club_name,
			$club->club_level ?: 'beginner',
			number_format($attendance_percent, 2),
			$band,
			number_format($overall_score, 2)
		);
	}
}


