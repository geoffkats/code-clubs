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
		'student_initials',
		'problem_solving_score',
		'creativity_score',
		'collaboration_score',
		'persistence_score',
		'scratch_project_ids',
		'favorite_concept',
		'challenges_overcome',
		'special_achievements',
		'areas_for_growth',
		'next_steps',
		'parent_feedback',
		'teacher_id',
		'facilitator_id',
		'admin_id',
		'facilitator_feedback',
		'admin_feedback',
		'facilitator_approved_at',
		'admin_approved_at',
		'status',
	];

	protected $casts = [
		'facilitator_approved_at' => 'datetime',
		'admin_approved_at' => 'datetime',
		'report_generated_at' => 'datetime',
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

	/**
	 * Get the teacher who created this report
	 */
	public function teacher(): BelongsTo
	{
		return $this->belongsTo(User::class, 'teacher_id');
	}

	/**
	 * Get the facilitator who approved this report
	 */
	public function facilitator(): BelongsTo
	{
		return $this->belongsTo(User::class, 'facilitator_id');
	}

	/**
	 * Get the admin who approved this report
	 */
	public function admin(): BelongsTo
	{
		return $this->belongsTo(User::class, 'admin_id');
	}

	/**
	 * Scope reports pending facilitator approval
	 */
	public function scopePendingFacilitatorApproval($query)
	{
		return $query->where('status', 'pending');
	}

	/**
	 * Scope reports pending admin approval
	 */
	public function scopePendingAdminApproval($query)
	{
		return $query->where('status', 'facilitator_approved');
	}

	/**
	 * Scope reports that need revision
	 */
	public function scopeNeedsRevision($query)
	{
		return $query->where('status', 'revision_requested');
	}

	/**
	 * Get the approval timeline as an array
	 */
	public function getApprovalTimelineAttribute()
	{
		$timeline = [];
		
		if ($this->facilitator_approved_at) {
			$timeline[] = [
				'action' => 'facilitator_approved',
				'user' => $this->facilitator,
				'timestamp' => $this->facilitator_approved_at,
				'feedback' => $this->facilitator_feedback,
			];
		}
		
		if ($this->admin_approved_at) {
			$timeline[] = [
				'action' => 'admin_approved',
				'user' => $this->admin,
				'timestamp' => $this->admin_approved_at,
				'feedback' => $this->admin_feedback,
			];
		}
		
		return $timeline;
	}
}


