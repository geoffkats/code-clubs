<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'facilitator_id',
        'teacher_id',
        'club_id',
        'content',
        'suggestions',
        'feedback_type',
        'content_delivery_rating',
        'student_engagement_rating',
        'session_management_rating',
        'preparation_rating',
        'overall_rating',
        'status',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'suggestions' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'content_delivery_rating' => 'integer',
        'student_engagement_rating' => 'integer',
        'session_management_rating' => 'integer',
        'preparation_rating' => 'integer',
        'overall_rating' => 'integer',
    ];

    /**
     * Get the session this feedback belongs to
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the facilitator who provided the feedback
     */
    public function facilitator()
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    /**
     * Get the teacher who received the feedback
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the club this feedback is for
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Calculate average rating across all aspects
     */
    public function getAverageRatingAttribute()
    {
        $ratings = collect([
            $this->content_delivery_rating,
            $this->student_engagement_rating,
            $this->session_management_rating,
            $this->preparation_rating,
        ])->filter();

        return $ratings->count() > 0 ? round($ratings->avg(), 1) : null;
    }

    /**
     * Get feedback type color for UI
     */
    public function getFeedbackTypeColorAttribute()
    {
        return match($this->feedback_type) {
            'positive' => 'green',
            'constructive' => 'blue',
            'critical' => 'red',
            'mixed' => 'yellow',
            default => 'gray'
        };
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'reviewed' => 'green',
            'actioned' => 'purple',
            default => 'gray'
        };
    }

    /**
     * Scope for feedback by facilitator
     */
    public function scopeByFacilitator($query, $facilitatorId)
    {
        return $query->where('facilitator_id', $facilitatorId);
    }

    /**
     * Scope for feedback for teacher
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope for feedback by club
     */
    public function scopeByClub($query, $clubId)
    {
        return $query->where('club_id', $clubId);
    }

    /**
     * Scope for feedback by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for submitted feedback only
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Check if feedback is submitted
     */
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if feedback is reviewed
     */
    public function isReviewed()
    {
        return in_array($this->status, ['reviewed', 'actioned']);
    }
}