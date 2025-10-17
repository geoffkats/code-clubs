<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionSchedule extends Model
{
	use HasFactory;

	protected $table = 'sessions_schedule';

	protected $fillable = [
		'club_id',
		'session_week_number',
		'session_date',
	];

	public function club(): BelongsTo
	{
		return $this->belongsTo(Club::class);
	}

	public function attendance_records(): HasMany
	{
		return $this->hasMany(AttendanceRecord::class, 'session_id');
	}
}


