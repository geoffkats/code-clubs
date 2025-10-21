<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FeedbackSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $summaryData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $summaryData)
    {
        $this->summaryData = $summaryData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $totalFeedbacks = $this->summaryData['total_feedbacks'];
        $averageRating = $this->summaryData['average_rating'];
        $criticalCount = $this->summaryData['critical_feedbacks'] ?? 0;
        $teachersNeedingAttention = $this->summaryData['teachers_needing_attention'] ?? 0;

        $message = (new MailMessage)
            ->subject('Weekly Feedback Summary Report')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Here\'s your weekly feedback summary for the system:')
            ->line('**📊 Summary Statistics:**')
            ->line('• Total feedback received: **' . $totalFeedbacks . '**')
            ->line('• Average rating: **' . number_format($averageRating, 1) . '/5**')
            ->line('• Critical feedback items: **' . $criticalCount . '**')
            ->line('• Teachers needing attention: **' . $teachersNeedingAttention . '**');

        if ($criticalCount > 0) {
            $message->line('⚠️ **Action Required:** There are critical feedback items that need immediate attention.');
        }

        if ($teachersNeedingAttention > 0) {
            $message->line('📈 **Teacher Development:** Some teachers may benefit from additional support or training.');
        }

        $message->action('View Analytics Dashboard', route('admin.feedback.analytics'))
            ->line('Thank you for maintaining teaching quality standards!');

        return $message;
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $totalFeedbacks = $this->summaryData['total_feedbacks'];
        $averageRating = $this->summaryData['average_rating'];

        return new DatabaseMessage([
            'title' => 'Weekly Feedback Summary',
            'message' => "Received {$totalFeedbacks} feedback items with average rating of " . number_format($averageRating, 1) . "/5",
            'icon' => 'chart-bar',
            'type' => 'summary',
            'total_feedbacks' => $totalFeedbacks,
            'average_rating' => $averageRating,
            'critical_count' => $this->summaryData['critical_feedbacks'] ?? 0,
            'teachers_attention' => $this->summaryData['teachers_needing_attention'] ?? 0,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'total_feedbacks' => $this->summaryData['total_feedbacks'],
            'average_rating' => $this->summaryData['average_rating'],
            'critical_feedbacks' => $this->summaryData['critical_feedbacks'] ?? 0,
            'teachers_needing_attention' => $this->summaryData['teachers_needing_attention'] ?? 0,
            'summary_type' => 'weekly',
        ];
    }
}
