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
     * Show the student registration form
     */
    public function showRegisterForm()
    {
        return view('students.auth.register');
    }

    /**
     * Handle student registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8|confirmed',
            'student_id_number' => 'required|string|max:50|unique:students',
            'student_grade_level' => 'required|string|max:10',
            'student_parent_name' => 'required|string|max:255',
            'student_parent_email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student = Student::create([
            'student_first_name' => $request->student_first_name,
            'student_last_name' => $request->student_last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'student_id_number' => $request->student_id_number,
            'student_grade_level' => $request->student_grade_level,
            'student_parent_name' => $request->student_parent_name,
            'student_parent_email' => $request->student_parent_email,
            'school_id' => 1, // Default school for now
        ]);

        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard')
            ->with('success', 'Account created successfully! Welcome to your dashboard.');
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
        
        // Load relationships for dashboard data
        $student->load([
            'clubs.sessions.attendance_records',
            'clubs.assessments.scores',
            'assessment_scores.assessment',
            'reports'
        ]);

        // Calculate dashboard statistics
        $stats = [
            'total_clubs' => $student->clubs->count(),
            'total_assessments' => $student->assessment_scores->count(),
            'average_unlock_score' => $student->getAverageAssessmentScore(),
            'attendance_percentage' => $student->getAttendancePercentage(),
            'total_reports' => $student->reports->count(),
        ];

        // Get recent activities
        $recentAssessments = $student->assessment_scores()
            ->with('assessment')
            ->latest()
            ->take(5)
            ->get();

        $upcomingSessions = collect();
        foreach ($student->clubs as $club) {
            $clubSessions = $club->sessions()
                ->where('session_date', '>=', now())
                ->orderBy('session_date')
                ->take(3)
                ->get();
            $upcomingSessions = $upcomingSessions->merge($clubSessions);
        }

        return view('students.dashboard', compact('student', 'stats', 'recentAssessments', 'upcomingSessions'));
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