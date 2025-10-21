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
     * Display all teacher proofs for admin review
     */
    public function index(Request $request)
    {
        $query = SessionProof::with(['session.club', 'uploader', 'reviewer'])
            ->notArchived()
            ->orderBy('created_at', 'desc');

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
        
        // Get statistics
        $stats = [
            'total' => SessionProof::count(),
            'pending' => SessionProof::pending()->count(),
            'approved' => SessionProof::approved()->count(),
            'rejected' => SessionProof::rejected()->count(),
        ];

        return view('admin.proofs.index', compact('proofs', 'clubs', 'teachers', 'stats'));
    }

    /**
     * Show detailed view of a specific proof
     */
    public function show(SessionProof $proof)
    {
        $proof->load(['session.club', 'uploader', 'reviewer']);
        
        return view('admin.proofs.show', compact('proof'));
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

        return redirect()->route('admin.proofs.index')
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

        return redirect()->route('admin.proofs.index')
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

        return redirect()->route('admin.proofs.index')
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

        return redirect()->route('admin.proofs.index')
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

        return redirect()->route('admin.proofs.index')
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
            return redirect()->route('admin.proofs.index')
                ->with('error', 'Approved proofs cannot be deleted.');
        }

        // Delete the file from storage
        if ($proof->proof_url && Storage::disk('public')->exists($proof->proof_url)) {
            Storage::disk('public')->delete($proof->proof_url);
        }

        $proof->delete();

        return redirect()->route('admin.proofs.index')
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
}