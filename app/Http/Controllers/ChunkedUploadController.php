<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Models\SessionProof;
use App\Models\SessionSchedule;

class ChunkedUploadController extends Controller
{
    /**
     * Handle chunked file upload
     */
    public function upload(Request $request)
    {
        try {
            // Log the request for debugging
            \Log::info('Chunked upload request:', [
                'has_file' => $request->hasFile('file'),
                'chunk' => $request->input('chunk'),
                'chunks' => $request->input('chunks'),
                'name' => $request->input('name'),
                'session_id' => $request->input('session_id')
            ]);

            // Validate the request
            $request->validate([
                'session_id' => 'required|exists:sessions_schedule,id',
                'description' => 'nullable|string|max:1000',
            ]);

            // Create the file receiver
            $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
            
            // Check if the upload is successful
            if (!$receiver->isUploaded()) {
                \Log::error('Chunked upload failed - not uploaded');
                return response()->json(['error' => 'Upload failed - file not uploaded'], 400);
            }

            // Receive the file
            $save = $receiver->receive();
            
            // Check if the upload is finished
            if ($save->isFinished()) {
                \Log::info('Chunked upload finished, saving file');
                return $this->saveFile($save->getFile(), $request);
            }

            // We are in chunk mode, send the current progress
            $handler = $save->handler();
            $progress = $handler->getPercentageDone();
            
            \Log::info('Chunked upload progress:', ['progress' => $progress]);
            
            return response()->json([
                "done" => $progress,
                "status" => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Chunked upload error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save the uploaded file
     */
    private function saveFile($file, Request $request)
    {
        try {
            // Generate unique filename
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store the file
            $filePath = $file->storeAs('proofs', $fileName, 'public');
            
            // Determine file type
            $extension = strtolower($file->getClientOriginalExtension());
            $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
            $proofType = in_array($extension, $videoExtensions) ? 'video' : 'photo';
            
            // Create the proof record
            $proof = SessionProof::create([
                'session_id' => $request->session_id,
                'proof_url' => $filePath,
                'mime_type' => $file->getMimeType(),
                'proof_type' => $proofType,
                'file_size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
                'processing_status' => 'completed',
                'status' => 'pending',
            ]);

            // Clean up temporary files
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'proof_id' => $proof->id,
                'file_path' => $filePath
            ]);

        } catch (\Exception $e) {
            \Log::error('Chunked upload error:', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            
            return response()->json([
                'error' => 'File upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upload progress
     */
    public function progress(Request $request)
    {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        
        if (!$receiver->isUploaded()) {
            return response()->json(['error' => 'No upload in progress'], 400);
        }

        $save = $receiver->receive();
        $handler = $save->handler();
        
        return response()->json([
            'progress' => $handler->getPercentageDone(),
            'status' => 'uploading'
        ]);
    }
}
