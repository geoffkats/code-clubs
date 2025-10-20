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
        'processing_status'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the session this proof belongs to
     */
    public function session()
    {
        return $this->belongsTo(ClubSession::class, 'session_id');
    }

    /**
     * Get the user who uploaded this proof
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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
}
