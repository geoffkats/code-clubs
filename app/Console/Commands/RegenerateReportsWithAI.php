<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Services\AIReportGeneratorService;

class RegenerateReportsWithAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:regenerate-ai {--report-id= : Specific report ID to regenerate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate reports with AI-powered content based on assessments and attendance';

    /**
     * Execute the console command.
     */
    public function handle(AIReportGeneratorService $aiService)
    {
        $this->info('🤖 Regenerating reports with AI-powered content...');
        
        $reportId = $this->option('report-id');
        
        if ($reportId) {
            // Regenerate specific report
            $report = Report::with(['student', 'club'])->find($reportId);
            if (!$report) {
                $this->error("Report with ID {$reportId} not found.");
                return 1;
            }
            
            $this->regenerateSingleReport($report, $aiService);
        } else {
            // Regenerate all reports
            $reports = Report::with(['student', 'club'])->get();
            
            $this->info("Found {$reports->count()} reports to regenerate...");
            
            $bar = $this->output->createProgressBar($reports->count());
            $bar->start();
            
            foreach ($reports as $report) {
                $this->regenerateSingleReport($report, $aiService);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
        }
        
        $this->info('✅ All reports regenerated successfully with AI content!');
        return 0;
    }
    
    private function regenerateSingleReport(Report $report, AIReportGeneratorService $aiService)
    {
        try {
            // Generate AI content
            $aiContent = $aiService->generateReportContent($report);
            
            // Update report with AI-generated content
            $report->update($aiContent);
            
            $this->line("✅ Updated report for {$report->student->student_first_name} {$report->student->student_last_name}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to regenerate report for {$report->student->student_first_name}: " . $e->getMessage());
        }
    }
}