<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
	use HasFactory, Notifiable;

	protected $fillable = [
		'school_id',
		'student_first_name',
		'student_last_name',
		'student_grade_level',
		'student_parent_name',
		'student_parent_email',
		'email',
		'password',
		'student_id_number',
		'profile_image',
		'preferences',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
		'preferences' => 'array',
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

	public function reports(): HasMany
	{
		return $this->hasMany(Report::class);
	}

	public function attendance_records(): HasMany
	{
		return $this->hasMany(AttendanceRecord::class);
	}

	// Helper methods
	public function getFullNameAttribute(): string
	{
		return $this->student_first_name . ' ' . $this->student_last_name;
	}

	public function getInitialsAttribute(): string
	{
		return strtoupper(
			substr($this->student_first_name, 0, 1) . 
			substr($this->student_last_name, 0, 1)
		);
	}

	public function getProfileImageUrlAttribute(): string
	{
		return $this->profile_image 
			? asset('storage/' . $this->profile_image)
			: 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
	}

	public function getAverageAssessmentScore(): float
	{
		$scores = $this->assessment_scores;
		if ($scores->isEmpty()) return 0;
		
		$totalPercentage = 0;
		foreach ($scores as $score) {
			$totalPercentage += ($score->score_value / $score->score_max_value) * 100;
		}
		
		return $totalPercentage / $scores->count();
	}

	public function getAttendancePercentage(): float
	{
		$totalSessions = 0;
		$attendedSessions = 0;

		foreach ($this->clubs as $club) {
			foreach ($club->sessions as $session) {
				$totalSessions++;
				$attendance = $session->attendance_records->where('student_id', $this->id)->first();
				if ($attendance && $attendance->attendance_status === 'present') {
					$attendedSessions++;
				}
			}
		}

		return $totalSessions > 0 ? ($attendedSessions / $totalSessions) * 100 : 0;
	}
}


