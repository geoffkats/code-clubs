<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
	use HasFactory;

	protected $fillable = [
		'club_id',
		'assessment_type',
		'assessment_name',
		'assessment_week_number',
		'total_points',
		'due_date',
		'description',
	];

	public function club(): BelongsTo
	{
		return $this->belongsTo(Club::class);
	}

	public function scores(): HasMany
	{
		return $this->hasMany(AssessmentScore::class);
	}

	public function attachments()
	{
		return $this->morphMany(Attachment::class, 'attachable');
	}
}


