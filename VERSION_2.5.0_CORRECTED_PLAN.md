# Code Club System V2.5.0 - Corrected Implementation Plan

## CORRECTED APPROACH: Separate Users and Students

### Why This Approach is Better:
- **Users Table**: For authenticated accounts (admin, facilitator, teacher)
- **Students Table**: For student records (managed by users, no direct login)
- **Club Enrollments**: Links students to clubs (existing structure)
- **Club Teachers**: Links teachers (users) to clubs (new pivot table)

This maintains the existing V1 structure while adding the new role hierarchy.

## Phase 1: Database Schema (Corrected)

### 1.1 Add New Roles to Users Table
**File**: `database/migrations/2025_10_21_000002_add_facilitator_teacher_roles_to_users.php`
- Add `facilitator_id` column for teacher → facilitator relationship
- Update `user_role` enum to include: `admin`, `facilitator`, `teacher`
- Add performance indexes

### 1.2 Add Facilitator to Clubs Table
**File**: `database/migrations/2025_10_21_000003_add_facilitator_to_clubs.php`
- Add `facilitator_id` to clubs table
- Link facilitators to clubs they manage

### 1.3 Create Club-Teacher Pivot Table
**File**: `database/migrations/2025_10_21_000004_create_club_teacher_table.php`
```php
Schema::create('club_teacher', function (Blueprint $table) {
    $table->id();
    $table->foreignId('club_id')->constrained()->cascadeOnDelete();
    $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
    $table->unique(['club_id', 'teacher_id']);
});
```

### 1.4 Create Lesson Notes Table
**File**: `database/migrations/2025_10_21_000005_create_lesson_notes_table.php`
- Resources created by admins/facilitators
- Visibility control (all vs teachers_only)

### 1.5 Create Club Sessions Table
**File**: `database/migrations/2025_10_21_000006_create_club_sessions_table.php`
- Sessions created by teachers
- Links to clubs and teachers

### 1.6 Create Session Attendance Table
**File**: `database/migrations/2025_10_21_000007_create_session_attendance_table.php`
```php
Schema::create('session_attendance', function (Blueprint $table) {
    $table->id();
    $table->foreignId('club_session_id')->constrained()->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
    $table->timestamp('attended_at')->useCurrent();
    $table->text('notes')->nullable();
    $table->unique(['club_session_id', 'student_id']);
});
```

### 1.7 Create Session Proofs Table
**File**: `database/migrations/2025_10_21_000008_create_session_proofs_table.php`
- Photos/videos uploaded by teachers
- Links to sessions

### 1.8 Update Reports Table
**File**: `database/migrations/2025_10_21_000009_update_reports_for_approval_workflow.php`
- Add facilitator_id, admin_id for approval workflow
- Add feedback fields and timestamps
- Update status enum for multi-level approval

## Phase 2: Models (Corrected)

### 2.1 User Model
**File**: `app/Models/User.php`
```php
// Relationships
public function facilitator() // Teacher -> Facilitator
public function teachers() // Facilitator -> Teachers
public function clubsAsTeacher() // belongsToMany via club_teacher
public function managedClubs() // Facilitator -> Clubs
public function createdResources() // hasMany LessonNote
public function uploadedProofs() // hasMany SessionProof

// Scopes
public function scopeTeachers($query)
public function scopeFacilitators($query)
```

### 2.2 Student Model (Existing - Enhanced)
**File**: `app/Models/Student.php`
```php
// Add new relationships
public function attendedSessions() // belongsToMany ClubSession via session_attendance
public function attendanceRecords() // hasMany SessionAttendance

// Accessor
public function getFullNameAttribute() // student_first_name + student_last_name
```

### 2.3 Club Model (Enhanced)
**File**: `app/Models/Club.php`
```php
// Add new relationships
public function facilitator() // belongsTo User
public function teachers() // belongsToMany User via club_teacher
public function lessonNotes() // hasMany LessonNote
public function sessions() // hasMany ClubSession

// Keep existing relationships
public function students() // belongsToMany Student via club_enrollments
```

