<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Models\Club;
use App\Models\AssessmentScore;

class FixReportScores extends Command
{
    protected $signature = 'reports:fix-scores';
    protected $description = 'Fix report overall scores by calculating them from assessment data';

    public function handle()
    {
        $this->info('Fixing report overall scores...');
        
        $reports = Report::with(['student', 'club.assessments.scores'])->get();
        $fixed = 0;
        
        foreach ($reports as $report) {
            $overallScore = $this->calculateOverallScore($report);
            
            if ($overallScore > 0) {
                $report->update(['report_overall_score' => $overallScore]);
                $fixed++;
                $this->line("Fixed report {$report->id}: {$overallScore}%");
            }
        }
        
        $this->info("Fixed {$fixed} reports with overall scores.");
    }
    
    private function calculateOverallScore(Report $report): float
    {
        $club = $report->club;
        $studentId = $report->student_id;
        
        if (!$club || !$club->assessments) {
            return 0.0;
        }
        
        $total = 0.0;
        $count = 0;
        
        foreach ($club->assessments as $assessment) {
            $score = $assessment->scores->where('student_id', $studentId)->first();
            if ($score && $score->score_max_value > 0) {
                $percentage = ($score->score_value / $score->score_max_value) * 100;
                $total += $percentage;
                $count++;
            }
        }
        
        return $count > 0 ? round($total / $count, 2) : 0.0;
    }
}
