<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file and create an attachment record.
     */
    public function uploadFile(UploadedFile $file, $attachable, string $description = null): Attachment
    {
        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        
        // Store file
        $path = $file->storeAs('attachments', $filename, 'public');
        
        // Create attachment record
        return Attachment::create([
            'attachable_type' => get_class($attachable),
            'attachable_id' => $attachable->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $extension,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'description' => $description,
        ]);
    }

    /**
     * Upload multiple files.
     */
    public function uploadMultipleFiles(array $files, $attachable, string $description = null): array
    {
        $attachments = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $attachments[] = $this->uploadFile($file, $attachable, $description);
            }
        }
        
        return $attachments;
    }

    /**
     * Delete a file and its attachment record.
     */
    public function deleteFile(Attachment $attachment): bool
    {
        // Delete file from storage
        Storage::disk('public')->delete($attachment->file_path);
        
        // Delete attachment record
        return $attachment->delete();
    }

    /**
     * Get file URL.
     */
    public function getFileUrl(Attachment $attachment): string
    {
        return Storage::disk('public')->url($attachment->file_path);
    }

    /**
     * Validate file upload.
     */
    public function validateFile(UploadedFile $file, array $allowedTypes = [], int $maxSize = 10240): array
    {
        $errors = [];
        
        // Check file size (default 10MB)
        if ($file->getSize() > $maxSize * 1024) {
            $errors[] = "File size must be less than {$maxSize}MB.";
        }
        
        // Check file type
        if (!empty($allowedTypes) && !in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = "File type not allowed. Allowed types: " . implode(', ', $allowedTypes);
        }
        
        return $errors;
    }

    /**
     * Get allowed file types for assessments.
     */
    public function getAllowedAssessmentTypes(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'text/plain',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
    }

    /**
     * Get allowed file types for projects.
     */
    public function getAllowedProjectTypes(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'video/mp4',
            'video/avi',
            'video/mov',
            'application/pdf',
            'text/plain',
            'application/json', // For Scratch projects
        ];
    }
}
