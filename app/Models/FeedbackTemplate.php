<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'rating_criteria',
        'feedback_questions',
        'is_active',
        'template_type',
        'club_id',
        'created_by',
    ];

    protected $casts = [
        'rating_criteria' => 'array',
        'feedback_questions' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the club this template belongs to
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get the user who created this template
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for standard templates
     */
    public function scopeStandard($query)
    {
        return $query->where('template_type', 'standard');
    }

    /**
     * Scope for club-specific templates
     */
    public function scopeForClub($query, $clubId)
    {
        return $query->where(function($q) use ($clubId) {
            $q->where('club_id', $clubId)
              ->orWhere('template_type', 'standard');
        });
    }

    /**
     * Get default template for new feedback
     */
    public static function getDefaultTemplate()
    {
        return static::active()
            ->standard()
            ->first() ?? static::createDefaultTemplate();
    }

    /**
     * Create default template if none exists
     */
    public static function createDefaultTemplate()
    {
        return static::create([
            'name' => 'Standard Feedback Template',
            'description' => 'Default template for session feedback',
            'template_type' => 'standard',
            'is_active' => true,
            'rating_criteria' => [
                'content_delivery' => 'How well was the content presented and explained?',
                'student_engagement' => 'How well did the teacher engage and interact with students?',
                'session_management' => 'How well was the session organized and managed?',
                'preparation' => 'How well was the teacher prepared for the session?',
            ],
            'feedback_questions' => [
                'What went well in this session?',
                'What areas need improvement?',
                'How can the teacher enhance student engagement?',
                'Any specific recommendations for the teacher?',
            ],
            'created_by' => 1, // Assuming admin user with ID 1
        ]);
    }
}
