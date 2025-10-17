<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
	use HasFactory;

	protected $fillable = [
		'club_id',
		'student_id',
		'report_name',
		'report_summary_text',
		'report_overall_score',
		'report_generated_at',
	];

	public function club(): BelongsTo
	{
		return $this->belongsTo(Club::class);
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class);
	}

	public function access_code(): HasOne
	{
		return $this->hasOne(ReportAccessCode::class);
	}
}


