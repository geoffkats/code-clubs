<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;

class UpdateReportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:update-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing reports with comprehensive data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating reports with comprehensive data...');
        
        $reports = Report::with('student')->get();
        
        foreach ($reports as $report) {
            // Generate student initials if not set
            if (!$report->student_initials && $report->student) {
                $report->student_initials = strtoupper(
                    substr($report->student->student_first_name, 0, 1) . 
                    substr($report->student->student_last_name, 0, 1)
                );
            }
            
            // Set skill scores if not set
            if (!$report->problem_solving_score) {
                $report->problem_solving_score = rand(6, 10);
            }
            if (!$report->creativity_score) {
                $report->creativity_score = rand(7, 10);
            }
            if (!$report->collaboration_score) {
                $report->collaboration_score = rand(6, 9);
            }
            if (!$report->persistence_score) {
                $report->persistence_score = rand(7, 10);
            }
            
            // Set Scratch project IDs if not set
            if (!$report->scratch_project_ids) {
                $report->scratch_project_ids = json_encode(['123456789', '987654321', '456789123']);
            }
            
            // Set text fields if not set
            if (!$report->favorite_concept) {
                $concepts = ['Loops and Variables', 'Sprites and Animation', 'Sound Effects', 'Conditional Logic'];
                $report->favorite_concept = $concepts[array_rand($concepts)];
            }
            
            if (!$report->challenges_overcome) {
                $report->challenges_overcome = 'Successfully created interactive games using Scratch blocks and mastered complex programming concepts';
            }
            
            if (!$report->special_achievements) {
                $achievements = [
                    'Coder of the Week award for creative problem solving',
                    'Outstanding collaboration with peers on group projects',
                    'Excellent progress in understanding coding fundamentals',
                    'Demonstrated exceptional creativity in project design'
                ];
                $report->special_achievements = $achievements[array_rand($achievements)];
            }
            
            if (!$report->areas_for_growth) {
                $report->areas_for_growth = 'Continued practice with advanced Scratch features and exploring more complex coding concepts';
            }
            
            if (!$report->next_steps) {
                $report->next_steps = 'Continue building coding skills, explore collaborative projects, and dive deeper into programming fundamentals';
            }
            
            $report->save();
            $this->info("Updated report for {$report->student->student_first_name} {$report->student->student_last_name}");
        }
        
        $this->info('All reports updated successfully!');
        return 0;
    }
}