@extends('layouts.teacher')

@section('title', 'Create Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create Report</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Generate a student progress report</p>
        </div>
        <a href="{{ route('teacher.reports') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Reports
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        <form action="{{ route('teacher.reports.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Student <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" id="student_id" required
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a student</option>
                        @foreach(auth()->user()->clubsAsTeacher as $club)
                            @foreach($club->students as $student)
                                <option value="{{ $student->id }}" 
                                        data-club="{{ $club->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->student_first_name }} {{ $student->student_last_name }} - {{ $club->club_name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Club Selection -->
                <div>
                    <label for="club_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Club <span class="text-red-500">*</span>
                    </label>
                    <select name="club_id" id="club_id" required
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a club</option>
                        @foreach(auth()->user()->clubsAsTeacher as $club)
                            <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : '' }}>
                                {{ $club->club_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('club_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Report Name -->
            <div class="mt-6">
                <label for="report_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Report Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="report_name" id="report_name" required
                       value="{{ old('report_name') }}"
                       placeholder="e.g., Progress Report - Q1 2024"
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('report_name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Report Summary -->
            <div class="mt-6">
                <label for="report_summary_text" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Report Summary
                </label>
                <textarea name="report_summary_text" id="report_summary_text" rows="4"
                          placeholder="Provide a summary of the student's progress..."
                          class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('report_summary_text') }}</textarea>
                @error('report_summary_text')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Scores -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Assessment Scores</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="problem_solving_score" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Problem Solving (0-100)
                        </label>
                        <input type="number" name="problem_solving_score" id="problem_solving_score" min="0" max="100"
                               value="{{ old('problem_solving_score') }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="creativity_score" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Creativity (0-100)
                        </label>
                        <input type="number" name="creativity_score" id="creativity_score" min="0" max="100"
                               value="{{ old('creativity_score') }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="collaboration_score" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Collaboration (0-100)
                        </label>
                        <input type="number" name="collaboration_score" id="collaboration_score" min="0" max="100"
                               value="{{ old('collaboration_score') }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="persistence_score" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Persistence (0-100)
                        </label>
                        <input type="number" name="persistence_score" id="persistence_score" min="0" max="100"
                               value="{{ old('persistence_score') }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Additional Information</h3>
                <div class="space-y-4">
                    <div>
                        <label for="favorite_concept" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Favorite Concept
                        </label>
                        <input type="text" name="favorite_concept" id="favorite_concept"
                               value="{{ old('favorite_concept') }}"
                               placeholder="What concept did the student enjoy most?"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="challenges_overcome" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Challenges Overcome
                        </label>
                        <textarea name="challenges_overcome" id="challenges_overcome" rows="3"
                                  placeholder="Describe challenges the student faced and how they overcame them..."
                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('challenges_overcome') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="special_achievements" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Special Achievements
                        </label>
                        <textarea name="special_achievements" id="special_achievements" rows="3"
                                  placeholder="Highlight any special achievements or milestones..."
                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('special_achievements') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="areas_for_growth" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Areas for Growth
                        </label>
                        <textarea name="areas_for_growth" id="areas_for_growth" rows="3"
                                  placeholder="Identify areas where the student can improve..."
                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('areas_for_growth') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="next_steps" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Next Steps
                        </label>
                        <textarea name="next_steps" id="next_steps" rows="3"
                                  placeholder="Recommendations for the student's continued learning..."
                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('next_steps') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('teacher.reports') }}" 
                   class="px-6 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Create Report
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-select club when student is selected
document.getElementById('student_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        const clubId = selectedOption.getAttribute('data-club');
        document.getElementById('club_id').value = clubId;
    }
});
</script>
@endsection
