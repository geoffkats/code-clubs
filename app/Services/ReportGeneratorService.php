<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Club;
use App\Models\Report;
use App\Models\SessionSchedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * ReportGeneratorService
 * 
 * Enterprise-grade service for generating comprehensive student reports.
 * Handles calculation of scores, attendance, and automatic access code generation.
 * 
 * @package App\Services
 * @version 1.0.0
 * @author Code Club System
 */
class ReportGeneratorService
{
    /**
     * Dependency injection for access code service
     * 
     * @param AccessCodeService $accessCodeService Service for managing access codes
     */
	public function __construct(
		private AccessCodeService $accessCodeService,
		private AIReportGeneratorService $aiService
	)
	{
	}

    /**
     * Generate comprehensive reports for all students in a club
     * 
     * This method creates detailed reports for each student including:
     * - Attendance calculations
     * - Assessment scores and performance metrics
     * - Summary text with personalized feedback
     * - Automatic access code generation for parent access
     * 
     * @param int $club_id The ID of the club to generate reports for
     * @param array $options Configuration options for report generation
     * @return void
     * @throws \Exception If report generation fails
     */
    public function generate_reports_for_club(int $club_id, array $options = []): void
    {
        try {
            // Increase execution time limit for AI generation
            set_time_limit(120); // 2 minutes
            
            // Start database transaction for data consistency
            DB::beginTransaction();
            
            // Load club with all necessary relationships (limit to prevent timeout)
            $club = Club::with([
                'students' => function($query) {
                    $query->limit(20); // Limit students to prevent timeout
                },
                'assessments.scores' => function($query) {
                    $query->limit(10); // Limit assessments to prevent timeout
                },
                'sessions.attendance_records' => function($query) {
                    $query->limit(50); // Limit sessions to prevent timeout
                },
                'attachments'
            ])->findOrFail($club_id);

            // Extract and validate options
            $reportType = $options['report_type'] ?? 'comprehensive';
            $dateRange = $options['date_range'] ?? 'month';
            $includeCharts = $options['include_charts'] ?? true;
            $sections = $options['sections'] ?? [];

            $generatedCount = 0;
            $errors = [];

            // Process each student in the club
            foreach ($club->students as $student) {
                try {
                    // Calculate student performance metrics
                    $attendance_percent = $this->calculate_attendance_percent($club, $student->id);
                    $overall_score = $this->calculate_overall_score($club, $student->id);
                    $summary_text = $this->build_summary_text($club, $attendance_percent, $overall_score, $options);
                    
                    // Create initial report
                    $report = Report::updateOrCreate(
                        ['club_id' => $club->id, 'student_id' => $student->id],
                        [
                            'report_name' => $student->student_first_name.' '.$student->student_last_name.' - '.$club->club_name.' Report',
                            'report_summary_text' => $summary_text,
                            'report_overall_score' => $overall_score,
                            'report_generated_at' => now(),
                        ]
                    );

                    // Use AI service to generate comprehensive content
                    $aiContent = $this->aiService->generateReportContent($report);
                    
                    // Update report with AI-generated content
                    $report->update($aiContent);

                    // Automatically generate access code for secure parent access
                    // This ensures all reports have proper access controls
                    $this->accessCodeService->create_access_code_for_report($report->id);
                    
                    $generatedCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = [
                        'student_id' => $student->id,
                        'student_name' => $student->student_first_name . ' ' . $student->student_last_name,
                        'error' => $e->getMessage()
                    ];
                    
                    Log::error('Failed to generate report for student', [
                        'student_id' => $student->id,
                        'club_id' => $club_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Commit transaction if all successful
            DB::commit();
            
            // Log successful generation
            Log::info('Reports generated successfully for club', [
                'club_id' => $club_id,
                'club_name' => $club->club_name,
                'generated_count' => $generatedCount,
                'total_students' => $club->students->count(),
                'errors_count' => count($errors),
                'generated_by' => auth()->id() ?? 'system'
            ]);
            
            // If there were errors, log them but don't fail the entire operation
            if (!empty($errors)) {
                Log::warning('Some reports failed to generate', [
                    'club_id' => $club_id,
                    'errors' => $errors
                ]);
            }
            
		} catch (\Exception $e) {
            // Rollback transaction on failure
            DB::rollBack();
            
            Log::error('Failed to generate reports for club', [
                'club_id' => $club_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
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

	/**
	 * Generate access codes for all reports that don't have them
	 */
	public function generate_access_codes_for_existing_reports(): int
	{
		$reportsWithoutCodes = Report::doesntHave('access_code')->get();
		$count = 0;

		foreach ($reportsWithoutCodes as $report) {
			$this->accessCodeService->create_access_code_for_report($report->id);
			$count++;
		}

		return $count;
	}

	/**
	 * Calculate skill score based on attendance and overall performance
	 * 
	 * @param float $attendance_percent Attendance percentage
	 * @param float $overall_score Overall assessment score
	 * @param int $skill_variation Variation factor for different skills (1-4)
	 * @return int Skill score between 1-10
	 */
	private function calculateSkillScore(float $attendance_percent, float $overall_score, int $skill_variation): int
	{
		// Base score from overall performance
		$base_score = ($overall_score / 100) * 10;
		
		// Attendance bonus
		$attendance_bonus = ($attendance_percent / 100) * 2;
		
		// Add some variation based on skill type
		$variation = ($skill_variation % 3) * 0.5;
		
		// Calculate final score
		$final_score = $base_score + $attendance_bonus + $variation;
		
		// Ensure score is between 1-10
		return max(1, min(10, round($final_score)));
	}
}


