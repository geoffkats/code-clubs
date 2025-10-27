<?php

namespace App\Http\Controllers;

use App\Models\SessionProof;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminProofController extends Controller
{
    /**
     * Helper method to get the correct redirect route based on current route context
     */
    protected function getRedirectRoute(): string
    {
        if (request()->routeIs('facilitator.*')) {
            return 'facilitator.proofs.index';
        } elseif (request()->routeIs('teacher.*')) {
            return 'teacher.proofs.index';
        } else {
            return 'admin.proofs.index';
        }
    }

    /**
     * Display all teacher proofs for admin review
     */
    public function index(Request $request)
    {
        $query = SessionProof::with(['session.club', 'uploader', 'reviewer'])
            ->notArchived()
            ->orderBy('created_at', 'desc');

        // If user is a teacher, only show their own proofs
        if (Auth::user()->user_role === 'teacher') {
            $query->where('uploaded_by', Auth::id());
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('club_id')) {
            $query->whereHas('session', function($q) use ($request) {
                $q->where('club_id', $request->club_id);
            });
        }

        if ($request->filled('teacher_id')) {
            $query->where('uploaded_by', $request->teacher_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $proofs = $query->paginate(20);
        
        // Get filter data
        $clubs = Club::orderBy('club_name')->get();
        $teachers = User::where('user_role', 'teacher')->orderBy('name')->get();
        
        // Get statistics - filtered for teacher role
        if (Auth::user()->user_role === 'teacher') {
            $stats = [
                'total' => SessionProof::where('uploaded_by', Auth::id())->count(),
                'pending' => SessionProof::where('uploaded_by', Auth::id())->pending()->count(),
                'approved' => SessionProof::where('uploaded_by', Auth::id())->approved()->count(),
                'rejected' => SessionProof::where('uploaded_by', Auth::id())->rejected()->count(),
            ];
        } else {
            $stats = [
                'total' => SessionProof::count(),
                'pending' => SessionProof::pending()->count(),
                'approved' => SessionProof::approved()->count(),
                'rejected' => SessionProof::rejected()->count(),
            ];
        }

        // Determine which layout to use
        if (request()->routeIs('facilitator.*')) {
            $layout = 'layouts.facilitator';
        } elseif (request()->routeIs('teacher.*')) {
            $layout = 'layouts.teacher';
        } else {
            $layout = 'layouts.admin';
        }
        
        return view('admin.proofs.index', compact('proofs', 'clubs', 'teachers', 'stats'))->layout($layout);
    }

    /**
     * Show detailed view of a specific proof
     */
    public function show(SessionProof $proof)
    {
        $proof->load(['session.club', 'uploader', 'reviewer']);
        
        // Determine which layout to use
        if (request()->routeIs('facilitator.*')) {
            $layout = 'layouts.facilitator';
        } elseif (request()->routeIs('teacher.*')) {
            $layout = 'layouts.teacher';
        } else {
            $layout = 'layouts.admin';
        }
        
        return view('admin.proofs.show', compact('proof'))->layout($layout);
    }

    /**
     * Approve a proof
     */
    public function approve(Request $request, SessionProof $proof)
    {
        $validated = $request->validate([
            'admin_comments' => 'nullable|string|max:1000',
        ]);

        $proof->update([
            'status' => 'approved',
            'admin_comments' => $validated['admin_comments'] ?? '',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route($this->getRedirectRoute())
            ->with('success', 'Proof approved successfully!');
    }

    /**
     * Reject a proof
     */
    public function reject(Request $request, SessionProof $proof)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'admin_comments' => 'nullable|string|max:1000',
        ]);

        $proof->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'admin_comments' => $validated['admin_comments'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route($this->getRedirectRoute())
            ->with('success', 'Proof rejected successfully!');
    }

    /**
     * Mark proof as under review
     */
    public function markUnderReview(SessionProof $proof)
    {
        $proof->update([
            'status' => 'under_review',
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->route($this->getRedirectRoute())
            ->with('success', 'Proof marked as under review!');
    }

    /**
     * Bulk approve proofs
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'proof_ids' => 'required|array',
            'proof_ids.*' => 'exists:session_proofs,id',
            'admin_comments' => 'nullable|string|max:1000',
        ]);

        SessionProof::whereIn('id', $validated['proof_ids'])
            ->update([
                'status' => 'approved',
                'admin_comments' => $validated['admin_comments'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);

        return redirect()->route($this->getRedirectRoute())
            ->with('success', count($validated['proof_ids']) . ' proofs approved successfully!');
    }

    /**
     * Bulk reject proofs
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'proof_ids' => 'required|array',
            'proof_ids.*' => 'exists:session_proofs,id',
            'rejection_reason' => 'required|string|max:1000',
            'admin_comments' => 'nullable|string|max:1000',
        ]);

        SessionProof::whereIn('id', $validated['proof_ids'])
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'admin_comments' => $validated['admin_comments'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

        return redirect()->route($this->getRedirectRoute())
            ->with('success', count($validated['proof_ids']) . ' proofs rejected successfully!');
    }

    /**
     * Download a proof file
     */
    public function download(SessionProof $proof)
    {
        if (!Storage::disk('public')->exists($proof->proof_url)) {
            abort(404, 'Proof file not found');
        }

        return Storage::disk('public')->download($proof->proof_url);
    }

    /**
     * Delete a proof (only if not approved)
     */
    public function destroy(SessionProof $proof)
    {
        // Only allow deletion of pending or rejected proofs
        if ($proof->status === 'approved') {
            return redirect()->route($this->getRedirectRoute())
                ->with('error', 'Approved proofs cannot be deleted.');
        }

        // Delete the file from storage
        if ($proof->proof_url && Storage::disk('public')->exists($proof->proof_url)) {
            Storage::disk('public')->delete($proof->proof_url);
        }

        $proof->delete();

        return redirect()->route($this->getRedirectRoute())
            ->with('success', 'Proof deleted successfully!');
    }

    /**
     * Archive a proof
     */
    public function archive(SessionProof $proof)
    {
        $proof->archive();

        return redirect()->route('admin.proofs.index')
            ->with('success', 'Proof archived successfully!');
    }

    /**
     * Unarchive a proof
     */
    public function unarchive(SessionProof $proof)
    {
        $proof->unarchive();

        return redirect()->route('admin.proofs.index')
            ->with('success', 'Proof unarchived successfully!');
    }

    /**
     * Bulk archive proofs
     */
    public function bulkArchive(Request $request)
    {
        $validated = $request->validate([
            'proof_ids' => 'required|array',
            'proof_ids.*' => 'exists:session_proofs,id',
        ]);

        SessionProof::whereIn('id', $validated['proof_ids'])
            ->get()
            ->each(function ($proof) {
                $proof->archive();
            });

        return redirect()->route('admin.proofs.index')
            ->with('success', count($validated['proof_ids']) . ' proofs archived successfully!');
    }

    /**
     * Bulk unarchive proofs
     */
    public function bulkUnarchive(Request $request)
    {
        $validated = $request->validate([
            'proof_ids' => 'required|array',
            'proof_ids.*' => 'exists:session_proofs,id',
        ]);

        SessionProof::whereIn('id', $validated['proof_ids'])
            ->get()
            ->each(function ($proof) {
                $proof->unarchive();
            });

        return redirect()->route('admin.proofs.archived')
            ->with('success', count($validated['proof_ids']) . ' proofs unarchived successfully!');
    }

    /**
     * View archived proofs
     */
    public function archived(Request $request)
    {
        $query = SessionProof::with(['session.club', 'uploader', 'reviewer', 'archiver'])
            ->archived()
            ->orderBy('archived_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('club_id')) {
            $query->whereHas('session', function($q) use ($request) {
                $q->where('club_id', $request->club_id);
            });
        }

        if ($request->filled('teacher_id')) {
            $query->where('uploaded_by', $request->teacher_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('archived_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('archived_at', '<=', $request->date_to);
        }

        $proofs = $query->paginate(20);
        
        // Get filter data
        $clubs = Club::orderBy('club_name')->get();
        $teachers = User::where('user_role', 'teacher')->orderBy('name')->get();
        
        // Get statistics
        $stats = [
            'total' => SessionProof::archived()->count(),
            'pending' => SessionProof::archived()->pending()->count(),
            'approved' => SessionProof::archived()->approved()->count(),
            'rejected' => SessionProof::archived()->rejected()->count(),
        ];

        return view('admin.proofs.archived', compact('proofs', 'clubs', 'teachers', 'stats'));
    }

    /**
     * Get proof analytics
     */
    public function analytics()
    {
        $stats = [
            'total_proofs' => SessionProof::notArchived()->count(),
            'pending_proofs' => SessionProof::notArchived()->pending()->count(),
            'approved_proofs' => SessionProof::notArchived()->approved()->count(),
            'rejected_proofs' => SessionProof::notArchived()->rejected()->count(),
            'archived_proofs' => SessionProof::archived()->count(),
            'approval_rate' => SessionProof::notArchived()->count() > 0 ? 
                round((SessionProof::notArchived()->approved()->count() / SessionProof::notArchived()->count()) * 100, 1) : 0,
        ];

        // Get proofs by club
        $proofsByClub = SessionProof::with('session.club')
            ->get()
            ->groupBy('session.club.club_name')
            ->map(function($proofs) {
                return [
                    'total' => $proofs->count(),
                    'pending' => $proofs->where('status', 'pending')->count(),
                    'approved' => $proofs->where('status', 'approved')->count(),
                    'rejected' => $proofs->where('status', 'rejected')->count(),
                ];
            });

        // Get proofs by teacher
        $proofsByTeacher = SessionProof::with('uploader')
            ->get()
            ->groupBy('uploader.name')
            ->map(function($proofs) {
                return [
                    'total' => $proofs->count(),
                    'pending' => $proofs->where('status', 'pending')->count(),
                    'approved' => $proofs->where('status', 'approved')->count(),
                    'rejected' => $proofs->where('status', 'rejected')->count(),
                ];
            });

        // Recent proofs
        $recentProofs = SessionProof::with(['session.club', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.proofs.analytics', compact('stats', 'proofsByClub', 'proofsByTeacher', 'recentProofs'));
    }

    /**
     * Bulk delete proofs
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'proof_ids' => 'required|array',
            'proof_ids.*' => 'exists:session_proofs,id',
        ]);

        $deletedCount = 0;
        foreach ($validated['proof_ids'] as $proofId) {
            $proof = SessionProof::find($proofId);
            if ($proof) {
                // Delete file from storage
                if (Storage::disk('public')->exists($proof->proof_url)) {
                    Storage::disk('public')->delete($proof->proof_url);
                }
                $proof->delete();
                $deletedCount++;
            }
        }

        return redirect()->route('admin.proofs.index')->with('success', $deletedCount . ' proofs deleted successfully!');
    }

    /**
     * Bulk export proofs as ZIP
     */
    public function bulkExport(Request $request)
    {
        $validated = $request->validate([
            'proof_ids' => 'required|array',
            'proof_ids.*' => 'exists:session_proofs,id',
        ]);

        $proofs = SessionProof::whereIn('id', $validated['proof_ids'])->get();
        
        if ($proofs->isEmpty()) {
            return redirect()->route('admin.proofs.index')->with('error', 'No proofs found to export.');
        }

        $zip = new \ZipArchive();
        $zipFileName = 'proofs_export_' . now()->format('Y_m_d_H_i_s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->route('admin.proofs.index')->with('error', 'Could not create ZIP file.');
        }

        $addedFiles = 0;
        foreach ($proofs as $proof) {
            if (Storage::disk('public')->exists($proof->proof_url)) {
                $filePath = Storage::disk('public')->path($proof->proof_url);
                $fileName = $proof->id . '_' . basename($proof->proof_url);
                $zip->addFile($filePath, $fileName);
                $addedFiles++;
            }
        }

        $zip->close();

        if ($addedFiles === 0) {
            return redirect()->route('admin.proofs.index')->with('error', 'No files found to export.');
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Show the form for creating a new proof
     */
    public function create()
    {
        $clubs = Club::orderBy('club_name')->get();
        $sessions = \App\Models\SessionSchedule::with('club')->orderBy('created_at', 'desc')->get();
        
        // Determine which layout to use
        if (request()->routeIs('facilitator.*')) {
            $layout = 'layouts.facilitator';
        } elseif (request()->routeIs('teacher.*')) {
            $layout = 'layouts.teacher';
        } else {
            $layout = 'layouts.admin';
        }
        
        return view('admin.proofs.create', compact('clubs', 'sessions'))->layout($layout);
    }

    /**
     * Store a newly created proof
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|exists:sessions_schedule,id',
                'proof_url' => 'required|file|max:8192', // 8MB limit for videos
                'description' => 'nullable|string|max:1000',
            ]);
            
            // Custom validation for file types and size
            if ($request->hasFile('proof_url')) {
                $file = $request->file('proof_url');
                $extension = strtolower($file->getClientOriginalExtension());
                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'mp4', 'mov', 'avi', 'webm'];
                $allowedMimeTypes = [
                    'image/jpeg', 'image/jpg', 'image/png',
                    'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'video/mp4', 'video/mpeg4', 'application/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'
                ];
                
                // Check file extension
                if (!in_array($extension, $allowedExtensions)) {
                    $errorMessage = 'File type not allowed. Allowed types: Images (JPG, PNG), Videos (MP4, MOV, AVI, WEBM), Documents (PDF, DOC, DOCX)';
                    
                    if (request()->ajax() || request()->wantsJson()) {
                        return response()->json(['error' => $errorMessage], 400);
                    }
                    
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['proof_url' => $errorMessage]);
                }
                
                // Check file size limits based on type
                $isVideo = in_array($extension, ['mp4', 'mov', 'avi', 'webm']);
                $maxSizeBytes = $isVideo ? 8 * 1024 * 1024 : 50 * 1024 * 1024; // 8MB for videos, 50MB for others
                
                if ($fileSize > $maxSizeBytes) {
                    $maxSizeMB = $isVideo ? '8MB' : '50MB';
                    $errorMessage = $isVideo 
                        ? "Video file too large. Maximum size allowed: {$maxSizeMB}. Your file: " . round($fileSize / 1024 / 1024, 2) . "MB"
                        : "File too large. Maximum size allowed: {$maxSizeMB}. Your file: " . round($fileSize / 1024 / 1024, 2) . "MB";
                    
                    if (request()->ajax() || request()->wantsJson()) {
                        return response()->json(['error' => $errorMessage], 400);
                    }
                    
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['proof_url' => $errorMessage]);
                }
                
                
                if (!in_array($mimeType, $allowedMimeTypes)) {
                    \Log::warning('Unexpected MIME type:', [
                        'file' => $file->getClientOriginalName(),
                        'mime_type' => $mimeType,
                        'extension' => $extension
                    ]);
                    // Allow the upload but log the warning
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'file_info' => $request->hasFile('proof_url') ? [
                    'name' => $request->file('proof_url')->getClientOriginalName(),
                    'mime' => $request->file('proof_url')->getMimeType(),
                    'size' => $request->file('proof_url')->getSize()
                ] : 'No file'
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            
            throw $e;
        }

        if ($request->hasFile('proof_url')) {
            $file = $request->file('proof_url');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Log file details for debugging
            \Log::info('File upload attempt:', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
                'is_valid' => $file->isValid(),
                'error' => $file->getErrorMessage(),
                'is_video' => in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov', 'avi', 'webm']),
                'php_upload_max' => ini_get('upload_max_filesize'),
                'php_post_max' => ini_get('post_max_size')
            ]);
            
            // Check if file upload was successful
            if (!$file->isValid()) {
                \Log::error('File upload failed:', [
                    'error' => $file->getErrorMessage(),
                    'file' => $file->getClientOriginalName()
                ]);
                $errorMessage = 'File upload failed: ' . $file->getErrorMessage();
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['error' => $errorMessage], 400);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['proof_url' => $errorMessage]);
            }
            
            // Check file size against PHP limits
            $fileSize = $file->getSize();
            $maxSize = 10 * 1024 * 1024; // 10MB in bytes
            
            if ($fileSize > $maxSize) {
                $errorMessage = 'File too large. Maximum size allowed is 10MB. Your file is ' . round($fileSize / 1024 / 1024, 2) . 'MB.';
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['error' => $errorMessage], 400);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['proof_url' => $errorMessage]);
            }
            
            try {
                $filePath = $file->storeAs('proofs', $fileName, 'public');
                $validated['proof_url'] = $filePath;
                
                // Determine proof type based on file extension
                $extension = strtolower($file->getClientOriginalExtension());
                $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                $documentExtensions = ['pdf', 'doc', 'docx'];
                
                if (in_array($extension, $videoExtensions)) {
                    $validated['proof_type'] = 'video';
                } elseif (in_array($extension, $documentExtensions)) {
                    $validated['proof_type'] = 'document';
                } else {
                    $validated['proof_type'] = 'photo';
                }
                $validated['mime_type'] = $file->getMimeType();
                $validated['file_size'] = $fileSize;
            } catch (\Exception $e) {
                $errorMessage = 'File upload failed: ' . $e->getMessage();
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['error' => $errorMessage], 500);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['proof_url' => $errorMessage]);
            }
        }

        $proof = SessionProof::create([
            'session_id' => $validated['session_id'],
            'proof_url' => $validated['proof_url'],
            'mime_type' => $validated['mime_type'] ?? null,
            'proof_type' => $validated['proof_type'] ?? 'photo',
            'file_size' => $validated['file_size'] ?? null,
            'uploaded_by' => Auth::id(),
            'processing_status' => 'completed',
        ]);

        // Debug logging
        \Log::info('Upload request details:', [
            'is_ajax' => request()->ajax(),
            'wants_json' => request()->wantsJson(),
            'content_type' => request()->header('Content-Type'),
            'accept' => request()->header('Accept'),
            'x_requested_with' => request()->header('X-Requested-With'),
            'method' => request()->method()
        ]);

        // Return JSON response for AJAX requests, redirect for regular form submissions
        if (request()->ajax() || request()->wantsJson()) {
            \Log::info('Returning JSON response for upload');
            return response()->json([
                'success' => true,
                'message' => 'Proof uploaded successfully!',
                'proof_id' => $proof->id,
                'file_path' => $proof->proof_url
            ]);
        }

        \Log::info('Returning redirect response for upload');

        return redirect()->route($this->getRedirectRoute())
            ->with('success', 'Proof uploaded successfully!');
    }

    /**
     * Show the form for editing a proof
     */
    public function edit(SessionProof $proof)
    {
        $clubs = Club::orderBy('club_name')->get();
        $sessions = \App\Models\SessionSchedule::with('club')->orderBy('created_at', 'desc')->get();
        
        // Determine which layout to use
        if (request()->routeIs('facilitator.*')) {
            $layout = 'layouts.facilitator';
        } elseif (request()->routeIs('teacher.*')) {
            $layout = 'layouts.teacher';
        } else {
            $layout = 'layouts.admin';
        }
        
        return view('admin.proofs.edit', compact('proof', 'clubs', 'sessions'))->layout($layout);
    }

    /**
     * Update a proof
     */
    public function update(Request $request, SessionProof $proof)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:sessions_schedule,id',
            'proof_url' => 'nullable|file|max:51200', // Increased to 50MB
            'description' => 'nullable|string|max:1000',
        ]);
        
        // Custom validation for file types
        if ($request->hasFile('proof_url')) {
            $file = $request->file('proof_url');
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'mp4', 'mov', 'avi', 'webm'];
            $allowedMimeTypes = [
                'image/jpeg', 'image/jpg', 'image/png',
                'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'video/mp4', 'video/mpeg4', 'application/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'
            ];
            
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['proof_url' => 'File type not allowed. Allowed types: Images (JPG, PNG), Videos (MP4, MOV, AVI, WEBM), Documents (PDF, DOC, DOCX)']);
            }
            
            if (!in_array($mimeType, $allowedMimeTypes)) {
                \Log::warning('Unexpected MIME type:', [
                    'file' => $file->getClientOriginalName(),
                    'mime_type' => $mimeType,
                    'extension' => $extension
                ]);
                // Allow the upload but log the warning
            }
        }

        if ($request->hasFile('proof_url')) {
            // Delete old file
            if ($proof->proof_url && Storage::disk('public')->exists($proof->proof_url)) {
                Storage::disk('public')->delete($proof->proof_url);
            }
            
            $file = $request->file('proof_url');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Check if file upload was successful
            if (!$file->isValid()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['proof_url' => 'File upload failed: ' . $file->getErrorMessage()]);
            }
            
            // Check file size against PHP limits
            $fileSize = $file->getSize();
            $maxSize = 10 * 1024 * 1024; // 10MB in bytes
            
            if ($fileSize > $maxSize) {
                $errorMessage = 'File too large. Maximum size allowed is 10MB. Your file is ' . round($fileSize / 1024 / 1024, 2) . 'MB.';
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['error' => $errorMessage], 400);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['proof_url' => $errorMessage]);
            }
            
            try {
                $filePath = $file->storeAs('proofs', $fileName, 'public');
                $validated['proof_url'] = $filePath;
                
                // Determine proof type based on file extension
                $extension = strtolower($file->getClientOriginalExtension());
                $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                $documentExtensions = ['pdf', 'doc', 'docx'];
                
                if (in_array($extension, $videoExtensions)) {
                    $validated['proof_type'] = 'video';
                } elseif (in_array($extension, $documentExtensions)) {
                    $validated['proof_type'] = 'document';
                } else {
                    $validated['proof_type'] = 'photo';
                }
                $validated['mime_type'] = $file->getMimeType();
                $validated['file_size'] = $fileSize;
            } catch (\Exception $e) {
                $errorMessage = 'File upload failed: ' . $e->getMessage();
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['error' => $errorMessage], 500);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['proof_url' => $errorMessage]);
            }
        }

        $proof->update($validated);

        return redirect()->route($this->getRedirectRoute())
            ->with('success', 'Proof updated successfully!');
    }
}