<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
	use HasFactory;

	protected $fillable = [
		'school_id',
		'student_first_name',
		'student_last_name',
		'student_grade_level',
		'student_parent_name',
		'student_parent_email',
	];

	public function school(): BelongsTo
	{
		return $this->belongsTo(School::class);
	}

	public function clubs(): BelongsToMany
	{
		return $this->belongsToMany(Club::class, 'club_enrollments');
	}

	public function assessment_scores(): HasMany
	{
		return $this->hasMany(AssessmentScore::class);
	}
}


