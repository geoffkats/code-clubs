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
                            Edit Student
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Update student information</p>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
                <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="student_first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                First Name
                            </label>
                            <input type="text" 
                                   id="student_first_name" 
                                   name="student_first_name" 
                                   value="{{ old('student_first_name', $student->student_first_name) }}"
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('student_first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="student_last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Last Name
                            </label>
                            <input type="text" 
                                   id="student_last_name" 
                                   name="student_last_name" 
                                   value="{{ old('student_last_name', $student->student_last_name) }}"
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('student_last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grade Level -->
                        <div>
                            <label for="student_grade_level" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Grade Level
                            </label>
                            <select id="student_grade_level" 
                                    name="student_grade_level" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Select Grade</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="Grade {{ $i }}" {{ old('student_grade_level', $student->student_grade_level) == "Grade {$i}" ? 'selected' : '' }}>
                                        Grade {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('student_grade_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Name -->
                        <div>
                            <label for="student_parent_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Parent/Guardian Name
                            </label>
                            <input type="text" 
                                   id="student_parent_name" 
                                   name="student_parent_name" 
                                   value="{{ old('student_parent_name', $student->student_parent_name) }}"
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('student_parent_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Email -->
                        <div>
                            <label for="student_parent_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Parent/Guardian Email
                            </label>
                            <input type="email" 
                                   id="student_parent_email" 
                                   name="student_parent_email" 
                                   value="{{ old('student_parent_email', $student->student_parent_email) }}"
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('student_parent_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Phone -->
                        <div>
                            <label for="student_parent_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Parent/Guardian Phone
                            </label>
                            <input type="tel" 
                                   id="student_parent_phone" 
                                   name="student_parent_phone" 
                                   value="{{ old('student_parent_phone', $student->student_parent_phone) }}"
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('student_parent_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div>
                        <label for="student_medical_info" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Medical Information
                        </label>
                        <textarea id="student_medical_info" 
                                  name="student_medical_info" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any medical conditions, allergies, or special requirements...">{{ old('student_medical_info', $student->student_medical_info) }}</textarea>
                        @error('student_medical_info')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('students.index') }}" 
                           class="px-6 py-3 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
