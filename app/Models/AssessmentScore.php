<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentScore extends Model
{
	use HasFactory;

	protected $fillable = [
		'assessment_id',
		'student_id',
		'score_value',
		'score_max_value',
		'submission_text',
		'submission_file_path',
		'submission_file_name',
		'status',
		'admin_feedback',
		'student_answers',
	];

	protected $casts = [
		'student_answers' => 'array',
	];

	public function assessment(): BelongsTo
	{
		return $this->belongsTo(Assessment::class);
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class);
	}
}


