<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             currentWeek: {{ request('week', 1) }},
             clubId: {{ $club->id }},
             students: @json($club->students),
            attendance: @json($attendanceRecords->mapWithKeys(function($r){ return [$r->student_id => $r->attendance_status]; })),
             showStudentModal: false,
             selectedStudent: null,
             showBulkEdit: false,
             editingMode: false,
             sessionId: {{ $session->id }}
         }">
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
                                Attendance Grid
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $club->club_name }} • Week {{ request('week', 1) }} • {{ $club->school->school_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <button @click="currentWeek = Math.max(1, currentWeek - 1); updateWeek()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-sm font-medium">Week <span x-text="currentWeek"></span></span>
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
                        <button @click="showBulkEdit = true" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Bulk Edit
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

            <!-- Attendance Grid -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Week <span x-text="currentWeek"></span> Attendance</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Click on student names to view profiles • Use edit mode to modify attendance</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-slate-900 dark:text-white">Student</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Mon</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Tue</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Wed</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Thu</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Fri</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Week Total</th>
                                <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <template x-if="students.length === 0">
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
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
                                    </td>
                                </tr>
                            </template>
                            <template x-for="student in students" :key="student.id">
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-sm font-medium">
                                                <span x-text="getInitials(student.student_first_name, student.student_last_name)"></span>
                                            </div>
                                            <div>
                                                <button @click="viewStudentProfile(student)" class="font-medium text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                    <span x-text="student.student_first_name + ' ' + student.student_last_name"></span>
                                                </button>
                                                <p class="text-sm text-slate-500 dark:text-slate-400">Grade <span x-text="student.student_grade_level"></span></p>
                                            </div>
                                        </div>
                                    </td>
                                    <template x-for="day in ['mon', 'tue', 'wed', 'thu', 'fri']" :key="day">
                                        <td class="px-6 py-4 text-center">
                                            <button @click="toggleAttendance(student.id, day)" 
                                                    :class="getAttendanceClass(student.id, day)"
                                                    class="w-8 h-8 rounded-lg transition-colors">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path x-show="getAttendanceStatus(student.id, day) === 'present'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    <path x-show="getAttendanceStatus(student.id, day) === 'absent'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    <path x-show="getAttendanceStatus(student.id, day) === 'late'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </template>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-lg text-sm font-medium" 
                                              :class="getWeekTotalClass(student.id)"
                                              x-text="getWeekTotal(student.id) + '/5'"></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button @click="viewStudentProfile(student)" class="p-1 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button @click="editStudentAttendance(student)" class="p-1 text-slate-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
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

                    <!-- Attendance Summary -->
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                        <h5 class="font-semibold text-slate-900 dark:text-white mb-3">Attendance Summary</h5>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-emerald-600" x-text="getStudentAttendanceRate(selectedStudent?.id) + '%'"></p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Overall Rate</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600" x-text="getStudentPresentDays(selectedStudent?.id)"></p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Days Present</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-red-600" x-text="getStudentAbsentDays(selectedStudent?.id)"></p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Days Absent</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Edit Modal -->
        <div x-show="showBulkEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Bulk Attendance Edit</h3>
                    <button @click="showBulkEdit=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="saveBulkAttendance()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Day</label>
                        <select x-model="bulkDay" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="mon">Monday</option>
                            <option value="tue">Tuesday</option>
                            <option value="wed">Wednesday</option>
                            <option value="thu">Thursday</option>
                            <option value="fri">Friday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Set Status for All Students</label>
                        <select x-model="bulkStatus" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <button type="button" @click="showBulkEdit=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Apply to All
                        </button>
                    </div>
			</form>
		</div>
        </div>
	</div>

    <script>
        function updateWeek() {
            window.location.href = `{{ route('attendance.grid', $club->id) }}?week=${this.currentWeek}`;
        }

        function getInitials(firstName, lastName) {
            return (firstName?.[0] || '') + (lastName?.[0] || '');
        }

        function viewStudentProfile(student) {
            this.selectedStudent = student;
            this.showStudentModal = true;
        }

        function editStudentAttendance(student) {
            // Implementation for editing individual student attendance
            console.log('Edit attendance for student:', student);
        }

        function toggleAttendance(studentId, day) {
            if (!this.editingMode) return;
            
            const currentStatus = this.getAttendanceStatus(studentId, day);
            const statuses = ['present', 'absent', 'late', 'excused'];
            const currentIndex = statuses.indexOf(currentStatus);
            const nextIndex = (currentIndex + 1) % statuses.length;
            const newStatus = statuses[nextIndex];
            
            if (!this.attendance[studentId]) {
                this.attendance[studentId] = {};
            }
            this.attendance[studentId][day] = newStatus;
            
            // Save to database
            this.saveAttendance(studentId, newStatus);
        }
        
        function saveAttendance(studentId, status) {
            fetch(`/attendance/update/${this.clubId}`, {
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Attendance saved successfully');
                }
            })
            .catch(error => {
                console.error('Error saving attendance:', error);
            });
        }

        function getAttendanceStatus(studentId, day) {
            return this.attendance[studentId]?.[day] || 'present';
        }

        function getAttendanceClass(studentId, day) {
            const status = this.getAttendanceStatus(studentId, day);
            const classes = {
                present: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-900/50',
                absent: 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50',
                late: 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/50',
                excused: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50'
            };
            return classes[status] || classes.present;
        }

        function getWeekTotal(studentId) {
            const days = ['mon', 'tue', 'wed', 'thu', 'fri'];
            return days.filter(day => this.getAttendanceStatus(studentId, day) === 'present').length;
        }

        function getWeekTotalClass(studentId) {
            const total = this.getWeekTotal(studentId);
            if (total >= 4) return 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
            if (total >= 3) return 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300';
            return 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300';
        }

        function getPresentCount() {
            return this.students.filter(student => {
                const days = ['mon', 'tue', 'wed', 'thu', 'fri'];
                return days.some(day => this.getAttendanceStatus(student.id, day) === 'present');
            }).length;
        }

        function getAbsentCount() {
            return this.students.length - this.getPresentCount();
        }

        function getAttendanceRate() {
            if (this.students.length === 0) return 0;
            return Math.round((this.getPresentCount() / this.students.length) * 100);
        }

        function getStudentAttendanceRate(studentId) {
            // This would calculate the overall attendance rate for the student
            return Math.floor(Math.random() * 40) + 60; // Mock data
        }

        function getStudentPresentDays(studentId) {
            // This would calculate the total present days for the student
            return Math.floor(Math.random() * 20) + 15; // Mock data
        }

        function getStudentAbsentDays(studentId) {
            // This would calculate the total absent days for the student
            return Math.floor(Math.random() * 5) + 1; // Mock data
        }

        function saveBulkAttendance() {
            // Implementation for saving bulk attendance
            console.log('Saving bulk attendance:', this.bulkDay, this.bulkStatus);
            this.showBulkEdit = false;
        }
    </script>
</x-layouts.app>