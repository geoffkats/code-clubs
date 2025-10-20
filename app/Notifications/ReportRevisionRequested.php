<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report;

class ReportRevisionRequested extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Report $report,
        public string $requestedBy = 'facilitator'
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $requesterRole = $this->requestedBy === 'facilitator' ? 'Facilitator' : 'Admin';
        $reportName = $this->report->report_name;
        $studentName = $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name;
        $clubName = $this->report->club->club_name;
        $feedback = $this->requestedBy === 'facilitator' ? $this->report->facilitator_feedback : $this->report->admin_feedback;
        
        return (new MailMessage)
            ->subject("Report Revision Requested by {$requesterRole}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A revision has been requested for one of your reports.")
            ->line("**Report Details:**")
            ->line("- Report: {$reportName}")
            ->line("- Student: {$studentName}")
            ->line("- Club: {$clubName}")
            ->line("- Overall Score: {$this->report->report_overall_score}%")
            ->line("- Requested by: {$requesterRole}")
            ->line("**Feedback:**")
            ->line($feedback)
            ->action('Edit Report', url('/teacher/reports'))
            ->line('Please review the feedback and make the necessary revisions.')
            ->salutation('Best regards, Code Club System');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'report_revision_requested',
            'report_id' => $this->report->id,
            'report_name' => $this->report->report_name,
            'student_name' => $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name,
            'club_name' => $this->report->club->club_name,
            'requested_by' => $this->requestedBy,
            'feedback' => $this->requestedBy === 'facilitator' ? $this->report->facilitator_feedback : $this->report->admin_feedback,
            'score' => $this->report->report_overall_score,
            'created_at' => now(),
        ];
    }
}