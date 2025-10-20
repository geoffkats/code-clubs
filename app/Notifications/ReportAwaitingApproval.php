<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report;

class ReportAwaitingApproval extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Report $report,
        public string $approvalType = 'facilitator'
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $approverRole = $this->approvalType === 'facilitator' ? 'Facilitator' : 'Admin';
        $reportName = $this->report->report_name;
        $studentName = $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name;
        $clubName = $this->report->club->club_name;
        
        return (new MailMessage)
            ->subject("Report Awaiting {$approverRole} Approval")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new report is awaiting your approval.")
            ->line("**Report Details:**")
            ->line("- Report: {$reportName}")
            ->line("- Student: {$studentName}")
            ->line("- Club: {$clubName}")
            ->line("- Teacher: {$this->report->teacher->name}")
            ->line("- Overall Score: {$this->report->report_overall_score}%")
            ->action('Review Report', url('/facilitator/reports'))
            ->line('Please review and approve this report at your earliest convenience.')
            ->salutation('Best regards, Code Club System');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'report_awaiting_approval',
            'report_id' => $this->report->id,
            'report_name' => $this->report->report_name,
            'student_name' => $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name,
            'club_name' => $this->report->club->club_name,
            'teacher_name' => $this->report->teacher->name,
            'approval_type' => $this->approvalType,
            'score' => $this->report->report_overall_score,
            'created_at' => now(),
        ];
    }
}