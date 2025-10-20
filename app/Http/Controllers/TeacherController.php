<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use App\Models\ClubSession;
use App\Models\SessionProof;
use App\Models\Report;
use App\Models\Student;
use App\Services\FileUploadService;
use App\Notifications\ReportAwaitingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher');
        $this->middleware('throttle:10,1')->only('uploadProof'); // 10 uploads per minute
    }

    /**
     * Display the teacher dashboard
     */
    public function index()
    {
        $teacher = User::with([
            'clubsAsTeacher.sessions' => fn($q) => $q->latest()->limit(10),
            'clubsAsTeacher.lessonNotes'
        ])->findOrFail(auth()->id());
        
        // Cache stats for 5 minutes
        $stats = Cache::remember("teacher.{$teacher->id}.stats", 300, function() use ($teacher) {
            return [
                'total_clubs' => $teacher->clubsAsTeacher()->count(),
                'total_sessions' => $teacher->uploadedProofs()->count(),
                'pending_reports' => Report::where('teacher_id', $teacher->id)
                    ->whereIn('status', ['pending', 'revision_requested'])
                    ->count(),
                'total_students' => $teacher->clubsAsTeacher()
                    ->withCount('students')
                    ->get()
                    ->sum('students_count'),
            ];
        });
        
        return view('teacher.dashboard', compact('teacher', 'stats'));
    }

    /**
     * Display all clubs assigned to this teacher
     */
    public function clubs()
    {
        $clubs = auth()->user()->clubsAsTeacher()
            ->with(['students', 'lessonNotes', 'clubSessions' => fn($q) => $q->latest()->limit(5)])
            ->paginate(20);
        
        return view('teacher.clubs', compact('clubs'));
    }

    /**
     * Display all sessions for this teacher
     */
    public function sessions()
    {
        $sessions = ClubSession::with(['club', 'proofs', 'students'])
            ->where('teacher_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('teacher.sessions', compact('sessions'));
    }

    /**
     * Show the form for creating a new session
     */
    public function createSession()
    {
        $clubs = auth()->user()->clubsAsTeacher()->get();
        
        return view('teacher.sessions.create', compact('clubs'));
    }

    /**
     * Store a newly created session
     */
    public function storeSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_id' => 'required|exists:clubs,id',
            'session_date' => 'required|date',
            'session_time' => 'nullable|date_format:H:i',
            'session_notes' => 'nullable|string|max:1000',
        ]);

        // Ensure the teacher is assigned to the club
        $validator->after(function ($validator) use ($request) {
            $club = Club::find($request->club_id);
            if ($club && !auth()->user()->clubsAsTeacher->contains($club->id)) {
                $validator->errors()->add('club_id', 'You are not assigned to this club.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        ClubSession::create([
            'club_id' => $request->club_id,
            'teacher_id' => auth()->id(),
            'session_date' => $request->session_date,
            'session_time' => $request->session_time,
            'session_notes' => $request->session_notes,
        ]);

        return redirect()->route('teacher.sessions')->with('success', 'Session created successfully.');
    }

    /**
     * Display a specific session
     */
    public function showSession(ClubSession $session)
    {
        // Ensure the teacher owns this session
        if ($session->teacher_id !== auth()->id()) {
            abort(403, 'You can only view your own sessions.');
        }

        $session->load(['club', 'proofs.uploader', 'students']);
        
        return view('teacher.sessions.show', compact('session'));
    }

    /**
     * Upload proof files for a session
     */
    public function uploadProof(Request $request, ClubSession $session)
    {
        // Ensure the teacher owns this session
        if ($session->teacher_id !== auth()->id()) {
            abort(403, 'You can only upload proofs for your own sessions.');
        }

        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg,mp4,mov|max:51200', // 50MB
        ]);
        
        $service = app(FileUploadService::class);
        
        foreach ($request->file('files') as $file) {
            $result = $service->uploadProof($file, $session->id, auth()->id());
            
            // Dispatch queue job if video
            if ($result['proof_type'] === 'video') {
                // ProcessUploadedProofJob::dispatch($result['proof_id']);
                // For now, we'll handle video processing synchronously
            }
        }
        
        return back()->with('success', 'Proofs uploaded successfully');
    }

    /**
     * Display all reports created by this teacher
     */
    public function reports()
    {
        $reports = Report::with(['club', 'student'])
            ->where('teacher_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('teacher.reports', compact('reports'));
    }

    /**
     * Show the form for creating a new report
     */
    public function createReport()
    {
        $clubs = auth()->user()->clubsAsTeacher()->with('students')->get();
        
        return view('teacher.reports.create', compact('clubs'));
    }

    /**
     * Store a newly created report
     */
    public function storeReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_id' => 'required|exists:clubs,id',
            'student_id' => 'required|exists:students,id',
            'report_name' => 'required|string|max:255',
            'report_summary_text' => 'required|string',
            'report_overall_score' => 'required|numeric|min:0|max:100',
            'problem_solving_score' => 'required|numeric|min:0|max:100',
            'creativity_score' => 'required|numeric|min:0|max:100',
            'collaboration_score' => 'required|numeric|min:0|max:100',
            'persistence_score' => 'required|numeric|min:0|max:100',
        ]);

        // Ensure the teacher is assigned to the club
        $validator->after(function ($validator) use ($request) {
            $club = Club::find($request->club_id);
            if ($club && !auth()->user()->clubsAsTeacher->contains($club->id)) {
                $validator->errors()->add('club_id', 'You are not assigned to this club.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $report = Report::create([
            'club_id' => $request->club_id,
            'student_id' => $request->student_id,
            'teacher_id' => auth()->id(),
            'report_name' => $request->report_name,
            'report_summary_text' => $request->report_summary_text,
            'report_overall_score' => $request->report_overall_score,
            'problem_solving_score' => $request->problem_solving_score,
            'creativity_score' => $request->creativity_score,
            'collaboration_score' => $request->collaboration_score,
            'persistence_score' => $request->persistence_score,
            'status' => 'pending',
            'report_generated_at' => now(),
        ]);

        // Set the facilitator_id based on the club's facilitator
        $club = Club::find($request->club_id);
        if ($club && $club->facilitator_id) {
            $report->update(['facilitator_id' => $club->facilitator_id]);
            
            // Notify facilitator about new report
            $facilitator = User::find($club->facilitator_id);
            if ($facilitator) {
                $facilitator->notify(new ReportAwaitingApproval($report, 'facilitator'));
            }
        }

        return redirect()->route('teacher.reports')->with('success', 'Report created successfully.');
    }

    /**
     * Display a specific report
     */
    public function showReport(Report $report)
    {
        // Ensure the teacher owns this report
        if ($report->teacher_id !== auth()->id()) {
            abort(403, 'You can only view your own reports.');
        }

        $report->load(['club', 'student', 'facilitator', 'admin']);
        
        return view('teacher.reports.show', compact('report'));
    }

    /**
     * Display attendance for a specific session
     */
    public function sessionAttendance(ClubSession $session)
    {
        // Ensure the teacher owns this session
        if ($session->teacher_id !== auth()->id()) {
            abort(403, 'You can only view attendance for your own sessions.');
        }

        $session->load(['club.students']);
        
        // Get existing attendance records
        $attendanceRecords = $session->attendance()->with('student')->get()->keyBy('student_id');
        
        return view('teacher.sessions.attendance', compact('session', 'attendanceRecords'));
    }

    /**
     * Update attendance for a session
     */
    public function updateAttendance(Request $request, ClubSession $session)
    {
        // Ensure the teacher owns this session
        if ($session->teacher_id !== auth()->id()) {
            abort(403, 'You can only update attendance for your own sessions.');
        }

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:500',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            if ($status === 'present') {
                $session->students()->syncWithoutDetaching([
                    $studentId => [
                        'attended_at' => now(),
                        'notes' => $request->notes[$studentId] ?? null,
                    ]
                ]);
            } else {
                $session->students()->detach($studentId);
            }
        }

        return back()->with('success', 'Attendance updated successfully.');
    }
}
