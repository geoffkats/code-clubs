<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Club;
use App\Models\Student;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional schools
        $school1 = School::create([
            'school_name' => 'Kampala International School',
            'address' => 'Kampala, Uganda',
            'contact_email' => 'info@kis.ug',
        ]);

        $school2 = School::create([
            'school_name' => 'Makerere University School',
            'address' => 'Makerere, Kampala',
            'contact_email' => 'info@mus.ug',
        ]);

        // Create clubs for each school
        $club1 = Club::create([
            'school_id' => $school1->id,
            'club_name' => 'Python Programming',
            'club_level' => 'intermediate',
            'club_duration_weeks' => 12,
            'club_start_date' => now(),
        ]);

        $club2 = Club::create([
            'school_id' => $school1->id,
            'club_name' => 'Web Development',
            'club_level' => 'advanced',
            'club_duration_weeks' => 16,
            'club_start_date' => now(),
        ]);

        $club3 = Club::create([
            'school_id' => $school2->id,
            'club_name' => 'Mobile App Development',
            'club_level' => 'beginner',
            'club_duration_weeks' => 10,
            'club_start_date' => now(),
        ]);

        // Create students and enroll them in clubs
        $students = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'grade' => '10',
                'parent_name' => 'Jane Doe',
                'parent_email' => 'jane.doe@email.com',
                'parent_phone' => '+256 700 123 456',
                'medical_info' => 'No known allergies',
                'clubs' => [$club1->id, $club2->id]
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Smith',
                'grade' => '11',
                'parent_name' => 'Robert Smith',
                'parent_email' => 'robert.smith@email.com',
                'parent_phone' => '+256 700 234 567',
                'medical_info' => 'Asthma - inhaler available',
                'clubs' => [$club1->id]
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'grade' => '9',
                'parent_name' => 'Lisa Johnson',
                'parent_email' => 'lisa.johnson@email.com',
                'parent_phone' => '+256 700 345 678',
                'medical_info' => 'None',
                'clubs' => [$club2->id, $club3->id]
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'grade' => '12',
                'parent_name' => 'David Brown',
                'parent_email' => 'david.brown@email.com',
                'parent_phone' => '+256 700 456 789',
                'medical_info' => 'None',
                'clubs' => [$club3->id]
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'grade' => '10',
                'parent_name' => 'Mary Wilson',
                'parent_email' => 'mary.wilson@email.com',
                'parent_phone' => '+256 700 567 890',
                'medical_info' => 'Epilepsy - medication taken daily',
                'clubs' => [$club1->id, $club3->id]
            ]
        ];

        foreach ($students as $studentData) {
            $student = Student::create([
                'school_id' => $school1->id, // All students in school1 for simplicity
                'student_first_name' => $studentData['first_name'],
                'student_last_name' => $studentData['last_name'],
                'student_grade_level' => $studentData['grade'],
                'student_parent_name' => $studentData['parent_name'],
                'student_parent_email' => $studentData['parent_email'],
                'student_parent_phone' => $studentData['parent_phone'],
                'student_medical_info' => $studentData['medical_info'],
            ]);

            // Enroll student in clubs
            $student->clubs()->attach($studentData['clubs']);
        }

        // Create additional users for different schools
        User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@kis.ug',
            'password' => bcrypt('password123'),
            'school_id' => $school1->id,
            'user_name' => 'teacher1',
            'user_role' => 'teacher',
        ]);

        User::create([
            'name' => 'Teacher Two',
            'email' => 'teacher2@mus.ug',
            'password' => bcrypt('password123'),
            'school_id' => $school2->id,
            'user_name' => 'teacher2',
            'user_role' => 'teacher',
        ]);
    }
}