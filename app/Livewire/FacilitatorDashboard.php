<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Report;
use App\Models\Club;
use App\Models\LessonNote;
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
    public $recentResources = [];
    public $recentProofs = [];
    public $upcomingSessions = [];
    public $teacherPerformance = [];
    public $attendanceStats = [];
    
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
            $managedClubIds = $facilitator->managedClubs()->pluck('id');
            
            return [
                'total_clubs' => $facilitator->managedClubs()->count(),
                'total_teachers' => $facilitator->teachers()->count(),
                'total_students' => DB::table('club_enrollments')->whereIn('club_id', $managedClubIds)->count(),
                'pending_reports' => Report::where('facilitator_id', $facilitator->id)
                    ->pendingFacilitatorApproval()
                    ->count(),
                'total_sessions' => DB::table('club_sessions')->whereIn('club_id', $managedClubIds)->count(),
                'upcoming_sessions' => DB::table('club_sessions')
                    ->whereIn('club_id', $managedClubIds)
                    ->where('session_date', '>=', now())
                    ->count(),
                'active_teachers' => $facilitator->teachers()
                    ->whereHas('clubsAsTeacher.clubSessions', function($q) {
                        $q->where('session_date', '>=', now()->subDays(7));
                    })
                    ->count(),
                'total_proofs' => \App\Models\SessionProof::whereHas('session.club', function($q) use ($facilitator) {
                    $q->where('facilitator_id', $facilitator->id);
                })->count(),
                'pending_proofs' => \App\Models\SessionProof::whereHas('session.club', function($q) use ($facilitator) {
                    $q->where('facilitator_id', $facilitator->id);
                })->where('status', 'pending')->count(),
                'completion_rate' => $this->getCompletionRate($facilitator),
                'attendance_rate' => $this->getAttendanceStats($facilitator)['average_attendance'],
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

        // Load recent resources from managed clubs
        $managedClubIds = $facilitator->managedClubs()->pluck('id');
        $this->recentResources = LessonNote::with(['club', 'createdBy'])
            ->whereIn('club_id', $managedClubIds)
            ->latest()
            ->limit(5)
            ->get();

        // Load recent proofs from managed clubs
        $this->recentProofs = \App\Models\SessionProof::with(['session.club', 'uploadedBy'])
            ->whereHas('session.club', function($q) use ($facilitator) {
                $q->where('facilitator_id', $facilitator->id);
            })
            ->latest()
            ->limit(5)
            ->get();

        // Load upcoming sessions
        $this->upcomingSessions = DB::table('club_sessions')
            ->join('clubs', 'club_sessions.club_id', '=', 'clubs.id')
            ->join('users', 'club_sessions.teacher_id', '=', 'users.id')
            ->where('clubs.facilitator_id', $facilitator->id)
            ->where('club_sessions.session_date', '>=', now())
            ->select('club_sessions.*', 'clubs.club_name', 'users.name as teacher_name')
            ->orderBy('club_sessions.session_date')
            ->limit(5)
            ->get();

        // Load teacher performance data
        $this->teacherPerformance = $facilitator->teachers()
            ->withCount(['clubsAsTeacher', 'uploadedProofs'])
            ->with(['clubsAsTeacher' => function($q) {
                $q->withCount('clubSessions');
            }])
            ->get()
            ->map(function($teacher) {
                $teacher->total_sessions = $teacher->clubsAsTeacher->sum('club_sessions_count');
                $teacher->recent_activity = $teacher->uploaded_proofs_count;
                return $teacher;
            })
            ->sortByDesc('total_sessions')
            ->take(5);

        // Load attendance statistics
        $this->attendanceStats = $this->getAttendanceStats($facilitator);
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

    public function getAttendanceStats($facilitator)
    {
        $managedClubIds = $facilitator->managedClubs()->pluck('id');
        
        $totalSessions = DB::table('club_sessions')
            ->whereIn('club_id', $managedClubIds)
            ->where('session_date', '>=', now()->subDays($this->selectedPeriod))
            ->count();

        $totalAttendance = DB::table('session_attendance')
            ->join('club_sessions', 'session_attendance.club_session_id', '=', 'club_sessions.id')
            ->whereIn('club_sessions.club_id', $managedClubIds)
            ->where('club_sessions.session_date', '>=', now()->subDays($this->selectedPeriod))
            ->count();

        $totalStudents = DB::table('club_enrollments')
            ->whereIn('club_id', $managedClubIds)
            ->count();

        $averageAttendance = $totalSessions > 0 && $totalStudents > 0 
            ? round(($totalAttendance / ($totalSessions * $totalStudents)) * 100, 1) 
            : 0;

        return [
            'total_sessions' => $totalSessions,
            'total_attendance' => $totalAttendance,
            'total_students' => $totalStudents,
            'average_attendance' => $averageAttendance,
            'period' => $this->selectedPeriod
        ];
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
            ->layout('layouts.facilitator', [
                'title' => 'Facilitator Dashboard',
                'subtitle' => 'Overview of your clubs, teachers, and activities'
            ]);
    }
}