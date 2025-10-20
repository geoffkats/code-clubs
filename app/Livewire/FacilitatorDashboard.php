<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Report;
use App\Models\Club;
use App\Notifications\ReportAwaitingApproval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FacilitatorDashboard extends Component
{
    use WithPagination;

    public $stats = [];
    public $recentReports = [];
    public $recentSessions = [];
    public $topTeachers = [];
    public $clubPerformance = [];
    
    // Filters
    public $selectedPeriod = '30';
    public $selectedClub = '';
    public $searchTerm = '';

    // Modal states
    public $showReportModal = false;
    public $selectedReport = null;
    public $reportFeedback = '';
    public $reportAction = '';

    protected $listeners = [
        'refreshDashboard' => '$refresh',
        'reportApproved' => 'handleReportApproved',
        'reportRejected' => 'handleReportRejected'
    ];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $facilitator = auth()->user();
        
        // Load stats with caching
        $this->stats = Cache::remember("facilitator.{$facilitator->id}.dashboard_stats", 300, function() use ($facilitator) {
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
                'active_teachers' => $facilitator->teachers()
                    ->whereHas('clubsAsTeacher.clubSessions', function($q) {
                        $q->where('session_date', '>=', now()->subDays(7));
                    })
                    ->count(),
                'completion_rate' => $this->getCompletionRate($facilitator),
            ];
        });

        // Load recent reports
        $this->recentReports = Report::with(['teacher', 'club', 'student'])
            ->where('facilitator_id', $facilitator->id)
            ->latest()
            ->limit(5)
            ->get();

        // Load recent sessions
        $this->recentSessions = DB::table('club_sessions')
            ->join('clubs', 'club_sessions.club_id', '=', 'clubs.id')
            ->join('users', 'club_sessions.teacher_id', '=', 'users.id')
            ->where('clubs.facilitator_id', $facilitator->id)
            ->select('club_sessions.*', 'clubs.club_name', 'users.name as teacher_name')
            ->latest('club_sessions.session_date')
            ->limit(5)
            ->get();

        // Load top performing teachers
        $this->topTeachers = $facilitator->teachers()
            ->withCount('clubsAsTeacher')
            ->with(['clubsAsTeacher' => function($q) {
                $q->withCount('clubSessions');
            }])
            ->get()
            ->map(function($teacher) {
                $teacher->total_sessions = $teacher->clubsAsTeacher->sum('club_sessions_count');
                return $teacher;
            })
            ->sortByDesc('total_sessions')
            ->take(5);

        // Load club performance data
        $this->clubPerformance = $facilitator->managedClubs()
            ->withCount(['clubSessions', 'students'])
            ->get()
            ->map(function($club) {
                $club->attendance_rate = $this->getClubAttendanceRate($club->id);
                $club->reports_count = Report::where('club_id', $club->id)->count();
                return $club;
            });
    }

    public function getCompletionRate($facilitator)
    {
        $totalReports = Report::where('facilitator_id', $facilitator->id)->count();
        $completedReports = Report::where('facilitator_id', $facilitator->id)
            ->whereIn('status', ['facilitator_approved', 'admin_approved'])
            ->count();
        
        return $totalReports > 0 ? round(($completedReports / $totalReports) * 100, 1) : 0;
    }

    public function getClubAttendanceRate($clubId)
    {
        $totalAttendance = DB::table('session_attendance')
            ->join('club_sessions', 'session_attendance.club_session_id', '=', 'club_sessions.id')
            ->where('club_sessions.club_id', $clubId)
            ->count();

        $totalPossible = DB::table('club_sessions')
            ->where('club_id', $clubId)
            ->count() * DB::table('club_enrollments')
            ->where('club_id', $clubId)
            ->count();

        return $totalPossible > 0 ? round(($totalAttendance / $totalPossible) * 100, 1) : 0;
    }

    public function approveReport($reportId)
    {
        $report = Report::findOrFail($reportId);
        $report->update([
            'status' => 'facilitator_approved',
            'facilitator_approved_at' => now(),
        ]);

        // Notify admin if there's an admin for this school
        $admin = User::where('user_role', 'admin')
            ->where('school_id', $report->club->school_id)
            ->first();
        
        if ($admin) {
            $admin->notify(new ReportAwaitingApproval($report, 'admin'));
        }

        // Notify teacher
        $report->teacher->notify(new \App\Notifications\ReportApproved($report, 'facilitator'));

        $this->dispatch('reportApproved', $reportId);
        $this->loadDashboardData();
        
        session()->flash('success', 'Report approved successfully!');
    }

    public function rejectReport($reportId)
    {
        $this->selectedReport = Report::findOrFail($reportId);
        $this->reportAction = 'reject';
        $this->showReportModal = true;
    }

    public function requestRevision($reportId)
    {
        $this->selectedReport = Report::findOrFail($reportId);
        $this->reportAction = 'revision';
        $this->showReportModal = true;
    }

    public function submitReportAction()
    {
        if (!$this->selectedReport || !$this->reportFeedback) {
            return;
        }

        $status = $this->reportAction === 'reject' ? 'rejected' : 'revision_requested';
        
        $this->selectedReport->update([
            'status' => $status,
            'facilitator_feedback' => $this->reportFeedback,
        ]);

        // Notify teacher
        if ($status === 'rejected') {
            $this->selectedReport->teacher->notify(new \App\Notifications\ReportRejected($this->selectedReport, 'facilitator'));
        } else {
            $this->selectedReport->teacher->notify(new \App\Notifications\ReportRevisionRequested($this->selectedReport, 'facilitator'));
        }

        $this->showReportModal = false;
        $this->selectedReport = null;
        $this->reportFeedback = '';
        $this->reportAction = '';
        
        $this->loadDashboardData();
        session()->flash('success', 'Report action completed successfully!');
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->selectedReport = null;
        $this->reportFeedback = '';
        $this->reportAction = '';
    }

    public function filterByPeriod($period)
    {
        $this->selectedPeriod = $period;
        $this->loadDashboardData();
    }

    public function filterByClub($clubId)
    {
        $this->selectedClub = $clubId;
        $this->loadDashboardData();
    }

    public function refreshStats()
    {
        Cache::forget("facilitator." . auth()->id() . ".dashboard_stats");
        $this->loadDashboardData();
        session()->flash('success', 'Dashboard refreshed!');
    }

    public function render()
    {
        return view('livewire.facilitator-dashboard')
            ->layout('layouts.admin');
    }
}