<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAccessCode extends Model
{
	use HasFactory;

	protected $fillable = [
		'report_id',
		'access_code_hash',
		'access_code_plain_preview',
		'access_code_expires_at',
	];

	protected $casts = [
		'access_code_expires_at' => 'datetime',
	];

	public function report(): BelongsTo
	{
		return $this->belongsTo(Report::class);
	}
}