### 2.4 ClubSession Model
**File**: `app/Models/ClubSession.php`
```php
public function club() // belongsTo Club
public function teacher() // belongsTo User
public function proofs() // hasMany SessionProof
public function attendance() // hasMany SessionAttendance
public function students() // belongsToMany Student via session_attendance

// Accessor
public function getStudentCountAttribute()
{
    return $this->attendance()->count();
}
```

### 2.5 SessionProof Model
**File**: `app/Models/SessionProof.php`
```php
public function session() // belongsTo ClubSession
public function uploader() // belongsTo User
```

### 2.6 LessonNote Model
**File**: `app/Models/LessonNote.php`
```php
public function club() // belongsTo Club
public function creator() // belongsTo User

// Scope
public function scopeVisibleTo($query, User $user)
```

### 2.7 Report Model (Enhanced)
**File**: `app/Models/Report.php`
```php
// Add new relationships
public function facilitator() // belongsTo User
public function admin() // belongsTo User

// Scopes
public function scopePendingFacilitatorApproval($query)
public function scopePendingAdminApproval($query)
```

## Phase 3: Controllers (Corrected)

### 3.1 FacilitatorController
- Manage teachers under supervision
- Approve reports from teachers
- Monitor club activities

### 3.2 TeacherController
- Manage assigned clubs
- Create sessions and mark attendance
- Upload proof photos/videos
- Submit reports to facilitators

### 3.3 ResourceController
- Upload lesson materials
- Control visibility (all vs teachers_only)
- Manage club resources

### 3.4 ReportApprovalController
- Facilitator approval workflow
- Admin approval workflow
- Revision requests

## Phase 4: Views (Corrected)

### 4.1 Facilitator Dashboard
- Overview of managed clubs
- Pending reports queue
- Teacher management

### 4.2 Teacher Dashboard
- Assigned clubs
- Session management
- Report submission
- Proof upload interface

### 4.3 Student Access (Existing V1)
- Students continue to access via existing V1 system
- No changes to student authentication
- Assessment system remains unchanged

## Phase 5: Authorization (Corrected)

### 5.1 User Policies
- Admin: Full system access
- Facilitator: Manage assigned teachers and clubs
- Teacher: Manage assigned clubs only

### 5.2 Club Policies
- Facilitators can manage clubs assigned to them
- Teachers can view clubs they're assigned to
- Students can access clubs they're enrolled in

### 5.3 Report Policies
- Teachers can create reports for their clubs
- Facilitators can approve reports from their teachers
- Admins can approve all reports

## Phase 6: Workflow (Corrected)

### 6.1 Admin → Facilitator → Teacher → Student
1. Admin creates facilitators and assigns them to schools
2. Facilitator creates/manages teachers and assigns them to clubs
3. Teacher manages students in their clubs (existing V1 students)
4. Teacher creates sessions, marks attendance, uploads proofs
5. Teacher submits reports to facilitator
6. Facilitator approves reports and forwards to admin
7. Admin provides final approval

### 6.2 Student Management (Unchanged)
- Students remain in separate students table
- Club enrollments work as before
- Student authentication via V1 system unchanged
- Assessment system unchanged

## Benefits of This Approach:

1. **Preserves V1 Functionality**: All existing student features remain unchanged
2. **Clean Separation**: Users (authenticated) vs Students (managed records)
3. **Scalable**: Easy to add new user roles without affecting students
4. **Backward Compatible**: No breaking changes to existing student system
5. **Logical Hierarchy**: Admin → Facilitator → Teacher → Student workflow

## Database Relationships:

```
users (admin, facilitator, teacher)
├── facilitator_id (teacher → facilitator)
├── clubs (facilitator manages)
└── club_teacher (teacher assigned to clubs)

students (existing V1 structure)
├── club_enrollments (student → club)
└── session_attendance (student → session)

clubs
├── facilitator_id (facilitator manages)
├── club_teacher (teachers assigned)
├── club_enrollments (students enrolled)
├── lesson_notes (resources)
└── club_sessions (sessions)

club_sessions
├── teacher_id (session created by)
├── session_proofs (photos/videos)
└── session_attendance (student attendance)

reports
├── teacher_id (created by)
├── facilitator_id (approved by)
└── admin_id (final approval)
```

This approach maintains the existing V1 student system while adding the new hierarchical user management system on top of it.
