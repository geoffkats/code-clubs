<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
	use HasFactory;

	protected $fillable = [
		'session_id',
		'student_id',
		'attendance_status',
		'attendance_notes',
	];

	public function session(): BelongsTo
	{
		return $this->belongsTo(SessionSchedule::class, 'session_id');
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class);
	}
}


