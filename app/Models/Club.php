<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Club extends Model
{
	use HasFactory;

	protected $fillable = [
		'school_id',
		'club_name',
		'club_level',
		'club_duration_weeks',
		'club_start_date',
		'facilitator_id',
	];

	public function school(): BelongsTo
	{
		return $this->belongsTo(School::class);
	}

	public function students(): BelongsToMany
	{
		return $this->belongsToMany(Student::class, 'club_enrollments');
	}

	public function sessions(): HasMany
	{
		return $this->hasMany(SessionSchedule::class, 'club_id');
	}

	public function assessments(): HasMany
	{
		return $this->hasMany(Assessment::class);
	}

	public function attachments(): MorphMany
	{
		return $this->morphMany(Attachment::class, 'attachable');
	}

	/**
	 * Get the facilitator who manages this club
	 */
	public function facilitator(): BelongsTo
	{
		return $this->belongsTo(User::class, 'facilitator_id');
	}

	/**
	 * Get teachers assigned to this club
	 */
	public function teachers(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'club_teacher', 'club_id', 'teacher_id');
	}

	/**
	 * Get lesson notes/resources for this club
	 */
	public function lessonNotes(): HasMany
	{
		return $this->hasMany(LessonNote::class);
	}

	/**
	 * Get sessions for this club (new club_sessions table)
	 */
	public function clubSessions(): HasMany
	{
		return $this->hasMany(ClubSession::class);
	}

	/**
	 * Scope clubs managed by a specific facilitator
	 */
	public function scopeManagedBy($query, $facilitatorId)
	{
		return $query->where('facilitator_id', $facilitatorId);
	}

	/**
	 * Scope clubs for a specific school
	 */
	public function scopeForSchool($query, $schoolId)
	{
		return $query->where('school_id', $schoolId);
	}
}


