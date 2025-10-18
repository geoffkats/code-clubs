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
}


