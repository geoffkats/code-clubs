<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Club;
use App\Models\Assessment;
use App\Models\AssessmentScore;
use App\Models\SessionSchedule;
use App\Models\AttendanceRecord;

class DashboardDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get first student and club
        $student = Student::first();
        $club = Club::first();
        
        if (!$student || !$club) {
            $this->command->info('No students or clubs found. Please run the main seeder first.');
            return;
        }

        // Create sample assessments
        $assessment1 = Assessment::create([
            'club_id' => $club->id,
            'assessment_name' => 'Python Basics Quiz',
            'assessment_type' => 'quiz',
            'description' => 'Test your Python knowledge',
            'total_points' => 100,
            'due_date' => now()->addDays(7)
        ]);

        $assessment2 = Assessment::create([
            'club_id' => $club->id,
            'assessment_name' => 'Web Development Project',
            'assessment_type' => 'project',
            'description' => 'Build a simple website',
            'total_points' => 100,
            'due_date' => now()->addDays(14)
        ]);

        // Create sample assessment scores
        AssessmentScore::create([
            'student_id' => $student->id,
            'assessment_id' => $assessment1->id,
            'score_value' => 85,
            'score_max_value' => 100
        ]);

        AssessmentScore::create([
            'student_id' => $student->id,
            'assessment_id' => $assessment2->id,
            'score_value' => 92,
            'score_max_value' => 100
        ]);

        // Create sample session schedules
        $session1 = SessionSchedule::create([
            'club_id' => $club->id,
            'session_date' => now()->addDays(1),
            'session_week_number' => 10
        ]);

        $session2 = SessionSchedule::create([
            'club_id' => $club->id,
            'session_date' => now()->addDays(3),
            'session_week_number' => 11
        ]);

        // Create sample attendance records
        AttendanceRecord::create([
            'student_id' => $student->id,
            'session_id' => $session1->id,
            'attendance_status' => 'present',
            'attendance_notes' => 'On time'
        ]);

        AttendanceRecord::create([
            'student_id' => $student->id,
            'session_id' => $session2->id,
            'attendance_status' => 'present',
            'attendance_notes' => 'Participated actively'
        ]);

        $this->command->info('Sample dashboard data created successfully!');
    }
}
