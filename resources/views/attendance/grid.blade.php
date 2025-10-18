<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="attendanceGrid()"
         x-init="init()">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('clubs.show', $club->id) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-emerald-900 to-teal-900 dark:from-white dark:via-emerald-100 dark:to-teal-100 bg-clip-text text-transparent">
                                Session Attendance
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $club->club_name }} • Session {{ request('week', 1) }} • {{ $club->school->school_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <button @click="currentWeek = Math.max(1, currentWeek - 1); updateWeek()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-sm font-medium">Session <span x-text="currentWeek"></span></span>
                            <button @click="currentWeek = currentWeek + 1; updateWeek()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <button @click="editingMode = !editingMode" 
                                :class="editingMode ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                                class="px-4 py-2 text-white rounded-lg transition-colors font-medium">
                            <span x-text="editingMode ? 'Exit Edit' : 'Edit Mode'"></span>
                        </button>
                        <button @click="markAllPresent()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mark All Present
                        </button>
                        <button @click="saveAllChanges()" 
                                :disabled="savingInProgress || !unsavedChanges"
                                :class="savingInProgress ? 'bg-gray-400 cursor-not-allowed' : (unsavedChanges ? 'bg-purple-600 hover:bg-purple-700' : 'bg-gray-400 cursor-not-allowed')"
                                class="px-4 py-2 text-white rounded-lg transition-colors font-medium">
                            <svg x-show="!savingInProgress" class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            <svg x-show="savingInProgress" class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span x-text="savingInProgress ? 'Saving...' : (unsavedChanges ? 'Save Changes' : 'All Saved')"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Students</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="students.length"></p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Present Today</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="getPresentCount()"></p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Absent Today</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="getAbsentCount()"></p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Attendance Rate</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="getAttendanceRate() + '%'"></p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Session Information</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Week {{ request('week', 1) }} - {{ $club->club_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Session Date</p>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('M d, Y') : 'TBD' }}</p>
                    </div>
                </div>
            </div>

            <!-- Attendance List -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Session Attendance</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Click on student names to view profiles • Use edit mode to modify attendance</p>
                </div>
                
                <div class="divide-y divide-slate-200 dark:divide-slate-700">
                    <template x-if="students.length === 0">
                        <div class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No Students Enrolled</h3>
                            <p class="text-slate-500 dark:text-slate-400 mb-4">This club doesn't have any students enrolled yet.</p>
                            <a href="{{ route('clubs.show', $club->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Students
                            </a>
                        </div>
                    </template>
                    
                    <template x-for="student in students" :key="student.id">
                        <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold">
                                        <span x-text="getInitials(student.student_first_name, student.student_last_name)"></span>
                                    </div>
                                    <div>
                                        <button @click="viewStudentProfile(student)" class="text-lg font-semibold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            <span x-text="student.student_first_name + ' ' + student.student_last_name"></span>
                                        </button>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Grade <span x-text="student.student_grade_level"></span></p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <!-- Attendance Status Button -->
                                    <button @click="toggleAttendance(student.id)" 
                                            :class="getAttendanceClass(student.id)"
                                            class="px-6 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path x-show="getAttendanceStatus(student.id) === 'present'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            <path x-show="getAttendanceStatus(student.id) === 'absent'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            <path x-show="getAttendanceStatus(student.id) === 'late'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            <path x-show="getAttendanceStatus(student.id) === 'excused'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span x-text="getAttendanceStatus(student.id).charAt(0).toUpperCase() + getAttendanceStatus(student.id).slice(1)"></span>
                                    </button>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-2">
                                        <button @click="viewStudentProfile(student)" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button @click="editStudentAttendance(student)" class="p-2 text-slate-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Student Profile Modal -->
        <div x-show="showStudentModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-4xl rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Student Profile</h3>
                    <button @click="showStudentModal=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div x-show="selectedStudent" class="space-y-6">
                    <!-- Student Header -->
                    <div class="flex items-center space-x-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold">
                            <span x-text="getInitials(selectedStudent?.student_first_name, selectedStudent?.student_last_name)"></span>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-slate-900 dark:text-white" x-text="selectedStudent?.student_first_name + ' ' + selectedStudent?.student_last_name"></h4>
                            <p class="text-slate-600 dark:text-slate-400">Grade <span x-text="selectedStudent?.student_grade_level"></span></p>
                            <p class="text-slate-600 dark:text-slate-400">Student ID: <span x-text="selectedStudent?.id"></span></p>
                        </div>
                    </div>

                    <!-- Current Session Attendance -->
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                        <h5 class="font-semibold text-slate-900 dark:text-white mb-3">Current Session Attendance</h5>
                        <div class="flex items-center space-x-4">
                            <button @click="toggleAttendance(selectedStudent.id)" 
                                    :class="getAttendanceClass(selectedStudent.id)"
                                    class="px-6 py-3 rounded-lg font-medium transition-colors flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path x-show="getAttendanceStatus(selectedStudent.id) === 'present'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path x-show="getAttendanceStatus(selectedStudent.id) === 'absent'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    <path x-show="getAttendanceStatus(selectedStudent.id) === 'late'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path x-show="getAttendanceStatus(selectedStudent.id) === 'excused'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span x-text="getAttendanceStatus(selectedStudent.id).charAt(0).toUpperCase() + getAttendanceStatus(selectedStudent.id).slice(1)"></span>
                            </button>
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                <p>Session {{ request('week', 1) }}</p>
                                <p x-text="getAttendanceStatus(selectedStudent.id) === 'present' ? 'Attended' : getAttendanceStatus(selectedStudent.id) === 'absent' ? 'Missed session' : getAttendanceStatus(selectedStudent.id) === 'late' ? 'Arrived late' : 'Excused absence'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Student Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                            <h5 class="font-semibold text-slate-900 dark:text-white mb-3">Personal Information</h5>
                            <div class="space-y-2">
                                <p><span class="font-medium">Date of Birth:</span> <span x-text="selectedStudent?.student_date_of_birth || 'Not provided'"></span></p>
                                <p><span class="font-medium">Gender:</span> <span x-text="selectedStudent?.student_gender || 'Not provided'"></span></p>
                                <p><span class="font-medium">Address:</span> <span x-text="selectedStudent?.student_address || 'Not provided'"></span></p>
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                            <h5 class="font-semibold text-slate-900 dark:text-white mb-3">Contact Information</h5>
                            <div class="space-y-2">
                                <p><span class="font-medium">Phone:</span> <span x-text="selectedStudent?.student_phone || 'Not provided'"></span></p>
                                <p><span class="font-medium">Email:</span> <span x-text="selectedStudent?.student_email || 'Not provided'"></span></p>
                                <p><span class="font-medium">Emergency Contact:</span> <span x-text="selectedStudent?.emergency_contact_name || 'Not provided'"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function attendanceGrid() {
            return {
                currentWeek: {{ request('week', 1) }},
                clubId: {{ $club->id }},
                students: @json($club->students ?? []),
                attendance: {},
                showStudentModal: false,
                selectedStudent: null,
                editingMode: false,
                sessionId: {{ $session->id }},
                savingInProgress: false,
                unsavedChanges: false,
                
                init() {
                    console.log('Students loaded:', this.students.length, this.students);
                    
                    // Initialize attendance data structure
                    this.attendance = {};
                    
                    // Ensure all students have default attendance status
                    if (this.students && Array.isArray(this.students)) {
                        this.students.forEach(student => {
                            this.attendance[student.id] = 'present'; // Default to present
                        });
                    }
                    
                    // Load existing attendance records from backend if available
                    @if(isset($attendanceRecords) && $attendanceRecords->count() > 0)
                        @foreach($attendanceRecords as $studentId => $record)
                            if (this.attendance[{{ $studentId }}] !== undefined) {
                                this.attendance[{{ $studentId }}] = '{{ $record->attendance_status ?? 'present' }}';
                            }
                        @endforeach
                    @endif
                    
                    console.log('Attendance initialized:', this.attendance);
                },
                
                // Global function for week navigation
                updateWeek() {
                    window.location.href = `{{ route('attendance.grid', $club->id) }}?week=${this.currentWeek}`;
                },
                
                // Helper methods
                getInitials(firstName, lastName) {
                    return (firstName?.[0] || '') + (lastName?.[0] || '');
                },
                
                viewStudentProfile(student) {
                    this.selectedStudent = student;
                    this.showStudentModal = true;
                },
                
                editStudentAttendance(student) {
                    // Open student profile modal for detailed editing
                    this.selectedStudent = student;
                    this.showStudentModal = true;
                    console.log('Opening detailed edit for student:', student);
                },
                
                toggleAttendance(studentId) {
                    if (!this.editingMode) {
                        console.log('Edit mode is not enabled');
                        return;
                    }
                    
                    const currentStatus = this.getAttendanceStatus(studentId);
                    const statuses = ['present', 'absent', 'late', 'excused'];
                    const currentIndex = statuses.indexOf(currentStatus);
                    const nextIndex = (currentIndex + 1) % statuses.length;
                    const newStatus = statuses[nextIndex];
                    
                    this.attendance[studentId] = newStatus;
                    this.unsavedChanges = true; // Mark that there are unsaved changes
                    
                    console.log(`Updated ${studentId} to ${newStatus}`);
                    
                    // Save to database (automatic save)
                    this.saveAttendance(studentId, newStatus);
                },
                
                markAllPresent() {
                    if (!this.editingMode) {
                        console.log('Edit mode is not enabled');
                        return;
                    }
                    
                    if (this.students && Array.isArray(this.students)) {
                        this.students.forEach(student => {
                            this.attendance[student.id] = 'present';
                        });
                    }
                    
                    console.log('Marked all students as present');
                },
                
                saveAllChanges() {
                    if (!this.students || !Array.isArray(this.students)) {
                        console.log('No students to save');
                        return;
                    }
                    
                    this.savingInProgress = true;
                    console.log('Saving all attendance changes...');
                    let savePromises = [];
                    
                    this.students.forEach(student => {
                        const status = this.attendance[student.id] || 'present';
                        savePromises.push(this.saveAttendancePromise(student.id, status));
                    });
                    
                    Promise.all(savePromises)
                        .then(results => {
                            console.log('All attendance saved successfully:', results);
                            this.savingInProgress = false;
                            this.unsavedChanges = false;
                            // Show success message (you could add a toast notification here)
                            alert('All attendance changes saved successfully!');
                        })
                        .catch(error => {
                            console.error('Error saving some attendance records:', error);
                            this.savingInProgress = false;
                            alert('Some attendance records failed to save. Please check the console for details.');
                        });
                },
                
                saveAttendancePromise(studentId, status) {
                    return fetch(`/attendance/update/${this.clubId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            student_id: studentId,
                            session_id: this.sessionId,
                            status: status
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            console.log(`Attendance saved successfully for student ${studentId}: ${status}`);
                            return { studentId, status, success: true };
                        } else {
                            throw new Error(`Server returned success: false for student ${studentId}`);
                        }
                    })
                    .catch(error => {
                        console.error(`Error saving attendance for student ${studentId}:`, error);
                        return { studentId, status, success: false, error: error.message };
                    });
                },
                
                saveAttendance(studentId, status) {
                    this.saveAttendancePromise(studentId, status)
                        .then(result => {
                            if (result.success) {
                                console.log(`Auto-saved: Student ${studentId} -> ${status}`);
                                // Check if all changes are saved
                                this.checkIfAllSaved();
                            } else {
                                console.error(`Auto-save failed: Student ${studentId} -> ${status}`, result.error);
                                // You could show a toast notification here for failed auto-saves
                            }
                        });
                },
                
                checkIfAllSaved() {
                    // This is a simple check - in a more complex app you might track individual save states
                    // For now, we'll just clear unsaved changes after a short delay
                    setTimeout(() => {
                        this.unsavedChanges = false;
                    }, 1000);
                },
                
                getAttendanceStatus(studentId) {
                    return this.attendance[studentId] || 'present';
                },
                
                getAttendanceClass(studentId) {
                    const status = this.getAttendanceStatus(studentId);
                    const classes = {
                        present: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700',
                        absent: 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-700',
                        late: 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-700',
                        excused: 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700'
                    };
                    return classes[status] || classes.present;
                },
                
                getPresentCount() {
                    if (!this.students || !Array.isArray(this.students)) {
                        return 0;
                    }
                    return this.students.filter(student => this.getAttendanceStatus(student.id) === 'present').length;
                },
                
                getAbsentCount() {
                    if (!this.students || !Array.isArray(this.students)) {
                        return 0;
                    }
                    return this.students.filter(student => this.getAttendanceStatus(student.id) === 'absent').length;
                },
                
                getAttendanceRate() {
                    if (!this.students || !Array.isArray(this.students) || this.students.length === 0) {
                        return 0;
                    }
                    return Math.round((this.getPresentCount() / this.students.length) * 100);
                }
            }
        }
    </script>
</x-layouts.app>