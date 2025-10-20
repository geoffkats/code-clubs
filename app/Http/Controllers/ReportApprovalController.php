<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Facilitator approves a report
     */
    public function facilitatorApprove($reportId)
    {
        $report = Report::with(['teacher', 'club'])->findOrFail($reportId);
        $this->authorize('approveFacilitator', $report);
        
        $report->update([
            'status' => 'facilitator_approved',
            'facilitator_approved_at' => now(),
        ]);
        
        // Notify admin
        $admin = User::where('user_role', 'admin')
            ->where('school_id', $report->club->school_id)
            ->first();
        
        if ($admin) {
            // ReportAwaitingAdminApproval::dispatch($report, $admin);
            // For now, we'll skip notifications
        }
        
        return back()->with('success', 'Report approved and forwarded to admin');
    }

    /**
     * Admin approves a report
     */
    public function adminApprove($reportId)
    {
        $report = Report::with('teacher')->findOrFail($reportId);
        $this->authorize('approveAdmin', $report);
        
        $report->update([
            'status' => 'admin_approved',
            'admin_id' => auth()->id(),
            'admin_approved_at' => now(),
        ]);
        
        // Notify teacher
        // $report->teacher->notify(new ReportApproved($report));
        // For now, we'll skip notifications
        
        return back()->with('success', 'Report approved');
    }

    /**
     * Request revision for a report
     */
    public function requestRevision(Request $request, $reportId)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000',
            'requested_by' => 'required|in:facilitator,admin',
        ]);
        
        $report = Report::with('teacher')->findOrFail($reportId);
        
        if ($request->requested_by === 'facilitator') {
            $this->authorize('approveFacilitator', $report);
            $report->update([
                'status' => 'revision_requested',
                'facilitator_feedback' => $request->feedback,
            ]);
        } else {
            $this->authorize('approveAdmin', $report);
            $report->update([
                'status' => 'revision_requested',
                'admin_feedback' => $request->feedback,
            ]);
        }
        
        // Notify teacher
        // $report->teacher->notify(new RevisionRequested($report));
        // For now, we'll skip notifications
        
        return back()->with('success', 'Revision requested');
    }

    /**
     * Reject a report
     */
    public function reject(Request $request, $reportId)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000',
            'rejected_by' => 'required|in:facilitator,admin',
        ]);
        
        $report = Report::with('teacher')->findOrFail($reportId);
        
        if ($request->rejected_by === 'facilitator') {
            $this->authorize('approveFacilitator', $report);
            $report->update([
                'status' => 'rejected',
                'facilitator_feedback' => $request->feedback,
            ]);
        } else {
            $this->authorize('approveAdmin', $report);
            $report->update([
                'status' => 'rejected',
                'admin_feedback' => $request->feedback,
            ]);
        }
        
        // Notify teacher
        // $report->teacher->notify(new ReportRejected($report));
        // For now, we'll skip notifications
        
        return back()->with('success', 'Report rejected');
    }

    /**
     * Display reports pending facilitator approval
     */
    public function facilitatorPendingReports()
    {
        $reports = Report::with(['teacher', 'club', 'student'])
            ->pendingFacilitatorApproval()
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('reports.facilitator-pending', compact('reports'));
    }

    /**
     * Display reports pending admin approval
     */
    public function adminPendingReports()
    {
        $reports = Report::with(['teacher', 'club', 'student', 'facilitator'])
            ->pendingAdminApproval()
            ->latest()
            ->paginate(20);
        
        return view('reports.admin-pending', compact('reports'));
    }

    /**
     * Display reports that need revision
     */
    public function revisionRequests()
    {
        $reports = Report::with(['teacher', 'club', 'student'])
            ->needsRevision()
            ->latest()
            ->paginate(20);
        
        return view('reports.revision-requests', compact('reports'));
    }

    /**
     * Display all reports for admin
     */
    public function adminReports()
    {
        $reports = Report::with(['teacher', 'club', 'student', 'facilitator'])
            ->latest()
            ->paginate(20);
        
        return view('reports.admin-all', compact('reports'));
    }

    /**
     * Display all reports for facilitator
     */
    public function facilitatorReports()
    {
        $reports = Report::with(['teacher', 'club', 'student'])
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('reports.facilitator-all', compact('reports'));
    }
}
