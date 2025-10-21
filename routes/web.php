<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClubSessionsController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ParentReportController;
use Illuminate\Support\Facades\Route as FacadesRoute;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect admin users to admin dashboard
    if ($user->user_role === 'admin' || $user->user_role === 'super_admin') {
        return redirect()->route('admin.dashboard');
    }
    
    // Redirect facilitator users to facilitator dashboard
    if ($user->user_role === 'facilitator') {
        return redirect()->route('facilitator.dashboard');
    }
    
    // Redirect teacher users to teacher dashboard
    if ($user->user_role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }
    
    // Regular users see the standard dashboard
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', \App\Http\Middleware\EnsureUserBelongsToSchool::class])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    
    // Schools (regular users)
    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/schools/create', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
    Route::get('/schools/{school_id}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
    Route::put('/schools/{school_id}', [SchoolController::class, 'update'])->name('schools.update');
    Route::delete('/schools/{school_id}', [SchoolController::class, 'destroy'])->name('schools.destroy');

    // Clubs (regular users)
    Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
    Route::get('/clubs/{club_id}', [ClubController::class, 'show'])->name('clubs.show');
    Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
    Route::put('/clubs/{club_id}', [ClubController::class, 'update'])->name('clubs.update');
    Route::delete('/clubs/{club_id}', [ClubController::class, 'destroy'])->name('clubs.destroy');
    Route::post('/clubs/{club_id}/sessions/generate', [ClubSessionsController::class, 'generate'])->name('clubs.sessions.generate');

    // Assessments
    Route::get('/clubs/{club_id}/assessments/create', [AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/clubs/{club_id}/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/assessments/{assessment_id}', [AssessmentController::class, 'show'])->name('assessments.show');
    Route::get('/assessments/{assessment_id}/edit', [AssessmentController::class, 'edit'])->name('assessments.edit');
    Route::put('/assessments/{assessment_id}', [AssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('/assessments/{assessment_id}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');
    Route::get('/assessments/{assessment_id}/duplicate', [AssessmentController::class, 'duplicate'])->name('assessments.duplicate');
    Route::get('/assessments/{assessment_id}/scores', [AssessmentController::class, 'scores'])->name('assessments.scores');
    Route::post('/assessments/{assessment_id}/scores', [AssessmentController::class, 'store_scores'])->name('assessments.scores.store');
    Route::put('/assessments/scores/{score_id}/grade', [AssessmentController::class, 'grade'])->name('assessments.scores.grade');
    Route::get('/assessments/scores/{score_id}/submission', [AssessmentController::class, 'getSubmission'])->name('assessments.scores.submission');
    Route::post('/clubs/{club_id}/assessments/ai-generate', [AssessmentController::class, 'ai_generate'])->name('assessments.ai-generate');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/clubs/{club_id}/reports/generate', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/clubs/{club_id}/reports/generate', [ReportController::class, 'generate_for_club'])->name('reports.generate');
    Route::get('/reports/{report_id}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report_id}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report_id}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report_id}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::get('/reports/{report_id}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::post('/reports/{report_id}/send', [ReportController::class, 'send_to_parent'])->name('reports.send');
    Route::post('/reports/{report_id}/regenerate-access-code', [ReportController::class, 'regenerate_access_code'])->name('reports.regenerate-access-code');
    Route::post('/reports/{report_id}/generate-ai-single', [ReportController::class, 'generate_ai_single'])->name('reports.generate-ai-single');
    
    // Report Approval route for regular users (if they have access)
    Route::get('/reports/approval', function () {
        // Check if user has admin or facilitator role
        if (!in_array(auth()->user()->user_role, ['admin', 'facilitator'])) {
            abort(403, 'Access denied. Only administrators and facilitators can access report approval.');
        }
        
        // Get reports data for the original reports functionality
        $reports = \App\Models\Report::with(['student', 'club', 'access_code'])
            ->orderBy('report_generated_at', 'desc')
            ->paginate(10);
        
        // Get clubs for report generation (filtered by user's school if not admin)
        $clubs = \App\Models\Club::with(['school'])
            ->whereHas('students')
            ->when(auth()->user()->user_role !== 'admin', function($query) {
                $query->whereHas('school', function($q) {
                    $q->where('id', auth()->user()->school_id);
                });
            })
            ->get();
        
        return view('admin.reports.index', compact('reports', 'clubs'));
    })->name('user.reports.approval');

// Parent access routes (no authentication required)
Route::get('/parent-welcome', [ParentReportController::class, 'welcome'])->name('parent.welcome');
Route::post('/parent-access/verify', [ParentReportController::class, 'verify_access_code'])->name('parent.verify-access');
Route::get('/parent-access/{access_code}', [ReportController::class, 'parent_preview'])->name('reports.parent-preview');
Route::post('/parent-access/verify-old', [ReportController::class, 'verify_parent_access'])->name('reports.verify-parent-access');

    // Attendance grid
    Route::get('/clubs/{club_id}/attendance', [AttendanceController::class, 'show_grid'])->name('attendance.grid');
    // Students (Original)
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/students/bulk-enroll', [StudentController::class, 'bulkEnroll'])->name('students.bulk-enroll');
    
    // Admin Student Management (with credentials)
    Route::get('/admin/students', [App\Http\Controllers\AdminStudentController::class, 'index'])->name('admin.students.index');
    Route::get('/admin/students/create', [App\Http\Controllers\AdminStudentController::class, 'create'])->name('admin.students.create');
    Route::post('/admin/students', [App\Http\Controllers\AdminStudentController::class, 'store'])->name('admin.students.store');
    Route::get('/admin/students/{student}', [App\Http\Controllers\AdminStudentController::class, 'show'])->name('admin.students.show');
    Route::get('/admin/students/{student}/edit', [App\Http\Controllers\AdminStudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/admin/students/{student}', [App\Http\Controllers\AdminStudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/admin/students/{student}', [App\Http\Controllers\AdminStudentController::class, 'destroy'])->name('admin.students.destroy');
    Route::get('/admin/students/{student}/reset-password', [App\Http\Controllers\AdminStudentController::class, 'showResetPassword'])->name('admin.students.reset-password');
    Route::post('/admin/students/{student}/reset-password', [App\Http\Controllers\AdminStudentController::class, 'resetPassword'])->name('admin.students.reset-password.post');
    Route::post('/admin/students/bulk-update-ids', [App\Http\Controllers\AdminStudentController::class, 'bulkUpdateIds'])->name('admin.students.bulk-update-ids');
    Route::post('/admin/students/bulk-enroll', [App\Http\Controllers\AdminStudentController::class, 'bulkEnroll'])->name('admin.students.bulk-enroll');
    
    
    // Assessments
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    
// Sessions
Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');

// Student Dashboard (student-specific)
Route::get('/students/{student_id}/dashboard', function(int $student_id) {
    $student = \App\Models\Student::with('clubs.school')->findOrFail($student_id);
    return view('students.dashboard', compact('student'));
})->name('students.dashboard');


// Attendance Management
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/clubs/{club_id}/attendance', [AttendanceController::class, 'show_grid'])->name('attendance.grid');
Route::post('/attendance/{club_id}', [AttendanceController::class, 'store'])->name('attendance.store');
Route::put('/attendance/{attendance_id}', [AttendanceController::class, 'update'])->name('attendance.update');
Route::delete('/attendance/{attendance_id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
Route::post('/attendance/bulk/{club_id}', [AttendanceController::class, 'bulk_update'])->name('attendance.bulk_update');
Route::post('/attendance/update/{club_id}', [AttendanceController::class, 'update_attendance'])->name('attendance.update_attendance');
Route::get('/api/clubs/{club_id}/sessions', [AttendanceController::class, 'getClubData'])->name('api.clubs.sessions');

      // V2.5.0 - Facilitator routes
      Route::middleware(['auth', 'role:facilitator'])->prefix('facilitator')->name('facilitator.')->group(function () {
          Route::get('/dashboard', App\Livewire\FacilitatorDashboard::class)->name('dashboard');
          Route::get('/teachers', [App\Http\Controllers\FacilitatorController::class, 'teachers'])->name('teachers');
          Route::get('/teachers/{teacher}', [App\Http\Controllers\FacilitatorController::class, 'showTeacher'])->name('teachers.show');
          Route::get('/clubs', [App\Http\Controllers\FacilitatorController::class, 'clubs'])->name('clubs');
          Route::get('/clubs/{club}', [App\Http\Controllers\FacilitatorController::class, 'showClub'])->name('clubs.show');
          Route::get('/reports/approval', function () {
              // Get reports data for the original reports functionality
              $reports = \App\Models\Report::with(['student', 'club', 'access_code'])
                  ->orderBy('report_generated_at', 'desc')
                  ->paginate(10);
              
              // Get clubs for report generation (filtered by facilitator's school)
              $clubs = \App\Models\Club::with(['school'])
                  ->whereHas('students')
                  ->whereHas('school', function($query) {
                      $query->where('id', auth()->user()->school_id);
                  })
                  ->get();
              
              return view('admin.reports.index', compact('reports', 'clubs'));
          })->name('facilitator.reports.approval');
          Route::post('/reports/{reportId}/approve', [App\Http\Controllers\ReportApprovalController::class, 'facilitatorApprove'])->name('reports.approve');
          Route::post('/reports/{reportId}/reject', [App\Http\Controllers\ReportApprovalController::class, 'reject'])->name('reports.reject');
          Route::post('/reports/{reportId}/request-revision', [App\Http\Controllers\ReportApprovalController::class, 'requestRevision'])->name('reports.request-revision');
          
          // Session Feedback routes
          Route::get('/feedback', [App\Http\Controllers\SessionFeedbackController::class, 'index'])->name('feedback.index');
          Route::get('/sessions/{session}/feedback/create', [App\Http\Controllers\SessionFeedbackController::class, 'create'])->name('feedback.create');
          Route::post('/sessions/{session}/feedback', [App\Http\Controllers\SessionFeedbackController::class, 'store'])->name('feedback.store');
          Route::get('/feedback/{sessionFeedback}', [App\Http\Controllers\SessionFeedbackController::class, 'show'])->name('feedback.show');
          Route::get('/feedback/{sessionFeedback}/edit', [App\Http\Controllers\SessionFeedbackController::class, 'edit'])->name('feedback.edit');
          Route::put('/feedback/{sessionFeedback}', [App\Http\Controllers\SessionFeedbackController::class, 'update'])->name('feedback.update');
          Route::delete('/feedback/{sessionFeedback}', [App\Http\Controllers\SessionFeedbackController::class, 'destroy'])->name('feedback.destroy');
      });

      // V2.5.0 - Teacher routes
      Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
          Route::get('/dashboard', App\Livewire\TeacherDashboard::class)->name('dashboard');
          Route::get('/clubs', [App\Http\Controllers\TeacherController::class, 'clubs'])->name('clubs');
          Route::get('/sessions', [App\Http\Controllers\TeacherController::class, 'sessions'])->name('sessions');
          Route::get('/sessions/create', [App\Http\Controllers\TeacherController::class, 'createSession'])->name('sessions.create');
          Route::post('/sessions', [App\Http\Controllers\TeacherController::class, 'storeSession'])->name('sessions.store');
          Route::get('/sessions/{session}', [App\Http\Controllers\TeacherController::class, 'showSession'])->name('sessions.show');
          Route::post('/sessions/{session}/proof', [App\Http\Controllers\TeacherController::class, 'uploadProof'])->name('sessions.proof');
          Route::get('/sessions/{session}/attendance', [App\Http\Controllers\TeacherController::class, 'sessionAttendance'])->name('sessions.attendance');
          Route::post('/sessions/{session}/attendance', [App\Http\Controllers\TeacherController::class, 'updateAttendance'])->name('sessions.attendance.update');
          Route::get('/reports', [App\Http\Controllers\TeacherController::class, 'reports'])->name('reports');
          Route::get('/reports/create', [App\Http\Controllers\TeacherController::class, 'createReport'])->name('reports.create');
          Route::post('/reports', [App\Http\Controllers\TeacherController::class, 'storeReport'])->name('reports.store');
          Route::get('/reports/{report}', [App\Http\Controllers\TeacherController::class, 'showReport'])->name('reports.show');
          
          // Session Feedback routes
          Route::get('/feedback', [App\Http\Controllers\SessionFeedbackController::class, 'index'])->name('feedback.index');
          Route::get('/feedback/{sessionFeedback}', [App\Http\Controllers\SessionFeedbackController::class, 'show'])->name('feedback.show');
      });

    // V2.5.0 - Resource routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/clubs/{clubId}/resources', [App\Http\Controllers\ResourceController::class, 'index'])->name('resources.index');
        Route::get('/clubs/{clubId}/resources/create', [App\Http\Controllers\ResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [App\Http\Controllers\ResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{resource}', [App\Http\Controllers\ResourceController::class, 'show'])->name('resources.show');
        Route::get('/resources/{resource}/download', [App\Http\Controllers\ResourceController::class, 'download'])->name('resources.download');
        Route::get('/resources/{resource}/edit', [App\Http\Controllers\ResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{resource}', [App\Http\Controllers\ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [App\Http\Controllers\ResourceController::class, 'destroy'])->name('resources.destroy');
    });

      // V2.5.0 - Admin routes
      Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
          // Admin Dashboard
          Route::get('/dashboard', function () {
              return view('dashboard')->layout('layouts.admin');
          })->name('dashboard');
          
          // Main Admin Routes (moved from main middleware group)
        Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
        Route::get('/schools/create', [SchoolController::class, 'create'])->name('schools.create');
        Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
        Route::get('/schools/{school_id}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
        Route::put('/schools/{school_id}', [SchoolController::class, 'update'])->name('schools.update');
        Route::delete('/schools/{school_id}', [SchoolController::class, 'destroy'])->name('schools.destroy');

        Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
        Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
        Route::get('/clubs/{club_id}', [ClubController::class, 'show'])->name('clubs.show');
        Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
        Route::put('/clubs/{club_id}', [ClubController::class, 'update'])->name('clubs.update');
        Route::delete('/clubs/{club_id}', [ClubController::class, 'destroy'])->name('clubs.destroy');
        Route::post('/clubs/{club_id}/sessions/generate', [ClubSessionsController::class, 'generate'])->name('clubs.sessions.generate');

        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::post('/students/bulk-enroll', [StudentController::class, 'bulkEnroll'])->name('students.bulk-enroll');

        // Report Approval routes
        Route::get('/reports/approval', function () {
            // Get reports data for the original reports functionality
            $reports = \App\Models\Report::with(['student', 'club', 'access_code'])
                ->orderBy('report_generated_at', 'desc')
                ->paginate(10);
            
            // Get clubs for report generation
            $clubs = \App\Models\Club::with(['school'])
                ->whereHas('students')
                ->get();
            
            return view('admin.reports.index', compact('reports', 'clubs'));
        })->name('admin.reports.approval');
        Route::post('/reports/{reportId}/approve', [App\Http\Controllers\ReportApprovalController::class, 'adminApprove'])->name('reports.approve');
        Route::post('/reports/{reportId}/reject', [App\Http\Controllers\ReportApprovalController::class, 'reject'])->name('reports.reject');
        Route::post('/reports/{reportId}/request-revision', [App\Http\Controllers\ReportApprovalController::class, 'requestRevision'])->name('reports.request-revision');
        
        // User Management routes
        Route::get('/users', [App\Http\Controllers\AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminUserController::class, 'destroy'])->name('users.destroy');
        
        // Resource Management routes
        Route::get('/resources', [App\Http\Controllers\AdminResourceController::class, 'index'])->name('resources.index');
        Route::get('/resources/create', [App\Http\Controllers\AdminResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [App\Http\Controllers\AdminResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{resource}/edit', [App\Http\Controllers\AdminResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{resource}', [App\Http\Controllers\AdminResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [App\Http\Controllers\AdminResourceController::class, 'destroy'])->name('resources.destroy');
        
        // Settings routes
        Route::get('/settings', [App\Http\Controllers\AdminSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\AdminSettingsController::class, 'update'])->name('settings.update');
        Route::get('/notifications/settings', [App\Http\Controllers\AdminNotificationSettingsController::class, 'index'])->name('notifications.settings');
        Route::post('/notifications/settings', [App\Http\Controllers\AdminNotificationSettingsController::class, 'update'])->name('notifications.update');
        
          // Admin Profile routes
          Route::get('/profile', [App\Http\Controllers\AdminProfileController::class, 'index'])->name('profile');
          Route::put('/profile', [App\Http\Controllers\AdminProfileController::class, 'update'])->name('profile.update');
          
          // Session Feedback routes
          Route::get('/feedback', [App\Http\Controllers\SessionFeedbackController::class, 'index'])->name('feedback.index');
          Route::get('/feedback/analytics', [App\Http\Controllers\SessionFeedbackController::class, 'analytics'])->name('feedback.analytics');
          Route::get('/feedback/export', [App\Http\Controllers\SessionFeedbackController::class, 'export'])->name('feedback.export');
          Route::post('/feedback/export', [App\Http\Controllers\SessionFeedbackController::class, 'export'])->name('feedback.export.post');
          Route::get('/feedback/{sessionFeedback}', [App\Http\Controllers\SessionFeedbackController::class, 'show'])->name('feedback.show');
          Route::get('/feedback/{sessionFeedback}/edit', [App\Http\Controllers\SessionFeedbackController::class, 'edit'])->name('feedback.edit');
          Route::put('/feedback/{sessionFeedback}', [App\Http\Controllers\SessionFeedbackController::class, 'update'])->name('feedback.update');
          Route::delete('/feedback/{sessionFeedback}', [App\Http\Controllers\SessionFeedbackController::class, 'destroy'])->name('feedback.destroy');
          
          // Teacher Proofs routes
          Route::get('/proofs', [App\Http\Controllers\AdminProofController::class, 'index'])->name('proofs.index');
          Route::get('/proofs/archived', [App\Http\Controllers\AdminProofController::class, 'archived'])->name('proofs.archived');
          Route::get('/proofs/{proof}', [App\Http\Controllers\AdminProofController::class, 'show'])->name('proofs.show');
          Route::post('/proofs/{proof}/approve', [App\Http\Controllers\AdminProofController::class, 'approve'])->name('proofs.approve');
          Route::post('/proofs/{proof}/reject', [App\Http\Controllers\AdminProofController::class, 'reject'])->name('proofs.reject');
          Route::post('/proofs/{proof}/mark-under-review', [App\Http\Controllers\AdminProofController::class, 'markUnderReview'])->name('proofs.mark-under-review');
          Route::post('/proofs/{proof}/archive', [App\Http\Controllers\AdminProofController::class, 'archive'])->name('proofs.archive');
          Route::post('/proofs/{proof}/unarchive', [App\Http\Controllers\AdminProofController::class, 'unarchive'])->name('proofs.unarchive');
          Route::post('/proofs/bulk-approve', [App\Http\Controllers\AdminProofController::class, 'bulkApprove'])->name('proofs.bulk-approve');
          Route::post('/proofs/bulk-reject', [App\Http\Controllers\AdminProofController::class, 'bulkReject'])->name('proofs.bulk-reject');
          Route::post('/proofs/bulk-archive', [App\Http\Controllers\AdminProofController::class, 'bulkArchive'])->name('proofs.bulk-archive');
          Route::post('/proofs/bulk-unarchive', [App\Http\Controllers\AdminProofController::class, 'bulkUnarchive'])->name('proofs.bulk-unarchive');
          Route::post('/proofs/bulk-delete', [App\Http\Controllers\AdminProofController::class, 'bulkDelete'])->name('proofs.bulk-delete');
          Route::post('/proofs/bulk-export', [App\Http\Controllers\AdminProofController::class, 'bulkExport'])->name('proofs.bulk-export');
          Route::get('/proofs/{proof}/download', [App\Http\Controllers\AdminProofController::class, 'download'])->name('proofs.download');
          Route::delete('/proofs/{proof}', [App\Http\Controllers\AdminProofController::class, 'destroy'])->name('proofs.destroy');
          Route::get('/proofs-analytics', [App\Http\Controllers\AdminProofController::class, 'analytics'])->name('proofs.analytics');
      });
});

require __DIR__.'/auth.php';

// Student Authentication Routes (Login Only)
Route::middleware('guest')->group(function () {
    Route::get('/student/login', [App\Http\Controllers\StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/student/login', [App\Http\Controllers\StudentAuthController::class, 'login'])->name('student.login.post');
});

// Student Protected Routes
Route::middleware(['auth:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\StudentAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\StudentAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\StudentAuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/logout', [App\Http\Controllers\StudentAuthController::class, 'logout'])->name('logout');
    
    // Student Dashboard Features
    Route::get('/assignments', [App\Http\Controllers\StudentDashboardController::class, 'assignments'])->name('assignments');
    Route::get('/assignments/{assessmentId}', [App\Http\Controllers\StudentDashboardController::class, 'showAssessment'])->name('assessment.show');
    Route::post('/assignments/{assessmentId}/submit', [App\Http\Controllers\StudentDashboardController::class, 'submitAssessment'])->name('assessment.submit');
    Route::get('/progress', [App\Http\Controllers\StudentDashboardController::class, 'progress'])->name('progress');
    Route::get('/reports', [App\Http\Controllers\StudentDashboardController::class, 'reports'])->name('reports');
});

// Public parent report route (no auth)
Route::get('/r/{report_id}', [ParentReportController::class, 'show_public'])->name('reports.public');
