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
    Route::get('/assessments/{assessment_id}/scores', [AssessmentController::class, 'scores'])->name('assessments.scores');

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

// Parent access routes (no authentication required)
Route::get('/parent-access/{access_code}', [ReportController::class, 'parent_preview'])->name('reports.parent-preview');
Route::post('/parent-access/verify', [ReportController::class, 'verify_parent_access'])->name('reports.verify-parent-access');

    // Attendance grid
    Route::get('/clubs/{club_id}/attendance', [AttendanceController::class, 'show_grid'])->name('attendance.grid');
    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    
    // Assessments
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    
// Sessions
Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');

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
});

require __DIR__.'/auth.php';

// Public parent report route (no auth)
Route::get('/r/{report_id}', [ParentReportController::class, 'show_public'])->name('reports.public');
