<?php

namespace App\Notifications;

use App\Models\SessionFeedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class SessionFeedbackReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sessionFeedback;

    /**
     * Create a new notification instance.
     */
    public function __construct(SessionFeedback $sessionFeedback)
    {
        $this->sessionFeedback = $sessionFeedback;
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
        $session = $this->sessionFeedback->session;
        $club = $this->sessionFeedback->club;
        $facilitator = $this->sessionFeedback->facilitator;

        return (new MailMessage)
            ->subject('New Session Feedback Received')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received new feedback for your session: **' . $session->title . '**')
            ->line('**Club:** ' . $club->club_name)
            ->line('**Date:** ' . $session->session_date->format('M d, Y'))
            ->line('**Facilitator:** ' . $facilitator->name)
            ->line('**Overall Rating:** ' . $this->sessionFeedback->overall_rating . '/5 stars')
            ->line('**Feedback Type:** ' . ucfirst($this->sessionFeedback->feedback_type))
            ->line('**Feedback Preview:**')
            ->line(substr($this->sessionFeedback->content, 0, 200) . '...')
            ->action('View Full Feedback', route('teacher.feedback.show', $this->sessionFeedback))
            ->line('Thank you for your dedication to teaching!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $session = $this->sessionFeedback->session;
        $club = $this->sessionFeedback->club;
        $facilitator = $this->sessionFeedback->facilitator;

        return new DatabaseMessage([
            'title' => 'New Session Feedback Received',
            'message' => "You received feedback for session '{$session->title}' from {$facilitator->name}",
            'icon' => 'star',
            'type' => 'feedback',
            'feedback_id' => $this->sessionFeedback->id,
            'session_id' => $session->id,
            'club_id' => $club->id,
            'facilitator_id' => $facilitator->id,
            'rating' => $this->sessionFeedback->overall_rating,
            'feedback_type' => $this->sessionFeedback->feedback_type,
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
            'session_feedback_id' => $this->sessionFeedback->id,
            'session_title' => $this->sessionFeedback->session->title,
            'club_name' => $this->sessionFeedback->club->club_name,
            'facilitator_name' => $this->sessionFeedback->facilitator->name,
            'rating' => $this->sessionFeedback->overall_rating,
            'feedback_type' => $this->sessionFeedback->feedback_type,
        ];
    }
}
