<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LessonNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'title',
        'description',
        'attachment_url',
        'mime_type',
        'attachment_type',
        'visibility',
        'created_by',
        'tags',
        'link_title',
        'video_url',
        'external_url',
        'code_url',
        'code_branch',
        'audio_url'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the club this resource belongs to
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get the user who created this resource
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who created this resource (alias for compatibility)
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope resources visible to a specific user
     */
    public function scopeVisibleTo($query, User $user)
    {
        // If admin, facilitator, or teacher: show all
        if (in_array($user->user_role, ['admin', 'facilitator', 'teacher'])) {
            return $query;
        }
        
        // If student: only show resources with visibility = 'all'
        if ($user->user_role === 'student') {
            return $query->where('visibility', 'all');
        }
        
        return $query;
    }

    /**
     * Get the file URL for downloading
     */
    public function getFileUrlAttribute()
    {
        if ($this->visibility === 'teachers_only') {
            // Generate signed URL for private files
            return Storage::disk('resources')->temporaryUrl($this->attachment_url, now()->addHour());
        }
        
        return Storage::disk('resources')->url($this->attachment_url);
    }

    /**
     * Check if this resource is visible to a user
     */
    public function isVisibleTo(User $user): bool
    {
        if (in_array($user->user_role, ['admin', 'facilitator', 'teacher'])) {
            return true;
        }
        
        if ($user->user_role === 'student') {
            return $this->visibility === 'all';
        }
        
        return false;
    }
}
