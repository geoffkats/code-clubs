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

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    
    // Schools (admin)
    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/schools/create', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
    Route::put('/schools/{school_id}', [SchoolController::class, 'update'])->name('schools.update');
    Route::delete('/schools/{school_id}', [SchoolController::class, 'destroy'])->name('schools.destroy');

    // Clubs
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
    Route::get('/assessments/{assessment_id}/scores', [AssessmentController::class, 'scores'])->name('assessments.scores');
    Route::post('/assessments/{assessment_id}/scores', [AssessmentController::class, 'store_scores'])->name('assessments.scores.store');
    Route::post('/clubs/{club_id}/assessments/ai-generate', [AssessmentController::class, 'ai_generate'])->name('assessments.ai-generate');
    Route::post('/assessments/{score_id}/grade', [AssessmentController::class, 'grade'])->name('assessments.grade');

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

// Scratch IDE
Route::get('/scratch/ide', function() {
    return view('scratch.ide');
})->name('scratch.ide');

// Attendance Management
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/clubs/{club_id}/attendance', [AttendanceController::class, 'show_grid'])->name('attendance.grid');
Route::post('/attendance/{club_id}', [AttendanceController::class, 'store'])->name('attendance.store');
Route::put('/attendance/{attendance_id}', [AttendanceController::class, 'update'])->name('attendance.update');
Route::delete('/attendance/{attendance_id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
Route::post('/attendance/bulk/{club_id}', [AttendanceController::class, 'bulk_update'])->name('attendance.bulk_update');
Route::post('/attendance/update/{club_id}', [AttendanceController::class, 'update_attendance'])->name('attendance.update_attendance');
Route::get('/api/clubs/{club_id}/sessions', [AttendanceController::class, 'getClubData'])->name('api.clubs.sessions');
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
