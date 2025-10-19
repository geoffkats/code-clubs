<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends Controller
{
    /**
     * Show the student login form
     */
    public function showLoginForm()
    {
        return view('students.auth.login');
    }

    /**
     * Handle student login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('student.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }


    /**
     * Handle student logout
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }

    /**
     * Show the student dashboard
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        // Create cache key for student dashboard
        $cacheKey = 'student_dashboard_' . $student->id;
        
        // Cache dashboard data for 10 minutes
        $dashboardData = \Cache::remember($cacheKey, 600, function() use ($student) {
            // Load only necessary relationships with selective fields
            $student->load([
                'clubs:id,club_name,club_level,club_start_date',
                'assessment_scores:id,student_id,assessment_id,score_value,score_max_value,created_at',
                'reports:id,student_id,club_id,report_name,report_generated_at'
            ]);

            // Calculate dashboard statistics efficiently
            $stats = [
                'total_clubs' => $student->clubs->count(),
                'total_assessments' => $student->assessment_scores->count(),
                'average_assessment_score' => $this->calculateAverageScore($student),
                'attendance_percentage' => $this->calculateAttendancePercentage($student),
                'total_reports' => $student->reports->count(),
            ];

            // Get recent activities with optimized queries
            $recentAssessments = $student->assessment_scores()
                ->select(['id', 'assessment_id', 'score_value', 'score_max_value', 'created_at'])
                ->with(['assessment:id,assessment_name,assessment_type'])
                ->latest()
                ->take(5)
                ->get();

            // Get upcoming sessions efficiently
            $upcomingSessions = \DB::table('sessions_schedule')
                ->join('club_enrollments', 'sessions_schedule.club_id', '=', 'club_enrollments.club_id')
                ->join('clubs', 'sessions_schedule.club_id', '=', 'clubs.id')
                ->select([
                    'sessions_schedule.id',
                    'sessions_schedule.session_date',
                    'sessions_schedule.session_time',
                    'clubs.club_name',
                    'clubs.club_level'
                ])
                ->where('club_enrollments.student_id', $student->id)
                ->where('sessions_schedule.session_date', '>=', now()->toDateString())
                ->orderBy('sessions_schedule.session_date')
                ->limit(6)
                ->get();

            return [
                'stats' => $stats,
                'recentAssessments' => $recentAssessments,
                'upcomingSessions' => $upcomingSessions
            ];
        });

        return view('students.dashboard', compact('student', 'stats', 'recentAssessments', 'upcomingSessions'));
    }

    /**
     * Calculate average assessment score for student
     */
    private function calculateAverageScore($student): float
    {
        $scores = $student->assessment_scores;
        if ($scores->isEmpty()) {
            return 0.0;
        }

        $total = $scores->sum(function($score) {
            return $score->score_max_value > 0 
                ? ($score->score_value / $score->score_max_value) * 100 
                : 0;
        });

        return round($total / $scores->count(), 2);
    }

    /**
     * Calculate attendance percentage for student
     */
    private function calculateAttendancePercentage($student): float
    {
        $totalSessions = \DB::table('sessions_schedule')
            ->join('club_enrollments', 'sessions_schedule.club_id', '=', 'club_enrollments.club_id')
            ->where('club_enrollments.student_id', $student->id)
            ->count();

        if ($totalSessions === 0) {
            return 0.0;
        }

        $attendedSessions = \DB::table('attendance_records')
            ->join('sessions_schedule', 'attendance_records.session_id', '=', 'sessions_schedule.id')
            ->join('club_enrollments', 'sessions_schedule.club_id', '=', 'club_enrollments.club_id')
            ->where('attendance_records.student_id', $student->id)
            ->where('attendance_records.attendance_status', 'present')
            ->where('club_enrollments.student_id', $student->id)
            ->count();

        return round(($attendedSessions / $totalSessions) * 100, 2);
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $student = Auth::guard('student')->user();
        return view('students.profile', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validator = Validator::make($request->all(), [
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'student_grade_level' => 'required|string|max:10',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'student_first_name',
            'student_last_name', 
            'email',
            'student_grade_level'
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = 'students/' . $student->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $data['profile_image'] = $filename;
        }

        $student->update($data);

        return redirect()->route('student.profile')
            ->with('success', 'Profile updated successfully!');
    }
}