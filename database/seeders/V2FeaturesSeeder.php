<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Club;
use App\Models\School;
use App\Models\ClubSession;
use App\Models\SessionProof;
use App\Models\SessionFeedback;
use App\Models\FeedbackTemplate;
use App\Models\LessonNote;
use App\Models\Report;
use App\Models\Student;
use Carbon\Carbon;

class V2FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding V2.5.0 Features...');

        // Create additional users for v2.5.0
        $this->createUsers();
        
        // Create additional clubs and sessions
        $this->createClubsAndSessions();
        
        // Create session proofs (teacher submissions)
        $this->createSessionProofs();
        
        // Create session feedbacks
        $this->createSessionFeedbacks();
        
        // Create feedback templates
        $this->createFeedbackTemplates();
        
        // Create lesson notes/resources
        $this->createLessonNotes();
        
        // Create additional reports
        $this->createReports();
        
        // Create additional students
        $this->createStudents();

        $this->command->info('✅ V2.5.0 Features seeded successfully!');
    }

    private function createUsers()
    {
        $this->command->info('👥 Creating additional users...');

        // Create facilitators
        $facilitators = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@codeclub.com',
                'user_role' => 'facilitator',
                'password' => Hash::make('password'),
                'school_id' => 1,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@codeclub.com',
                'user_role' => 'facilitator',
                'password' => Hash::make('password'),
                'school_id' => 1,
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@codeclub.com',
                'user_role' => 'facilitator',
                'password' => Hash::make('password'),
                'school_id' => 2,
            ],
        ];

        foreach ($facilitators as $facilitator) {
            User::firstOrCreate(
                ['email' => $facilitator['email']],
                $facilitator
            );
        }

        // Create teachers
        $teachers = [
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@codeclub.com',
                'user_role' => 'teacher',
                'password' => Hash::make('password'),
                'school_id' => 1,
            ],
            [
                'name' => 'Lisa Thompson',
                'email' => 'lisa.thompson@codeclub.com',
                'user_role' => 'teacher',
                'password' => Hash::make('password'),
                'school_id' => 1,
            ],
            [
                'name' => 'James Brown',
                'email' => 'james.brown@codeclub.com',
                'user_role' => 'teacher',
                'password' => Hash::make('password'),
                'school_id' => 2,
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@codeclub.com',
                'user_role' => 'teacher',
                'password' => Hash::make('password'),
                'school_id' => 2,
            ],
            [
                'name' => 'Alex Kim',
                'email' => 'alex.kim@codeclub.com',
                'user_role' => 'teacher',
                'password' => Hash::make('password'),
                'school_id' => 1,
            ],
        ];

        foreach ($teachers as $teacher) {
            User::firstOrCreate(
                ['email' => $teacher['email']],
                $teacher
            );
        }

        $this->command->info('✅ Users created: ' . (count($facilitators) + count($teachers)));
    }

    private function createClubsAndSessions()
    {
        $this->command->info('🏫 Creating additional clubs and sessions...');

        // Create additional clubs
        $clubs = [
            [
                'club_name' => 'Advanced Python Programming',
                'club_level' => 'Advanced',
                'club_duration_weeks' => 12,
                'club_start_date' => Carbon::now()->subWeeks(4),
                'school_id' => 1,
                'facilitator_id' => 2, // Sarah Johnson
            ],
            [
                'club_name' => 'Web Development Bootcamp',
                'club_level' => 'Intermediate',
                'club_duration_weeks' => 16,
                'club_start_date' => Carbon::now()->subWeeks(6),
                'school_id' => 1,
                'facilitator_id' => 3, // Michael Chen
            ],
            [
                'club_name' => 'Mobile App Development',
                'club_level' => 'Intermediate',
                'club_duration_weeks' => 14,
                'club_start_date' => Carbon::now()->subWeeks(2),
                'school_id' => 2,
                'facilitator_id' => 4, // Emily Rodriguez
            ],
            [
                'club_name' => 'Data Science & AI',
                'club_level' => 'Advanced',
                'club_duration_weeks' => 20,
                'club_start_date' => Carbon::now()->subWeeks(8),
                'school_id' => 2,
                'facilitator_id' => 4, // Emily Rodriguez
            ],
            [
                'club_name' => 'Game Development',
                'club_level' => 'Beginner',
                'club_duration_weeks' => 10,
                'club_start_date' => Carbon::now()->subWeeks(1),
                'school_id' => 1,
                'facilitator_id' => 2, // Sarah Johnson
            ],
        ];

        $createdClubs = [];
        foreach ($clubs as $club) {
            $createdClubs[] = Club::firstOrCreate(
                ['club_name' => $club['club_name']],
                $club
            );
        }

        $this->command->info('✅ Clubs created: ' . count($createdClubs));
    }

    private function createSessionProofs()
    {
        $this->command->info('📸 Creating session proofs...');

        $sessions = ClubSession::all();
        $teachers = User::where('user_role', 'teacher')->get();

        $proofTypes = ['photo', 'video'];
        $statuses = ['pending', 'approved', 'rejected', 'under_review'];
        $descriptions = [
            'Students working on their coding projects during the session.',
            'Group presentation of the final project results.',
            'Hands-on coding workshop with live demonstrations.',
            'Students debugging their code with teacher assistance.',
            'Interactive learning session with student engagement.',
            'Code review session with peer feedback.',
            'Final project showcase with student presentations.',
            'Algorithm implementation workshop.',
            'Database design and implementation session.',
            'API development and testing workshop.',
        ];

        foreach ($sessions as $session) {
            // Create 1-3 proofs per session
            $proofCount = rand(1, 3);
            
            for ($i = 0; $i < $proofCount; $i++) {
                $proofType = $proofTypes[array_rand($proofTypes)];
                $status = $statuses[array_rand($statuses)];
                $description = $descriptions[array_rand($descriptions)];
                
                // Create mock file paths
                $fileExtensions = $proofType === 'photo' ? ['jpg', 'png', 'jpeg'] : ['mp4', 'mov', 'avi'];
                $extension = $fileExtensions[array_rand($fileExtensions)];
                $fileName = 'proof_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
                
                SessionProof::create([
                    'session_id' => $session->id,
                    'proof_url' => 'proofs/' . $fileName,
                    'mime_type' => $proofType === 'photo' ? 'image/jpeg' : 'video/mp4',
                    'proof_type' => $proofType,
                    'file_size' => rand(500000, 10000000), // 500KB to 10MB
                    'uploaded_by' => $teachers->random()->id,
                    'processing_status' => 'completed',
                    'status' => $status,
                    'description' => $description,
                    'admin_comments' => $status === 'approved' ? 'Great work! Students are clearly engaged.' : 
                                      ($status === 'rejected' ? 'Please provide clearer evidence of student participation.' : null),
                    'reviewed_by' => $status !== 'pending' ? 1 : null, // Admin user ID 1
                    'reviewed_at' => $status !== 'pending' ? Carbon::now()->subDays(rand(1, 7)) : null,
                    'rejection_reason' => $status === 'rejected' ? 'Image quality is too low to verify student participation.' : null,
                ]);
            }
        }

        $this->command->info('✅ Session proofs created: ' . SessionProof::count());
    }

    private function createSessionFeedbacks()
    {
        $this->command->info('⭐ Creating session feedbacks...');

        $sessions = ClubSession::all();
        $facilitators = User::where('user_role', 'facilitator')->get();
        $teachers = User::where('user_role', 'teacher')->get();

        $feedbackTypes = ['positive', 'constructive', 'critical', 'mixed'];
        $statuses = ['draft', 'submitted', 'reviewed', 'actioned'];
        
        $contents = [
            'Excellent session! The teacher demonstrated great engagement with students and the content was well-structured.',
            'Good session overall, but could benefit from more interactive activities to keep students engaged.',
            'The teacher needs to improve time management and ensure all students participate equally.',
            'Mixed feedback: Great content delivery but student engagement could be better.',
            'Outstanding teaching! Students were highly engaged and learned effectively.',
            'Session was okay but the teacher could provide more individual attention to struggling students.',
            'The teacher showed excellent preparation and the session flowed smoothly.',
            'Good effort, but the teaching methods could be more diverse to cater to different learning styles.',
        ];

        $suggestions = [
            'Consider adding more hands-on coding exercises.',
            'Try to involve quieter students more in discussions.',
            'Use visual aids to explain complex concepts.',
            'Break down large tasks into smaller, manageable steps.',
            'Provide more real-world examples to make concepts relatable.',
            'Encourage peer-to-peer learning and collaboration.',
            'Use interactive tools and platforms to enhance engagement.',
            'Provide more frequent feedback to students during the session.',
        ];

        foreach ($sessions as $session) {
            // Create feedback for 60% of sessions
            if (rand(1, 10) <= 6) {
                $facilitator = $facilitators->random();
                $teacher = $teachers->random();
                
                SessionFeedback::create([
                    'session_id' => $session->id,
                    'facilitator_id' => $facilitator->id,
                    'teacher_id' => $teacher->id,
                    'club_id' => $session->club_id,
                    'content' => $contents[array_rand($contents)],
                    'suggestions' => json_encode([$suggestions[array_rand($suggestions)]]),
                    'feedback_type' => $feedbackTypes[array_rand($feedbackTypes)],
                    'content_delivery_rating' => rand(3, 5),
                    'student_engagement_rating' => rand(3, 5),
                    'session_management_rating' => rand(3, 5),
                    'preparation_rating' => rand(3, 5),
                    'overall_rating' => rand(3, 5),
                    'status' => $statuses[array_rand($statuses)],
                    'submitted_at' => Carbon::now()->subDays(rand(1, 14)),
                    'reviewed_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 7)) : null,
                ]);
            }
        }

        $this->command->info('✅ Session feedbacks created: ' . SessionFeedback::count());
    }

    private function createFeedbackTemplates()
    {
        $this->command->info('📋 Creating feedback templates...');

        $templates = [
            [
                'name' => 'Standard Teaching Evaluation',
                'description' => 'Comprehensive evaluation template for all teaching sessions',
                'template_type' => 'standard',
                'is_active' => true,
                'rating_criteria' => json_encode([
                    'content_delivery' => 'How well was the content presented and explained?',
                    'student_engagement' => 'How well did the teacher engage and interact with students?',
                    'session_management' => 'How well was the session organized and managed?',
                    'preparation' => 'How well was the teacher prepared for the session?',
                ]),
                'feedback_questions' => json_encode([
                    'What went well in this session?',
                    'What areas need improvement?',
                    'How can the teacher enhance student engagement?',
                    'Any specific recommendations for the teacher?',
                ]),
                'created_by' => 1,
            ],
            [
                'name' => 'Coding Workshop Template',
                'description' => 'Specialized template for coding and programming sessions',
                'template_type' => 'custom',
                'is_active' => true,
                'rating_criteria' => json_encode([
                    'code_quality' => 'How well did students understand coding concepts?',
                    'hands_on_practice' => 'How effective were the hands-on coding exercises?',
                    'problem_solving' => 'How well did the teacher guide problem-solving?',
                    'technical_support' => 'How well did the teacher provide technical assistance?',
                ]),
                'feedback_questions' => json_encode([
                    'How well did students grasp the coding concepts?',
                    'Were the coding exercises appropriate for the skill level?',
                    'How effective was the technical support provided?',
                    'What improvements could be made to the coding curriculum?',
                ]),
                'created_by' => 1,
            ],
        ];

        foreach ($templates as $template) {
            FeedbackTemplate::create($template);
        }

        $this->command->info('✅ Feedback templates created: ' . count($templates));
    }

    private function createLessonNotes()
    {
        $this->command->info('📚 Creating lesson notes/resources...');

        $clubs = Club::all();
        $attachmentTypes = ['video', 'document', 'image', 'link', 'audio', 'code', 'quiz', 'assignment', 'other'];
        $visibilityOptions = ['all', 'teachers_only', 'students_only', 'private'];

        $resources = [
            [
                'title' => 'Python Fundamentals - Variables and Data Types',
                'description' => 'Comprehensive guide to Python variables, data types, and basic operations.',
                'attachment_type' => 'document',
                'visibility' => 'all',
                'tags' => 'python, fundamentals, variables, data-types',
            ],
            [
                'title' => 'Web Development with React - Component Lifecycle',
                'description' => 'Video tutorial explaining React component lifecycle methods and hooks.',
                'attachment_type' => 'video',
                'visibility' => 'all',
                'tags' => 'react, web-development, components, lifecycle',
                'video_url' => 'https://www.youtube.com/watch?v=example1',
            ],
            [
                'title' => 'Database Design Best Practices',
                'description' => 'PDF guide covering database normalization, relationships, and optimization.',
                'attachment_type' => 'document',
                'visibility' => 'teachers_only',
                'tags' => 'database, design, normalization, sql',
            ],
            [
                'title' => 'Git and GitHub Workflow',
                'description' => 'Step-by-step guide to version control with Git and collaborative development.',
                'attachment_type' => 'link',
                'visibility' => 'all',
                'tags' => 'git, github, version-control, collaboration',
                'external_url' => 'https://github.com/example/git-workflow',
                'link_title' => 'Git Workflow Guide',
            ],
            [
                'title' => 'Mobile App UI/UX Design Principles',
                'description' => 'Audio lecture on mobile app design principles and user experience.',
                'attachment_type' => 'audio',
                'visibility' => 'all',
                'tags' => 'mobile, ui, ux, design, principles',
                'audio_url' => 'https://example.com/audio/design-principles.mp3',
            ],
            [
                'title' => 'Machine Learning Project Repository',
                'description' => 'Complete machine learning project with datasets and code examples.',
                'attachment_type' => 'code',
                'visibility' => 'students_only',
                'tags' => 'machine-learning, python, data-science, ai',
                'code_url' => 'https://github.com/example/ml-project',
                'code_branch' => 'main',
            ],
            [
                'title' => 'JavaScript Quiz - ES6 Features',
                'description' => 'Interactive quiz testing knowledge of ES6 JavaScript features.',
                'attachment_type' => 'quiz',
                'visibility' => 'all',
                'tags' => 'javascript, es6, quiz, assessment',
            ],
            [
                'title' => 'Final Project Assignment - E-commerce Website',
                'description' => 'Comprehensive assignment to build a full-stack e-commerce website.',
                'attachment_type' => 'assignment',
                'visibility' => 'all',
                'tags' => 'e-commerce, full-stack, project, assignment',
            ],
        ];

        foreach ($clubs as $club) {
            $resourceCount = rand(3, 6);
            $selectedResources = array_slice($resources, 0, $resourceCount);
            
            foreach ($selectedResources as $resource) {
                $resource['club_id'] = $club->id;
                $resource['created_by'] = rand(1, 5); // Random user ID
                
                // Create mock file paths for file-based resources
                if (in_array($resource['attachment_type'], ['document', 'image', 'quiz', 'assignment', 'other'])) {
                    $extensions = [
                        'document' => 'pdf',
                        'image' => 'jpg',
                        'quiz' => 'pdf',
                        'assignment' => 'docx',
                        'other' => 'txt',
                    ];
                    $extension = $extensions[$resource['attachment_type']];
                    $resource['attachment_url'] = 'resources/' . strtolower(str_replace(' ', '_', $resource['title'])) . '.' . $extension;
                    $resource['mime_type'] = $resource['attachment_type'] === 'document' ? 'application/pdf' : 'image/jpeg';
                } else {
                    $resource['attachment_url'] = $resource['video_url'] ?? $resource['external_url'] ?? $resource['audio_url'] ?? $resource['code_url'] ?? '';
                    $resource['mime_type'] = 'text/html';
                }
                
                LessonNote::create($resource);
            }
        }

        $this->command->info('✅ Lesson notes created: ' . LessonNote::count());
    }

    private function createReports()
    {
        $this->command->info('📊 Creating additional reports...');

        $clubs = Club::all();
        $students = Student::all();

        foreach ($clubs as $club) {
            $clubStudents = $students->where('school_id', $club->school_id)->take(rand(3, 8));
            
            foreach ($clubStudents as $student) {
                Report::firstOrCreate([
                    'club_id' => $club->id,
                    'student_id' => $student->id,
                ], [
                    'report_name' => 'Progress Report - ' . $student->student_first_name . ' ' . $student->student_last_name,
                    'report_summary_text' => 'This student has shown excellent progress in ' . $club->club_name . ' with strong problem-solving skills and creativity.',
                    'report_overall_score' => rand(75, 95),
                    'report_generated_at' => Carbon::now()->subDays(rand(1, 30)),
                    'student_initials' => substr($student->student_first_name, 0, 1) . substr($student->student_last_name, 0, 1),
                    'problem_solving_score' => rand(70, 95),
                    'creativity_score' => rand(70, 95),
                    'collaboration_score' => rand(70, 95),
                    'persistence_score' => rand(70, 95),
                    'scratch_project_ids' => json_encode([rand(1000, 9999), rand(1000, 9999)]),
                    'favorite_concept' => 'Variables and Loops',
                    'challenges_overcome' => 'Debugging complex algorithms and working with arrays.',
                    'special_achievements' => 'Completed advanced coding challenges and helped classmates.',
                    'areas_for_growth' => 'Continue practicing with more complex data structures.',
                ]);
            }
        }

        $this->command->info('✅ Reports created: ' . Report::count());
    }

    private function createStudents()
    {
        $this->command->info('👨‍🎓 Creating additional students...');

        $schools = School::all();
        $firstNames = ['Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'William', 'Sophia', 'James', 'Isabella', 'Benjamin', 'Mia', 'Lucas', 'Charlotte', 'Henry', 'Amelia', 'Alexander', 'Harper', 'Mason', 'Evelyn', 'Michael'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];

        foreach ($schools as $school) {
            $studentCount = rand(15, 25);
            
            for ($i = 0; $i < $studentCount; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $email = strtolower($firstName . '.' . $lastName . rand(1, 99) . '@student.' . strtolower($school->school_name) . '.edu');
                
                Student::firstOrCreate([
                    'email' => $email,
                ], [
                    'student_first_name' => $firstName,
                    'student_last_name' => $lastName,
                    'password' => Hash::make('password'),
                    'student_parent_phone' => '+1-' . rand(200, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                    'school_id' => $school->id,
                    'student_grade_level' => rand(6, 12),
                    'student_parent_name' => $firstName . ' ' . $lastName . ' Parent',
                    'student_parent_email' => 'parent.' . $email,
                ]);
            }
        }

        $this->command->info('✅ Students created: ' . Student::count());
    }
}