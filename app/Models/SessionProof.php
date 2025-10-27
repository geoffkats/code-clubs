<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'proof_url',
        'mime_type',
        'proof_type',
        'file_size',
        'uploaded_by',
        'processing_status',
        'status',
        'description',
        'admin_comments',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'is_archived',
        'archived_at',
        'archived_by'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    /**
     * Get the session this proof belongs to
     */
    public function session()
    {
        return $this->belongsTo(SessionSchedule::class, 'session_id');
    }

    /**
     * Get the user who uploaded this proof
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the admin who reviewed this proof
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the admin who archived this proof
     */
    public function archiver()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Get the preview URL for videos (thumbnail)
     */
    public function getPreviewUrlAttribute()
    {
        if ($this->proof_type === 'video') {
            // For now, return the same URL - can be enhanced later with thumbnail generation
            return $this->proof_url;
        }
        
        return $this->proof_url;
    }

    /**
     * Get the file URL for downloading
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->proof_url);
    }

    /**
     * Scope proofs by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('proof_type', $type);
    }

    /**
     * Scope proofs by processing status
     */
    public function scopeByProcessingStatus($query, $status)
    {
        return $query->where('processing_status', $status);
    }

    /**
     * Scope proofs uploaded by a specific user
     */
    public function scopeByUploader($query, $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    /**
     * Scope proofs by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pending proofs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope approved proofs
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope rejected proofs
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if proof is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if proof is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if proof is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'under_review' => 'blue',
            default => 'gray'
        };
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file is an image
     */
    public function isImage()
    {
        return $this->proof_type === 'photo';
    }

    /**
     * Check if file is a video
     */
    public function isVideo()
    {
        return $this->proof_type === 'video';
    }

    /**
     * Check if file is a document
     */
    public function isDocument()
    {
        return $this->proof_type === 'document';
    }

    /**
     * Scope for archived proofs
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Scope for non-archived proofs
     */
    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Check if proof is archived
     */
    public function isArchived()
    {
        return $this->is_archived;
    }

    /**
     * Archive this proof
     */
    public function archive($archivedBy = null)
    {
        $this->update([
            'is_archived' => true,
            'archived_at' => now(),
            'archived_by' => $archivedBy ?? auth()->id(),
        ]);
    }

    /**
     * Unarchive this proof
     */
    public function unarchive()
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null,
            'archived_by' => null,
        ]);
    }
}
