<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        $indexes = \DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index]);
        return count($indexes) > 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add performance indexes for frequently queried columns
        
        // Reports table indexes
        Schema::table('reports', function (Blueprint $table) {
            if (!$this->indexExists('reports', 'idx_reports_club_student')) {
                $table->index(['club_id', 'student_id'], 'idx_reports_club_student');
            }
            if (!$this->indexExists('reports', 'idx_reports_created_at')) {
                $table->index('created_at', 'idx_reports_created_at');
            }
            if (!$this->indexExists('reports', 'idx_reports_generated_at')) {
                $table->index('report_generated_at', 'idx_reports_generated_at');
            }
        });

        // Attendance records indexes
        Schema::table('attendance_records', function (Blueprint $table) {
            if (!$this->indexExists('attendance_records', 'idx_attendance_student_session')) {
                $table->index(['student_id', 'session_id'], 'idx_attendance_student_session');
            }
            if (!$this->indexExists('attendance_records', 'idx_attendance_status')) {
                $table->index('attendance_status', 'idx_attendance_status');
            }
            if (!$this->indexExists('attendance_records', 'idx_attendance_created_at')) {
                $table->index('created_at', 'idx_attendance_created_at');
            }
        });

        // Assessment scores indexes
        Schema::table('assessment_scores', function (Blueprint $table) {
            if (!$this->indexExists('assessment_scores', 'idx_scores_student_assessment')) {
                $table->index(['student_id', 'assessment_id'], 'idx_scores_student_assessment');
            }
            if (!$this->indexExists('assessment_scores', 'idx_scores_value')) {
                $table->index('score_value', 'idx_scores_value');
            }
            if (!$this->indexExists('assessment_scores', 'idx_scores_created_at')) {
                $table->index('created_at', 'idx_scores_created_at');
            }
        });

        // Sessions schedule indexes
        Schema::table('sessions_schedule', function (Blueprint $table) {
            if (!$this->indexExists('sessions_schedule', 'idx_sessions_club_date')) {
                $table->index(['club_id', 'session_date'], 'idx_sessions_club_date');
            }
            if (!$this->indexExists('sessions_schedule', 'idx_sessions_date')) {
                $table->index('session_date', 'idx_sessions_date');
            }
        });

        // Students table indexes
        Schema::table('students', function (Blueprint $table) {
            if (!$this->indexExists('students', 'idx_students_school')) {
                $table->index('school_id', 'idx_students_school');
            }
            if (!$this->indexExists('students', 'idx_students_email')) {
                $table->index('email', 'idx_students_email');
            }
            if (!$this->indexExists('students', 'idx_students_id_number')) {
                $table->index('student_id_number', 'idx_students_id_number');
            }
        });

        // Clubs table indexes
        Schema::table('clubs', function (Blueprint $table) {
            if (!$this->indexExists('clubs', 'idx_clubs_school')) {
                $table->index('school_id', 'idx_clubs_school');
            }
            if (!$this->indexExists('clubs', 'idx_clubs_start_date')) {
                $table->index('club_start_date', 'idx_clubs_start_date');
            }
            if (!$this->indexExists('clubs', 'idx_clubs_level')) {
                $table->index('club_level', 'idx_clubs_level');
            }
        });

        // Assessments table indexes
        Schema::table('assessments', function (Blueprint $table) {
            if (!$this->indexExists('assessments', 'idx_assessments_club_week')) {
                $table->index(['club_id', 'assessment_week_number'], 'idx_assessments_club_week');
            }
            if (!$this->indexExists('assessments', 'idx_assessments_type')) {
                $table->index('assessment_type', 'idx_assessments_type');
            }
            if (!$this->indexExists('assessments', 'idx_assessments_created_at')) {
                $table->index('created_at', 'idx_assessments_created_at');
            }
        });

        // Club enrollments table indexes
        Schema::table('club_enrollments', function (Blueprint $table) {
            if (!$this->indexExists('club_enrollments', 'idx_enrollments_student_club')) {
                $table->index(['student_id', 'club_id'], 'idx_enrollments_student_club');
            }
            if (!$this->indexExists('club_enrollments', 'idx_enrollments_created_at')) {
                $table->index('created_at', 'idx_enrollments_created_at');
            }
        });

        // Schools table indexes
        Schema::table('schools', function (Blueprint $table) {
            if (!$this->indexExists('schools', 'idx_schools_name')) {
                $table->index('school_name', 'idx_schools_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes in reverse order
        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex('idx_schools_name');
        });

        Schema::table('club_enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_created_at');
            $table->dropIndex('idx_enrollments_student_club');
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex('idx_assessments_created_at');
            $table->dropIndex('idx_assessments_type');
            $table->dropIndex('idx_assessments_club_week');
        });

        Schema::table('clubs', function (Blueprint $table) {
            $table->dropIndex('idx_clubs_level');
            $table->dropIndex('idx_clubs_start_date');
            $table->dropIndex('idx_clubs_school');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_id_number');
            $table->dropIndex('idx_students_email');
            $table->dropIndex('idx_students_school');
        });

        Schema::table('sessions_schedule', function (Blueprint $table) {
            $table->dropIndex('idx_sessions_date');
            $table->dropIndex('idx_sessions_club_date');
        });

        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->dropIndex('idx_scores_created_at');
            $table->dropIndex('idx_scores_value');
            $table->dropIndex('idx_scores_student_assessment');
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropIndex('idx_attendance_created_at');
            $table->dropIndex('idx_attendance_status');
            $table->dropIndex('idx_attendance_student_session');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('idx_reports_generated_at');
            $table->dropIndex('idx_reports_created_at');
            $table->dropIndex('idx_reports_club_student');
        });
    }
};