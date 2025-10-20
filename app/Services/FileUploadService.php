<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a proof file for a session
     */
    public function uploadProof(UploadedFile $file, $sessionId, $userId)
    {
        $mimeType = $file->getMimeType();
        $proofType = str_starts_with($mimeType, 'video') ? 'video' : 'photo';
        
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('session_proofs', $filename, 'proofs');
        
        $proof = \App\Models\SessionProof::create([
            'session_id' => $sessionId,
            'proof_url' => $path,
            'mime_type' => $mimeType,
            'proof_type' => $proofType,
            'file_size' => $file->getSize(),
            'uploaded_by' => $userId,
            'processing_status' => $proofType === 'video' ? 'pending' : 'completed',
        ]);
        
        return [
            'proof_id' => $proof->id,
            'path' => $path,
            'proof_type' => $proofType,
            'mime_type' => $mimeType,
        ];
    }

    /**
     * Upload a resource file
     */
    public function uploadResource(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('resources', $filename, 'resources');
        
        // Determine file type based on MIME type
        $type = 'other';
        if (str_starts_with($mimeType, 'video')) {
            $type = 'video';
        } elseif (str_starts_with($mimeType, 'image')) {
            $type = 'image';
        } elseif (str_contains($mimeType, 'pdf')) {
            $type = 'pdf';
        } elseif (str_contains($mimeType, 'document') || str_contains($mimeType, 'word')) {
            $type = 'document';
        }
        
        return [
            'path' => $path,
            'mime_type' => $mimeType,
            'type' => $type,
        ];
    }

    /**
     * Delete a proof file
     */
    public function deleteProof($proofPath)
    {
        return Storage::disk('proofs')->delete($proofPath);
    }

    /**
     * Delete a resource file
     */
    public function deleteResource($resourcePath)
    {
        return Storage::disk('resources')->delete($resourcePath);
    }

    /**
     * Get file size in human readable format
     */
    public function getHumanReadableSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Validate file type for proofs
     */
    public function validateProofFile(UploadedFile $file)
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'video/mp4', 'video/mov'];
        $maxSize = 50 * 1024 * 1024; // 50MB
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['valid' => false, 'message' => 'Invalid file type. Only JPEG, PNG, MP4, and MOV files are allowed.'];
        }
        
        if ($file->getSize() > $maxSize) {
            return ['valid' => false, 'message' => 'File size too large. Maximum size is 50MB.'];
        }
        
        return ['valid' => true];
    }

    /**
     * Validate file type for resources
     */
    public function validateResourceFile(UploadedFile $file)
    {
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'video/mp4',
            'image/jpeg',
            'image/png',
            'image/jpg'
        ];
        $maxSize = 100 * 1024 * 1024; // 100MB
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['valid' => false, 'message' => 'Invalid file type. Only PDF, DOC, DOCX, MP4, JPEG, and PNG files are allowed.'];
        }
        
        if ($file->getSize() > $maxSize) {
            return ['valid' => false, 'message' => 'File size too large. Maximum size is 100MB.'];
        }
        
        return ['valid' => true];
    }
}