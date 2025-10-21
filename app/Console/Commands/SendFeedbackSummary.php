<?php

namespace App\Console\Commands;

use App\Models\SessionFeedback;
use App\Models\User;
use App\Notifications\FeedbackSummaryNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendFeedbackSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feedback:send-summary {--period=weekly : Summary period (daily, weekly, monthly)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send feedback summary notifications to administrators';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->option('period');
        
        // Calculate date range based on period
        $dateRange = $this->getDateRange($period);
        
        // Get feedback data for the period
        $feedbacks = SessionFeedback::whereBetween('created_at', $dateRange)->get();
        
        if ($feedbacks->isEmpty()) {
            $this->info("No feedback found for {$period} period.");
            return;
        }

        // Calculate summary statistics
        $summaryData = $this->calculateSummaryData($feedbacks);
        
        // Get all admin users
        $admins = User::where('user_role', 'admin')->get();
        
        if ($admins->isEmpty()) {
            $this->warn('No admin users found to send notifications to.');
            return;
        }

        // Send notifications to all admins
        $this->info("Sending {$period} feedback summary to {$admins->count()} admin(s)...");
        
        foreach ($admins as $admin) {
            $admin->notify(new FeedbackSummaryNotification($summaryData));
            $this->line("✓ Sent to: {$admin->name} ({$admin->email})");
        }

        $this->info("✅ Successfully sent {$period} feedback summary notifications!");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Feedbacks', $summaryData['total_feedbacks']],
                ['Average Rating', number_format($summaryData['average_rating'], 1) . '/5'],
                ['Critical Feedbacks', $summaryData['critical_feedbacks']],
                ['Teachers Needing Attention', $summaryData['teachers_needing_attention']],
            ]
        );
    }

    /**
     * Get date range based on period
     */
    private function getDateRange(string $period): array
    {
        $end = Carbon::now();
        
        switch ($period) {
            case 'daily':
                $start = Carbon::now()->subDay();
                break;
            case 'weekly':
                $start = Carbon::now()->subWeek();
                break;
            case 'monthly':
                $start = Carbon::now()->subMonth();
                break;
            default:
                $start = Carbon::now()->subWeek();
        }
        
        return [$start, $end];
    }

    /**
     * Calculate summary data from feedbacks
     */
    private function calculateSummaryData($feedbacks): array
    {
        $totalFeedbacks = $feedbacks->count();
        $averageRating = $feedbacks->avg('overall_rating') ?: 0;
        
        // Count critical feedbacks
        $criticalFeedbacks = $feedbacks->where('feedback_type', 'critical')->count();
        
        // Count teachers with low ratings (below 3.0)
        $teachersWithLowRatings = $feedbacks
            ->groupBy('teacher_id')
            ->filter(function ($teacherFeedbacks) {
                return $teacherFeedbacks->avg('overall_rating') < 3.0;
            })
            ->count();

        return [
            'total_feedbacks' => $totalFeedbacks,
            'average_rating' => $averageRating,
            'critical_feedbacks' => $criticalFeedbacks,
            'teachers_needing_attention' => $teachersWithLowRatings,
            'period' => $this->option('period'),
            'date_range' => $this->getDateRange($this->option('period')),
        ];
    }
}
