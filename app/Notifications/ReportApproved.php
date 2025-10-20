<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report;

class ReportApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Report $report,
        public string $approvedBy = 'facilitator'
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $approverRole = $this->approvedBy === 'facilitator' ? 'Facilitator' : 'Admin';
        $reportName = $this->report->report_name;
        $studentName = $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name;
        $clubName = $this->report->club->club_name;
        
        return (new MailMessage)
            ->subject("Report Approved by {$approverRole}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Great news! Your report has been approved.")
            ->line("**Report Details:**")
            ->line("- Report: {$reportName}")
            ->line("- Student: {$studentName}")
            ->line("- Club: {$clubName}")
            ->line("- Overall Score: {$this->report->report_overall_score}%")
            ->line("- Approved by: {$approverRole}")
            ->action('View Reports', url('/teacher/reports'))
            ->line('Thank you for your excellent work!')
            ->salutation('Best regards, Code Club System');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'report_approved',
            'report_id' => $this->report->id,
            'report_name' => $this->report->report_name,
            'student_name' => $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name,
            'club_name' => $this->report->club->club_name,
            'approved_by' => $this->approvedBy,
            'score' => $this->report->report_overall_score,
            'created_at' => now(),
        ];
    }
}