<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Report;
use App\Models\Club;
use App\Models\ClubSession;
use App\Models\SessionProof;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TeacherDashboard extends Component
{
    use WithPagination, WithFileUploads;

    public $stats = [];
    public $upcomingSessions = [];
    public $recentReports = [];
    public $recentProofs = [];
    public $myClubs = [];
    
    // Session creation
    public $showCreateSessionModal = false;
    public $newSession = [
        'club_id' => '',
        'session_date' => '',
        'session_time' => '',
        'session_notes' => ''
    ];

    // Proof upload
    public $showUploadModal = false;
    public $selectedSessionId = '';
    public $proofFiles = [];
    public $uploadingProofs = false;

    // Filters
    public $selectedPeriod = '30';
    public $selectedClub = '';

    protected $listeners = [
        'refreshDashboard' => '$refresh',
        'sessionCreated' => 'handleSessionCreated',
        'proofUploaded' => 'handleProofUploaded'
    ];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $teacher = auth()->user();
        
        // Load stats with caching
        $this->stats = Cache::remember("teacher.{$teacher->id}.dashboard_stats", 300, function() use ($teacher) {
            return [
                'total_clubs' => $teacher->clubsAsTeacher()->count(),
                'total_sessions' => ClubSession::where('teacher_id', $teacher->id)->count(),
                'pending_reports' => Report::where('teacher_id', $teacher->id)
                    ->whereIn('status', ['pending', 'revision_requested'])
                    ->count(),
                'total_students' => $teacher->clubsAsTeacher()
                    ->withCount('students')
                    ->get()
                    ->sum('students_count'),
                'total_proofs' => SessionProof::where('uploaded_by', $teacher->id)->count(),
                'recent_activity' => $this->getRecentActivity($teacher),
            ];
        });

        // Load upcoming sessions
        $this->upcomingSessions = ClubSession::with(['club'])
            ->where('teacher_id', $teacher->id)
            ->where('session_date', '>=', now())
            ->orderBy('session_date')
            ->limit(5)
            ->get();

        // Load recent reports
        $this->recentReports = Report::with(['club', 'student'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->limit(5)
            ->get();

        // Load recent proofs
        $this->recentProofs = SessionProof::with(['session.club'])
            ->where('uploaded_by', $teacher->id)
            ->latest()
            ->limit(5)
            ->get();

        // Load my clubs
        $this->myClubs = $teacher->clubsAsTeacher()->with(['students', 'lessonNotes'])->get();
    }

    public function getRecentActivity($teacher)
    {
        $sessions = ClubSession::where('teacher_id', $teacher->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        $reports = Report::where('teacher_id', $teacher->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        $proofs = SessionProof::where('uploaded_by', $teacher->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        return $sessions + $reports + $proofs;
    }

    public function openCreateSessionModal()
    {
        $this->showCreateSessionModal = true;
        $this->newSession = [
            'club_id' => '',
            'session_date' => now()->format('Y-m-d'),
            'session_time' => now()->format('H:i'),
            'session_notes' => ''
        ];
    }

    public function closeCreateSessionModal()
    {
        $this->showCreateSessionModal = false;
        $this->newSession = [
            'club_id' => '',
            'session_date' => '',
            'session_time' => '',
            'session_notes' => ''
        ];
    }

    public function createSession()
    {
        $this->validate([
            'newSession.club_id' => 'required|exists:clubs,id',
            'newSession.session_date' => 'required|date',
            'newSession.session_time' => 'nullable|date_format:H:i',
            'newSession.session_notes' => 'nullable|string|max:1000',
        ]);

        // Ensure the teacher is assigned to the club
        $club = Club::find($this->newSession['club_id']);
        if ($club && !auth()->user()->clubsAsTeacher->contains($club->id)) {
            $this->addError('newSession.club_id', 'You are not assigned to this club.');
            return;
        }

        ClubSession::create([
            'club_id' => $this->newSession['club_id'],
            'teacher_id' => auth()->id(),
            'session_date' => $this->newSession['session_date'],
            'session_time' => $this->newSession['session_time'],
            'session_notes' => $this->newSession['session_notes'],
        ]);

        $this->closeCreateSessionModal();
        $this->loadDashboardData();
        $this->dispatch('sessionCreated');
        
        session()->flash('success', 'Session created successfully!');
    }

    public function openUploadModal($sessionId)
    {
        $this->selectedSessionId = $sessionId;
        $this->showUploadModal = true;
        $this->proofFiles = [];
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->selectedSessionId = '';
        $this->proofFiles = [];
        $this->uploadingProofs = false;
    }

    public function uploadProofs()
    {
        if (empty($this->proofFiles)) {
            $this->addError('proofFiles', 'Please select at least one file.');
            return;
        }

        $this->validate([
            'proofFiles.*' => 'required|file|mimes:jpeg,png,jpg,mp4,mov|max:51200', // 50MB
        ]);

        $this->uploadingProofs = true;
        $service = app(FileUploadService::class);
        
        foreach ($this->proofFiles as $file) {
            $result = $service->uploadProof($file, $this->selectedSessionId, auth()->id());
        }
        
        $this->closeUploadModal();
        $this->loadDashboardData();
        $this->dispatch('proofUploaded');
        
        session()->flash('success', 'Proofs uploaded successfully!');
    }

    public function downloadProof($proofId)
    {
        $proof = SessionProof::findOrFail($proofId);
        
        if ($proof->uploaded_by !== auth()->id()) {
            abort(403, 'You can only download your own proofs.');
        }

        return Storage::disk('proofs')->download($proof->proof_url);
    }

    public function deleteProof($proofId)
    {
        $proof = SessionProof::findOrFail($proofId);
        
        if ($proof->uploaded_by !== auth()->id()) {
            abort(403, 'You can only delete your own proofs.');
        }

        Storage::disk('proofs')->delete($proof->proof_url);
        $proof->delete();
        
        $this->loadDashboardData();
        session()->flash('success', 'Proof deleted successfully!');
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
        Cache::forget("teacher." . auth()->id() . ".dashboard_stats");
        $this->loadDashboardData();
        session()->flash('success', 'Dashboard refreshed!');
    }

    public function render()
    {
        return view('livewire.teacher-dashboard')
            ->layout('layouts.admin');
    }
}