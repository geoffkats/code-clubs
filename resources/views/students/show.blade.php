<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <a href="{{ route('students.index') }}" 
                       class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                            Student Details
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">View student information and enrollment</p>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <!-- Student Header -->
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($student->student_first_name, 0, 1) }}{{ substr($student->student_last_name, 0, 1) }}
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                                    {{ $student->student_first_name }} {{ $student->student_last_name }}
                                </h2>
                                <p class="text-slate-600 dark:text-slate-400">{{ $student->student_grade_level }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('students.edit', $student) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Edit Student
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Student Details -->
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Personal Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Full Name</label>
                                    <p class="text-slate-900 dark:text-white">{{ $student->student_first_name }} {{ $student->student_last_name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
                                    <p class="text-slate-900 dark:text-white">{{ $student->student_grade_level }}</p>
                                </div>
                                @if($student->student_medical_info)
                                <div>
                                    <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Medical Information</label>
                                    <p class="text-slate-900 dark:text-white">{{ $student->student_medical_info }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Parent/Guardian Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Parent/Guardian Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Name</label>
                                    <p class="text-slate-900 dark:text-white">{{ $student->student_parent_name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Email</label>
                                    <p class="text-slate-900 dark:text-white">
                                        <a href="mailto:{{ $student->student_parent_email }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $student->student_parent_email }}
                                        </a>
                                    </p>
                                </div>
                                @if($student->student_parent_phone)
                                <div>
                                    <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Phone</label>
                                    <p class="text-slate-900 dark:text-white">
                                        <a href="tel:{{ $student->student_parent_phone }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $student->student_parent_phone }}
                                        </a>
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Club Enrollments -->
                    @if($student->clubs->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Club Enrollments</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($student->clubs as $club)
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 border border-slate-200 dark:border-slate-600">
                                <h4 class="font-semibold text-slate-900 dark:text-white">{{ $club->club_name }}</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $club->school->school_name ?? 'No School' }}</p>
                                <span class="inline-block mt-2 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs font-medium">
                                    {{ $club->club_level }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
