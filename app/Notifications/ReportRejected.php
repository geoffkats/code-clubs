<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report;

class ReportRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Report $report,
        public string $rejectedBy = 'facilitator'
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $rejectorRole = $this->rejectedBy === 'facilitator' ? 'Facilitator' : 'Admin';
        $reportName = $this->report->report_name;
        $studentName = $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name;
        $clubName = $this->report->club->club_name;
        $feedback = $this->rejectedBy === 'facilitator' ? $this->report->facilitator_feedback : $this->report->admin_feedback;
        
        return (new MailMessage)
            ->subject("Report Rejected by {$rejectorRole}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Unfortunately, one of your reports has been rejected.")
            ->line("**Report Details:**")
            ->line("- Report: {$reportName}")
            ->line("- Student: {$studentName}")
            ->line("- Club: {$clubName}")
            ->line("- Overall Score: {$this->report->report_overall_score}%")
            ->line("- Rejected by: {$rejectorRole}")
            ->line("**Feedback:**")
            ->line($feedback)
            ->action('View Reports', url('/teacher/reports'))
            ->line('Please review the feedback and consider creating a new report.')
            ->salutation('Best regards, Code Club System');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'report_rejected',
            'report_id' => $this->report->id,
            'report_name' => $this->report->report_name,
            'student_name' => $this->report->student->student_first_name . ' ' . $this->report->student->student_last_name,
            'club_name' => $this->report->club->club_name,
            'rejected_by' => $this->rejectedBy,
            'feedback' => $this->rejectedBy === 'facilitator' ? $this->report->facilitator_feedback : $this->report->admin_feedback,
            'score' => $this->report->report_overall_score,
            'created_at' => now(),
        ];
    }
}