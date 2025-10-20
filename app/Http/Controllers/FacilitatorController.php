<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FacilitatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:facilitator');
    }

    /**
     * Display the facilitator dashboard
     */
    public function index()
    {
        // Explicit eager loading - NO global $with
        $facilitator = User::with([
            'managedClubs.sessions' => fn($q) => $q->latest()->limit(5),
            'managedClubs.teachers',
            'teachers.clubsAsTeacher'
        ])->findOrFail(auth()->id());
        
        $pendingReports = Report::with(['teacher', 'club'])
            ->pendingFacilitatorApproval()
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->get();
        
        // Cache stats for 5 minutes
        $stats = Cache::remember("facilitator.{$facilitator->id}.stats", 300, function() use ($facilitator) {
            return [
                'total_clubs' => $facilitator->managedClubs()->count(),
                'total_teachers' => $facilitator->teachers()->count(),
                'pending_reports' => Report::where('facilitator_id', $facilitator->id)
                    ->pendingFacilitatorApproval()
                    ->count(),
                'total_sessions' => $facilitator->managedClubs()
                    ->withCount('clubSessions')
                    ->get()
                    ->sum('club_sessions_count'),
            ];
        });
        
        return view('facilitator.dashboard', compact('facilitator', 'pendingReports', 'stats'));
    }

    /**
     * Display all teachers under this facilitator
     */
    public function teachers()
    {
        $teachers = User::teachers()
            ->where('facilitator_id', auth()->id())
            ->with('clubsAsTeacher')
            ->paginate(20);
        
        return view('facilitator.teachers', compact('teachers'));
    }

    /**
     * Display all clubs managed by this facilitator
     */
    public function clubs()
    {
        $clubs = Club::managedBy(auth()->id())
            ->with(['teachers', 'lessonNotes', 'clubSessions' => fn($q) => $q->latest()->limit(10)])
            ->paginate(20);
        
        return view('facilitator.clubs', compact('clubs'));
    }

    /**
     * Display pending reports for approval
     */
    public function pendingReports()
    {
        $reports = Report::with(['teacher', 'club', 'student'])
            ->pendingFacilitatorApproval()
            ->where('facilitator_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('facilitator.pending-reports', compact('reports'));
    }

    /**
     * Display approved reports
     */
    public function approvedReports()
    {
        $reports = Report::with(['teacher', 'club', 'student'])
            ->where('facilitator_id', auth()->id())
            ->whereIn('status', ['facilitator_approved', 'admin_approved'])
            ->latest()
            ->paginate(20);
        
        return view('facilitator.approved-reports', compact('reports'));
    }

    /**
     * Show a specific teacher's details
     */
    public function showTeacher(User $teacher)
    {
        // Ensure this teacher belongs to the current facilitator
        if ($teacher->facilitator_id !== auth()->id()) {
            abort(403, 'You can only view teachers under your supervision.');
        }

        $teacher->load(['clubsAsTeacher.sessions', 'clubsAsTeacher.lessonNotes']);
        
        $reports = Report::with(['club', 'student'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->paginate(10);
        
        return view('facilitator.teacher-details', compact('teacher', 'reports'));
    }

    /**
     * Show a specific club's details
     */
    public function showClub(Club $club)
    {
        // Ensure this club is managed by the current facilitator
        if ($club->facilitator_id !== auth()->id()) {
            abort(403, 'You can only view clubs you manage.');
        }

        $club->load(['teachers', 'lessonNotes', 'clubSessions.proofs', 'students']);
        
        $recentReports = Report::with(['teacher', 'student'])
            ->where('club_id', $club->id)
            ->latest()
            ->limit(10)
            ->get();
        
        return view('facilitator.club-details', compact('club', 'recentReports'));
    }
}
