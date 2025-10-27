<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionProof;
use Illuminate\Support\Str;

class SimpleChunkedUploadController extends Controller
{
    /**
     * Handle simple chunked file upload
     */
    public function upload(Request $request)
    {
        try {
            \Log::info('Simple chunked upload request:', [
                'user_id' => Auth::id(),
                'user_role' => Auth::user() ? Auth::user()->user_role : 'not_authenticated',
                'has_file' => $request->hasFile('file'),
                'chunk' => $request->input('chunk'),
                'chunks' => $request->input('chunks'),
                'name' => $request->input('name'),
                'session_id' => $request->input('session_id'),
                'headers' => $request->headers->all(),
                'ip' => $request->ip()
            ]);

            // Check authentication first
            if (!Auth::check()) {
                \Log::error('Chunked upload failed: User not authenticated');
                return response()->json(['error' => 'Authentication required'], 401);
            }

            // Validate the request
            try {
                $request->validate([
                    'session_id' => 'required|exists:sessions_schedule,id',
                    'description' => 'nullable|string|max:1000',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Chunked upload validation failed:', [
                    'errors' => $e->errors(),
                    'input' => $request->all()
                ]);
                return response()->json(['error' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))], 422);
            }

            $chunk = $request->input('chunk', 0);
            $chunks = $request->input('chunks', 1);
            $fileName = $request->input('name');
            $sessionId = $request->input('session_id');
            $description = $request->input('description', '');

            // Create a unique identifier for this upload
            $uploadId = md5($fileName . $sessionId . time());
            $tempDir = storage_path('app/temp/chunks/' . $uploadId);
            
            // Create temp directory if it doesn't exist
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Save the chunk
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if (!$file->isValid()) {
                    \Log::error('Chunked upload failed: Invalid file chunk', [
                        'error' => $file->getErrorMessage(),
                        'chunk' => $chunk
                    ]);
                    return response()->json(['error' => 'Invalid file chunk: ' . $file->getErrorMessage()], 400);
                }
                
                $chunkPath = $tempDir . '/chunk_' . $chunk;
                if (!$file->move($tempDir, 'chunk_' . $chunk)) {
                    \Log::error('Chunked upload failed: Could not save chunk', [
                        'chunk' => $chunk,
                        'temp_dir' => $tempDir
                    ]);
                    return response()->json(['error' => 'Could not save chunk'], 500);
                }
                
                \Log::info('Chunk saved successfully:', [
                    'chunk' => $chunk,
                    'chunk_path' => $chunkPath,
                    'file_size' => $file->getSize()
                ]);
            } else {
                \Log::error('Chunked upload failed: No file in request', [
                    'chunk' => $chunk,
                    'has_file' => $request->hasFile('file')
                ]);
                return response()->json(['error' => 'No file provided'], 400);
            }

            // If this is the last chunk, combine all chunks
            if ($chunk == $chunks - 1) {
                return $this->combineChunks($tempDir, $fileName, $sessionId, $description, $chunks);
            }

            // Return progress
            $progress = (($chunk + 1) / $chunks) * 100;
            
            return response()->json([
                'done' => $progress,
                'status' => true,
                'message' => "Chunk " . ($chunk + 1) . " of {$chunks} uploaded"
            ]);

        } catch (\Exception $e) {
            \Log::error('Simple chunked upload error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Combine all chunks into final file
     */
    private function combineChunks($tempDir, $fileName, $sessionId, $description, $totalChunks)
    {
        try {
            $finalPath = storage_path('app/public/proofs/' . time() . '_' . $fileName);
            
            // Ensure the proofs directory exists
            $proofsDir = storage_path('app/public/proofs');
            if (!file_exists($proofsDir)) {
                mkdir($proofsDir, 0755, true);
            }

            // Open the final file for writing
            $finalFile = fopen($finalPath, 'wb');
            
            // Combine all chunks
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = $tempDir . '/chunk_' . $i;
                if (file_exists($chunkPath)) {
                    $chunkData = file_get_contents($chunkPath);
                    fwrite($finalFile, $chunkData);
                    unlink($chunkPath); // Delete chunk after writing
                }
            }
            
            fclose($finalFile);
            
            // Clean up temp directory
            rmdir($tempDir);
            
            // Get file info
            $fileSize = filesize($finalPath);
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
            $proofType = in_array($extension, $videoExtensions) ? 'video' : 'photo';
            
            // Determine MIME type
            $mimeType = mime_content_type($finalPath);
            
            // Create the proof record
            $proof = SessionProof::create([
                'session_id' => $sessionId,
                'proof_url' => 'proofs/' . basename($finalPath),
                'mime_type' => $mimeType,
                'proof_type' => $proofType,
                'file_size' => $fileSize,
                'uploaded_by' => Auth::id(),
                'processing_status' => 'completed',
                'status' => 'pending',
            ]);

            \Log::info('Chunked upload completed successfully:', [
                'proof_id' => $proof->id,
                'file_path' => $proof->proof_url,
                'file_size' => $fileSize
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'proof_id' => $proof->id,
                'file_path' => $proof->proof_url
            ]);

        } catch (\Exception $e) {
            \Log::error('Error combining chunks:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to combine chunks: ' . $e->getMessage()
            ], 500);
        }
    }
}

