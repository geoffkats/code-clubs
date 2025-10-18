<x-layouts.app :title="__('Dashboard')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $user = auth()->user();
        $schoolId = $user->school_id ?? null;
        
        // Debug information - temporarily enable to see what's happening
        // Uncomment the line below to debug
        // dd([
        //     'user_id' => $user->id,
        //     'user_name' => $user->name,
        //     'school_id' => $schoolId,
        //     'total_students' => \App\Models\Student::count(),
        //     'total_clubs' => \App\Models\Club::count(),
        //     'total_assessments' => \App\Models\Assessment::count(),
        //     'total_reports' => \App\Models\Report::count(),
        // ]);
        
        // Core metrics - if no school_id, show all data
        $studentsCount = $schoolId ? 
            \App\Models\Student::where('school_id', $schoolId)->count() : 
            \App\Models\Student::count();
        $clubsCount = $schoolId ? 
            \App\Models\Club::where('school_id', $schoolId)->count() : 
            \App\Models\Club::count();
        $assessmentsCount = $schoolId ? 
            \App\Models\Assessment::whereHas('club', fn($q) => $q->where('school_id', $schoolId))->count() : 
            \App\Models\Assessment::count();
        $reportsCount = $schoolId ? 
            \App\Models\Report::whereHas('club', fn($q) => $q->where('school_id', $schoolId))->count() : 
            \App\Models\Report::count();
            
        // If no data found for this school, show all data (fallback for demo purposes)
        if ($studentsCount == 0 && $clubsCount == 0) {
            $studentsCount = \App\Models\Student::count();
            $clubsCount = \App\Models\Club::count();
            $assessmentsCount = \App\Models\Assessment::count();
            $reportsCount = \App\Models\Report::count();
        }
        
        // Time-based metrics
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();
        $attendanceThisWeek = $schoolId ? 
            \App\Models\AttendanceRecord::whereHas('session.club', fn($q) => $q->where('school_id', $schoolId))
                ->whereHas('session', function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->whereBetween('session_date', [$startOfWeek, $endOfWeek]);
                })->count() :
            \App\Models\AttendanceRecord::whereHas('session', function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('session_date', [$startOfWeek, $endOfWeek]);
            })->count();
        
        // 30-day attendance metrics
        $last30 = now()->subDays(30);
        $attendanceTotal30 = $schoolId ? 
            \App\Models\AttendanceRecord::whereHas('session.club', fn($q) => $q->where('school_id', $schoolId))
                ->where('created_at', '>=', $last30)->count() :
            \App\Models\AttendanceRecord::where('created_at', '>=', $last30)->count();
        $attendancePresent30 = $schoolId ? 
            \App\Models\AttendanceRecord::whereHas('session.club', fn($q) => $q->where('school_id', $schoolId))
                ->where('created_at', '>=', $last30)->where('attendance_status', 'present')->count() :
            \App\Models\AttendanceRecord::where('created_at', '>=', $last30)->where('attendance_status', 'present')->count();
        $attendanceRate30 = $attendanceTotal30 > 0 ? round(($attendancePresent30 / $attendanceTotal30) * 100) : 0;
        
        // If no attendance data found for this school, show all attendance data (fallback)
        if ($attendanceThisWeek == 0 && $attendanceTotal30 == 0) {
            $attendanceThisWeek = \App\Models\AttendanceRecord::whereHas('session', function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('session_date', [$startOfWeek, $endOfWeek]);
            })->count();
            $attendanceTotal30 = \App\Models\AttendanceRecord::where('created_at', '>=', $last30)->count();
            $attendancePresent30 = \App\Models\AttendanceRecord::where('created_at', '>=', $last30)->where('attendance_status', 'present')->count();
            $attendanceRate30 = $attendanceTotal30 > 0 ? round(($attendancePresent30 / $attendanceTotal30) * 100) : 0;
        }
        
        // Recent activity data
        $upcomingSessions = $schoolId ? 
            \App\Models\SessionSchedule::whereHas('club', fn($q) => $q->where('school_id', $schoolId))
                ->whereBetween('session_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->with('club')->orderBy('session_date')->take(5)->get() :
            \App\Models\SessionSchedule::whereBetween('session_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->with('club')->orderBy('session_date')->take(5)->get();
        $recentReports = $schoolId ? 
            \App\Models\Report::with(['student', 'club'])
                ->whereHas('club', fn($q) => $q->where('school_id', $schoolId))->latest()->take(5)->get() :
            \App\Models\Report::with(['student', 'club'])->latest()->take(5)->get();
        $recentAssessments = $schoolId ? 
            \App\Models\Assessment::with('club')
                ->whereHas('club', fn($q) => $q->where('school_id', $schoolId))->latest()->take(5)->get() :
            \App\Models\Assessment::with('club')->latest()->take(5)->get();
        $schoolClubs = $schoolId ? 
            \App\Models\Club::where('school_id', $schoolId)->with('students')->orderBy('club_name')->get() :
            \App\Models\Club::with('students')->orderBy('club_name')->get();
        $allSchools = \App\Models\School::orderBy('school_name')->get();
        
        // Get club enrollment data
        $clubEnrollments = $schoolId ? 
            \App\Models\Club::where('school_id', $schoolId)
                ->withCount('students')
                ->orderBy('club_name')
                ->get() :
            \App\Models\Club::withCount('students')
                ->orderBy('club_name')
                ->get();
        
        // Advanced analytics data
        $weeklyAttendanceData = [];
        $monthlyClubPerformance = [];
        $assessmentScoresData = [];
        $studentEngagementData = [];
        
        // Generate weekly attendance data for charts
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayStart = $date->startOfDay()->toDateString();
            $dayEnd = $date->endOfDay()->toDateString();
            
            $totalSessions = \App\Models\AttendanceRecord::whereHas('session.club', fn($q) => $q->where('school_id', $schoolId))
                ->whereHas('session', function ($q) use ($dayStart, $dayEnd) {
                    $q->whereBetween('session_date', [$dayStart, $dayEnd]);
                })->count();
                
            $presentSessions = \App\Models\AttendanceRecord::whereHas('session.club', fn($q) => $q->where('school_id', $schoolId))
                ->whereHas('session', function ($q) use ($dayStart, $dayEnd) {
                    $q->whereBetween('session_date', [$dayStart, $dayEnd]);
                })->where('attendance_status', 'present')->count();
                
            $weeklyAttendanceData[] = [
                'date' => $date->format('M j'),
                'present' => $presentSessions,
                'total' => $totalSessions,
                'rate' => $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100) : 0
            ];
        }
        
        // Club performance data
        foreach ($schoolClubs as $club) {
            $clubAttendance = \App\Models\AttendanceRecord::whereHas('session', fn($q) => $q->where('club_id', $club->id))
                ->where('created_at', '>=', $last30)->count();
            $clubPresent = \App\Models\AttendanceRecord::whereHas('session', fn($q) => $q->where('club_id', $club->id))
                ->where('created_at', '>=', $last30)->where('attendance_status', 'present')->count();
            $clubRate = $clubAttendance > 0 ? round(($clubPresent / $clubAttendance) * 100) : 0;
            
            $monthlyClubPerformance[] = [
                'name' => $club->club_name,
                'level' => $club->club_level,
                'attendance_rate' => $clubRate,
                'total_sessions' => $clubAttendance,
                'students_count' => $club->students()->count()
            ];
        }
        
        // Assessment scores data
        $assessmentScores = \App\Models\AssessmentScore::whereHas('assessment.club', fn($q) => $q->where('school_id', $schoolId))
            ->where('created_at', '>=', $last30)
            ->with('assessment')
            ->get()
            ->groupBy('assessment.assessment_type');
            
        foreach ($assessmentScores as $type => $scores) {
            $avgScore = $scores->avg('score_value') ?? 0;
            $maxScore = $scores->max('score_max_value') ?? 1;
            $percentage = $maxScore > 0 ? round(($avgScore / $maxScore) * 100) : 0;
            
            $assessmentScoresData[] = [
                'type' => ucfirst($type),
                'average_score' => round($avgScore, 1),
                'max_score' => $maxScore,
                'percentage' => $percentage,
                'count' => $scores->count()
            ];
        }
        
        // Student engagement data
        $studentEngagement = \App\Models\Student::where('school_id', $schoolId)
            ->withCount(['clubs', 'assessment_scores'])
            ->with(['assessment_scores' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->get()
            ->map(function($student) {
                $recentScores = $student->assessment_scores->avg('score_value') ?? 0;
                $maxScore = $student->assessment_scores->max('score_max_value') ?? 1;
                $avgPercentage = $maxScore > 0 ? round(($recentScores / $maxScore) * 100) : 0;
                
                return [
                    'name' => ($student->student_first_name ?? '') . ' ' . ($student->student_last_name ?? ''),
                    'grade' => $student->student_grade_level ?? 'N/A',
                    'clubs_count' => $student->clubs_count ?? 0,
                    'assessments_count' => $student->assessment_scores_count ?? 0,
                    'avg_score' => round($recentScores, 1),
                    'score_percentage' => $avgPercentage
                ];
            })
            ->sortByDesc('score_percentage')
            ->take(10)
            ->values();
    @endphp

    <div class="h-full" 
         x-data="{ 
             showStudent: false, 
             showClub: false, 
             showSessions: false,
             showSchool: false,
             activeTab: 'overview',
             searchQuery: '',
             dateRange: '7d',
             theme: localStorage.getItem('theme') || 'light',
             selectedSchoolId: '{{ $schoolId }}',
             selectedClubId: null
         }" 
         x-init="
             $watch('theme', value => {
                 localStorage.setItem('theme', value);
                 if (value === 'dark') {
                     document.documentElement.classList.add('dark');
                 } else {
                     document.documentElement.classList.remove('dark');
                 }
             });
             if (theme === 'dark') document.documentElement.classList.add('dark');
         ">
        
        <!-- Dashboard Content -->
        <div class="p-6">
                <!-- Navigation Tabs -->
                <div class="mt-6 flex space-x-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg w-fit">
                    <button @click="activeTab = 'overview'" 
                            :class="activeTab === 'overview' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        Overview
                    </button>
                    <button @click="activeTab = 'analytics'" 
                            :class="activeTab === 'analytics' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        Analytics
                    </button>
                    <button @click="activeTab = 'reports'" 
                            :class="activeTab === 'reports' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        Reports
                    </button>
                    <button @click="activeTab = 'schools'" 
                            :class="activeTab === 'schools' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        Schools
                    </button>
                    <button @click="activeTab = 'activity'" 
                            :class="activeTab === 'activity' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                        Activity
                    </button>
                </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" class="space-y-8">
                <!-- Key Metrics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Students Card -->
                    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Students</p>
                                    <p class="text-3xl font-bold mt-1">{{ number_format($studentsCount) }}</p>
                                    <p class="text-blue-200 text-xs mt-2">+12% from last month</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-xl">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clubs Card -->
                    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-100 text-sm font-medium">Active Clubs</p>
                                    <p class="text-3xl font-bold mt-1">{{ number_format($clubsCount) }}</p>
                                    <p class="text-emerald-200 text-xs mt-2">+3 new this week</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-xl">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessments Card -->
                    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 via-purple-600 to-pink-600 p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Assessments</p>
                                    <p class="text-3xl font-bold mt-1">{{ number_format($assessmentsCount) }}</p>
                                    <p class="text-purple-200 text-xs mt-2">+8 this month</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-xl">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Card -->
                    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 via-orange-600 to-red-600 p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="relative">
                <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-100 text-sm font-medium">Reports Generated</p>
                                    <p class="text-3xl font-bold mt-1">{{ number_format($reportsCount) }}</p>
                                    <p class="text-orange-200 text-xs mt-2">+15 this week</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-xl">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Overview -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Attendance Overview</h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-2xl font-bold text-emerald-600">{{ $attendanceRate30 }}%</span>
                            <span class="text-sm text-slate-500 dark:text-slate-400">30-day average</span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Present</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($attendancePresent30) }}</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-3 rounded-full transition-all duration-1000" 
                                 style="width: {{ $attendanceRate30 }}%"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Sessions</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($attendanceTotal30) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <button @click="showStudent=true" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-800 dark:to-slate-700 p-6 text-left hover:shadow-xl transition-all duration-300 hover:scale-105 border border-blue-200 dark:border-slate-600">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-blue-500 rounded-xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Add Student</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Enroll student in a club</p>
                            </div>
                        </div>
                    </button>

                    <button @click="showClub=true" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-slate-800 dark:to-slate-700 p-6 text-left hover:shadow-xl transition-all duration-300 hover:scale-105 border border-emerald-200 dark:border-slate-600">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-emerald-500 rounded-xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Create Club</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Set up a new coding club</p>
                            </div>
            </div>
            </button>

                    <button @click="showSchool=true" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-orange-100 dark:from-slate-800 dark:to-slate-700 p-6 text-left hover:shadow-xl transition-all duration-300 hover:scale-105 border border-amber-200 dark:border-slate-600">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-amber-500 rounded-xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Add School</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Create a new school</p>
                            </div>
                        </div>
            </button>

                    <button @click="showSessions=true" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-pink-100 dark:from-slate-800 dark:to-slate-700 p-6 text-left hover:shadow-xl transition-all duration-300 hover:scale-105 border border-purple-200 dark:border-slate-600">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-purple-500 rounded-xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Generate Sessions</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Create 8-week program</p>
                            </div>
                        </div>
            </button>
        </div>

                <!-- Charts and Analytics Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Weekly Attendance Chart -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Weekly Attendance Trend</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">Present</span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>

                    <!-- Club Performance Chart -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Club Performance</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">Attendance Rate</span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="clubPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Upcoming Sessions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Upcoming Sessions -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Upcoming Sessions</h3>
                            <a href="{{ route('clubs.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View all →</a>
                        </div>
                        <div class="space-y-4">
                    @forelse($upcomingSessions as $s)
                                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                            <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">{{ $s->session_week_number }}</span>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $s->club->club_name }}</div>
                                            <div class="text-sm text-slate-600 dark:text-slate-400">Week {{ $s->session_week_number }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($s->session_date)->format('M j') }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($s->session_date)->format('D') }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400">No sessions scheduled in the next 7 days</p>
                                </div>
                            @endforelse
                        </div>
                </div>

                <!-- Club Enrollments -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Club Enrollments</h3>
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-sm rounded-full font-medium">
                            {{ $clubEnrollments->sum('students_count') }} Total Students
                        </span>
                    </div>
                    <div class="space-y-4">
                        @forelse($clubEnrollments as $club)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800 rounded-xl border border-slate-200 dark:border-slate-600">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $club->club_name }}</h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $club->club_level }} • {{ $club->club_duration_weeks }} weeks</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $club->students_count }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">students</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No clubs yet</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-4">Create your first club to start enrolling students</p>
                                <button @click="showClub=true" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                    Create Club
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Reports -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Recent Reports</h3>
                            <a href="{{ route('clubs.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">Generate →</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentReports as $r)
                                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $r->report_name }}</div>
                                            <div class="text-sm text-slate-600 dark:text-slate-400">{{ $r->student->student_first_name }} {{ $r->student->student_last_name }} • {{ $r->club->club_name }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ optional($r->report_generated_at)->format('M j') }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ optional($r->report_generated_at)->format('g:i A') }}</div>
                                    </div>
                        </div>
                    @empty
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400">No recent reports generated</p>
                                </div>
                    @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div x-show="activeTab === 'analytics'" class="space-y-8">
                <!-- Advanced Analytics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Avg. Assessment Score</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">
                                    {{ !empty($assessmentScoresData) ? round(collect($assessmentScoresData)->avg('percentage'), 1) : 0 }}%
                                </p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Students</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ count($studentEngagement) }}</p>
                            </div>
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-xl">
                                <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Completion Rate</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">87%</p>
                            </div>
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-xl">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                </div>
            </div>
        </div>

                <!-- Student Performance Table -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Top Performing Students</h3>
                        <a href="{{ route('students.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View all →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th class="text-left py-3 px-4 font-semibold text-slate-600 dark:text-slate-400">Student</th>
                                    <th class="text-left py-3 px-4 font-semibold text-slate-600 dark:text-slate-400">Grade</th>
                                    <th class="text-left py-3 px-4 font-semibold text-slate-600 dark:text-slate-400">Clubs</th>
                                    <th class="text-left py-3 px-4 font-semibold text-slate-600 dark:text-slate-400">Avg Score</th>
                                    <th class="text-left py-3 px-4 font-semibold text-slate-600 dark:text-slate-400">Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentEngagement ?? [] as $student)
                                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ substr($student['name'] ?? 'U', 0, 1) }}
                                                </div>
                                                <span class="font-medium text-slate-900 dark:text-white">{{ $student['name'] ?? 'Unknown Student' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $student['grade'] ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $student['clubs_count'] ?? 0 }}</td>
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $student['avg_score'] ?? 'N/A' }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-16 bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full" 
                                                         style="width: {{ $student['score_percentage'] ?? 0 }}%"></div>
                                                </div>
                                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $student['score_percentage'] ?? 0 }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-slate-600 dark:text-slate-400">No student data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Reports Tab -->
            <div x-show="activeTab === 'reports'" class="space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Report Generation</h3>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Generate All Reports
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($schoolClubs as $club)
                            <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-xl hover:shadow-lg transition-shadow">
                                <h4 class="font-semibold text-slate-900 dark:text-white">{{ $club->club_name }}</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">{{ $club->club_level }} • {{ $club->students()->count() }} students</p>
                                <button class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors text-sm">
                                    Generate Reports
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Schools Tab -->
            <div x-show="activeTab === 'schools'" class="space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">School Management</h3>
                        <button @click="showSchool=true" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                            Add New School
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($allSchools as $school)
                            <div class="p-6 border border-slate-200 dark:border-slate-700 rounded-xl hover:shadow-lg transition-shadow bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    @if($school->id == $schoolId)
                                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs rounded-full font-medium">Current</span>
                                    @endif
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">{{ $school->school_name }}</h4>
                                @if($school->address)
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">{{ Str::limit($school->address, 50) }}</p>
                                @endif
                                @if($school->contact_email)
                                    <p class="text-sm text-slate-500 dark:text-slate-500 mb-4">{{ $school->contact_email }}</p>
                                @endif
                                <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
                                    <span>{{ $school->clubs()->count() }} clubs</span>
                                    <span>{{ $school->students()->count() }} students</span>
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <button @click="selectedSchoolId = '{{ $school->id }}'; showClub = true" class="flex-1 px-3 py-2 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-800 transition-colors text-sm font-medium">
                                        Create Club
                                    </button>
                                    <a href="{{ route('schools.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors text-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Activity Tab -->
            <div x-show="activeTab === 'activity'" class="space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Recent Activity</h3>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg text-sm">All</button>
                            <button class="px-3 py-1 text-slate-600 dark:text-slate-400 rounded-lg text-sm">Students</button>
                            <button class="px-3 py-1 text-slate-600 dark:text-slate-400 rounded-lg text-sm">Clubs</button>
                            <button class="px-3 py-1 text-slate-600 dark:text-slate-400 rounded-lg text-sm">Reports</button>
                        </div>
                    </div>
                    <div class="space-y-4">
                @forelse($recentAssessments as $a)
                            <div class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ ucfirst($a->assessment_type) }}: {{ $a->assessment_name }}</div>
                                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $a->club->club_name }} • Week {{ $a->assessment_week_number ?? '-' }}</div>
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">{{ $a->created_at->format('M j, g:i A') }}</div>
                    </div>
                @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400">No recent activity</p>
                            </div>
                @endforelse
                    </div>
            </div>
        </div>

        <!-- Modals: Add Student -->
        <div x-show="showStudent" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Add Student to Club</h3>
                    <button @click="showStudent=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="post" action="{{ route('students.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Club</label>
                        <select name="club_id" x-model="selectedClubId" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Choose a club first</option>
                            @foreach($schoolClubs as $club)
                                <option value="{{ $club->id }}">{{ $club->club_name }} ({{ $club->school->school_name }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Student will be enrolled in the selected club</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                    <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">First Name</label>
                            <input name="student_first_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Last Name</label>
                            <input name="student_last_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Grade Level</label>
                        <select name="student_grade_level" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Select Grade</option>
                            <option value="1">Grade 1</option>
                            <option value="2">Grade 2</option>
                            <option value="3">Grade 3</option>
                            <option value="4">Grade 4</option>
                            <option value="5">Grade 5</option>
                            <option value="6">Grade 6</option>
                            <option value="7">Grade 7</option>
                            <option value="8">Grade 8</option>
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Parent Name</label>
                        <input name="student_parent_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Parent Email</label>
                        <input type="email" name="student_parent_email" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Student Enrollment</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Students are automatically enrolled in the selected club and associated with the club's school.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showStudent=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Add Student</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modals: Create Club -->
        <div x-show="showClub" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Create New Club</h3>
                    <button @click="showClub=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="post" action="{{ route('clubs.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">School</label>
                        <select name="school_id" x-model="selectedSchoolId" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Select a school</option>
                            @foreach($allSchools as $school)
                                <option value="{{ $school->id }}" {{ $school->id == $schoolId ? 'selected' : '' }}>
                                    {{ $school->school_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Choose which school this club belongs to</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Club Name</label>
                        <input name="club_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Level</label>
                        <select name="club_level" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Duration (weeks)</label>
                        <input type="number" name="club_duration_weeks" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="8">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Start Date</label>
                        <input type="date" name="club_start_date" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showClub=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">Create Club</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modals: Generate Sessions -->
        <div x-show="showSessions" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Generate Sessions</h3>
                    <button @click="showSessions=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="post" action="#" onsubmit="event.preventDefault(); const id=this.querySelector('select').value; if(id){ const f=document.createElement('form'); f.method='post'; f.action='{{ url('/clubs') }}/'+id+'/sessions/generate'; const c=document.createElement('input'); c.type='hidden'; c.name='_token'; c.value='{{ csrf_token() }}'; f.appendChild(c); document.body.appendChild(f); f.submit(); }">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Club</label>
                        <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach($schoolClubs as $club)
                                <option value="{{ $club->id }}">{{ $club->club_name }} ({{ $club->club_level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">What this does:</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Creates 8 weekly sessions starting from the club's start date.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showSessions=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">Generate Sessions</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modals: Create School -->
        <div x-show="showSchool" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Create New School</h3>
                    <button @click="showSchool=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="post" action="{{ route('schools.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">School Name</label>
                        <input name="school_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Address</label>
                        <textarea name="address" rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">After creating the school:</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">You can create clubs and add students to this school. The school will be available in the school selection dropdown.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showSchool=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">Create School</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Weekly Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart');
            if (attendanceCtx) {
                new Chart(attendanceCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_column($weeklyAttendanceData ?? [], 'date')) !!},
                        datasets: [{
                            label: 'Present',
                            data: {!! json_encode(array_column($weeklyAttendanceData ?? [], 'present')) !!},
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Total',
                            data: {!! json_encode(array_column($weeklyAttendanceData ?? [], 'total')) !!},
                            borderColor: 'rgb(156, 163, 175)',
                            backgroundColor: 'rgba(156, 163, 175, 0.1)',
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Club Performance Chart
            const clubCtx = document.getElementById('clubPerformanceChart');
            if (clubCtx) {
                new Chart(clubCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_column($monthlyClubPerformance ?? [], 'name')) !!},
                        datasets: [{
                            label: 'Attendance Rate (%)',
                            data: {!! json_encode(array_column($monthlyClubPerformance ?? [], 'attendance_rate')) !!},
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderColor: [
                                'rgb(16, 185, 129)',
                                'rgb(59, 130, 246)',
                                'rgb(139, 92, 246)',
                                'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.app>
