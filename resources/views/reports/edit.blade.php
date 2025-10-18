<x-layouts.app :title="__('Edit Report')">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Enterprise Header -->
        <div class="bg-gradient-to-r from-slate-800 via-amber-900 to-orange-900 rounded-3xl shadow-2xl p-8 text-white mb-8 border border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 mr-4">
                            <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-200 to-orange-200 bg-clip-text text-transparent">
                                Edit Report
                            </h1>
                            <p class="text-slate-300 text-lg mt-1">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }} - {{ $report->club->club_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('reports.show', $report->id) }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <form method="POST" action="{{ route('reports.update', $report->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Report Name -->
                <div>
                    <label for="report_name" class="block text-sm font-semibold text-slate-700 mb-2">
                        Report Name
                    </label>
                    <input type="text" 
                           id="report_name" 
                           name="report_name" 
                           value="{{ old('report_name', $report->report_name) }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700"
                           required>
                    @error('report_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Overall Score -->
                <div>
                    <label for="report_overall_score" class="block text-sm font-semibold text-slate-700 mb-2">
                        Overall Score (%)
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="report_overall_score" 
                               name="report_overall_score" 
                               value="{{ old('report_overall_score', $report->report_overall_score) }}"
                               min="0" 
                               max="100" 
                               step="0.1"
                               class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700"
                               required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-slate-500 text-sm font-medium">%</span>
                        </div>
                    </div>
                    @error('report_overall_score')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Student Initials -->
                <div>
                    <label for="student_initials" class="block text-sm font-semibold text-slate-700 mb-2">
                        Student Initials
                    </label>
                    <input type="text" 
                           id="student_initials" 
                           name="student_initials" 
                           value="{{ old('student_initials', $report->student_initials ?? strtoupper(substr($report->student->student_first_name, 0, 1) . substr($report->student->student_last_name, 0, 1))) }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700"
                           placeholder="e.g., JS for John Smith"
                           maxlength="3">
                    @error('student_initials')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Coding Skills Assessment -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">
                        🎮 Coding Skills Assessment
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="problem_solving_score" class="block text-sm font-medium text-slate-600 mb-2">
                                Problem Solving Skills (1-10)
                            </label>
                            <input type="number" 
                                   id="problem_solving_score" 
                                   name="problem_solving_score" 
                                   value="{{ old('problem_solving_score', $report->problem_solving_score ?? '') }}"
                                   min="1" max="10"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700">
                        </div>
                        <div>
                            <label for="creativity_score" class="block text-sm font-medium text-slate-600 mb-2">
                                Creativity & Innovation (1-10)
                            </label>
                            <input type="number" 
                                   id="creativity_score" 
                                   name="creativity_score" 
                                   value="{{ old('creativity_score', $report->creativity_score ?? '') }}"
                                   min="1" max="10"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700">
                        </div>
                        <div>
                            <label for="collaboration_score" class="block text-sm font-medium text-slate-600 mb-2">
                                Teamwork & Collaboration (1-10)
                            </label>
                            <input type="number" 
                                   id="collaboration_score" 
                                   name="collaboration_score" 
                                   value="{{ old('collaboration_score', $report->collaboration_score ?? '') }}"
                                   min="1" max="10"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700">
                        </div>
                        <div>
                            <label for="persistence_score" class="block text-sm font-medium text-slate-600 mb-2">
                                Persistence & Perseverance (1-10)
                            </label>
                            <input type="number" 
                                   id="persistence_score" 
                                   name="persistence_score" 
                                   value="{{ old('persistence_score', $report->persistence_score ?? '') }}"
                                   min="1" max="10"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Student Grade/Year -->
                <div>
                    <label for="student_grade" class="block text-sm font-semibold text-slate-700 mb-2">
                        📚 Student Grade/Year
                    </label>
                    <input type="text" 
                           id="student_grade" 
                           name="student_grade" 
                           value="{{ old('student_grade', $report->student->student_grade_level ?? '') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700"
                           placeholder="e.g., Grade 3, Year 4, etc.">
                </div>

                <!-- Scratch Project IDs -->
                <div>
                    <label for="scratch_project_ids" class="block text-sm font-semibold text-slate-700 mb-2">
                        🎮 Scratch Project IDs
                    </label>
                    <textarea id="scratch_project_ids" 
                              name="scratch_project_ids" 
                              rows="4"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="Enter Scratch project IDs (one per line):&#10;123456789&#10;987654321&#10;456789123">{{ old('scratch_project_ids', is_array(json_decode($report->scratch_project_ids ?? '[]', true)) ? implode("\n", json_decode($report->scratch_project_ids ?? '[]', true)) : '') }}</textarea>
                    <p class="text-sm text-slate-500 mt-2">Enter one Scratch project ID per line. Parents will be able to preview these projects.</p>
                </div>

                <!-- Favorite Coding Concept -->
                <div>
                    <label for="favorite_concept" class="block text-sm font-semibold text-slate-700 mb-2">
                        ⭐ Favorite Coding Concept
                    </label>
                    <input type="text" 
                           id="favorite_concept" 
                           name="favorite_concept" 
                           value="{{ old('favorite_concept', $report->favorite_concept ?? '') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700"
                           placeholder="e.g., Loops, Variables, Sprites, Sound Effects">
                </div>

                <!-- Challenges Overcome -->
                <div>
                    <label for="challenges_overcome" class="block text-sm font-semibold text-slate-700 mb-2">
                        🏆 Challenges Overcome
                    </label>
                    <textarea id="challenges_overcome" 
                              name="challenges_overcome" 
                              rows="3"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="Describe the coding challenges your child successfully tackled">{{ old('challenges_overcome', $report->challenges_overcome ?? '') }}</textarea>
                </div>

                <!-- Special Achievements -->
                <div>
                    <label for="special_achievements" class="block text-sm font-semibold text-slate-700 mb-2">
                        🏅 Special Achievements & Recognition
                    </label>
                    <textarea id="special_achievements" 
                              name="special_achievements" 
                              rows="3"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="Any "Coder of the Week" awards, peer recognition, or special moments">{{ old('special_achievements', $report->special_achievements ?? '') }}</textarea>
                </div>

                <!-- Areas for Growth -->
                <div>
                    <label for="areas_for_growth" class="block text-sm font-semibold text-slate-700 mb-2">
                        🌱 Implementation of Areas for Growth
                    </label>
                    <textarea id="areas_for_growth" 
                              name="areas_for_growth" 
                              rows="3"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="How your child has improved in areas identified earlier">{{ old('areas_for_growth', $report->areas_for_growth ?? '') }}</textarea>
                </div>

                <!-- Next Steps -->
                <div>
                    <label for="next_steps" class="block text-sm font-semibold text-slate-700 mb-2">
                        🚀 Next Steps & Recommendations
                    </label>
                    <textarea id="next_steps" 
                              name="next_steps" 
                              rows="3"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="Suggestions for continued learning and development">{{ old('next_steps', $report->next_steps ?? '') }}</textarea>
                </div>

                <!-- Parent Feedback -->
                <div>
                    <label for="parent_feedback" class="block text-sm font-semibold text-slate-700 mb-2">
                        💬 Parent Feedback & Comments
                    </label>
                    <textarea id="parent_feedback" 
                              name="parent_feedback" 
                              rows="3"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="Space for parent comments and feedback">{{ old('parent_feedback', $report->parent_feedback ?? '') }}</textarea>
                </div>

                <!-- Summary Text -->
                <div>
                    <label for="report_summary_text" class="block text-sm font-semibold text-slate-700 mb-2">
                        📝 Overall Summary
                    </label>
                    <textarea id="report_summary_text" 
                              name="report_summary_text" 
                              rows="6"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="Write a comprehensive summary of your child's coding journey..."
                              required>{{ old('report_summary_text', $report->report_summary_text) }}</textarea>
                    @error('report_summary_text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('reports.show', $report->id) }}" 
                       class="px-6 py-3 text-slate-600 hover:text-slate-800 font-semibold transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Info Card -->
        <div class="mt-8 bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl p-6 border border-white/20">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Report Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="text-sm text-slate-600 mb-1">Student</div>
                    <div class="font-semibold text-slate-800">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="text-sm text-slate-600 mb-1">Club</div>
                    <div class="font-semibold text-slate-800">{{ $report->club->club_name }}</div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="text-sm text-slate-600 mb-1">Generated</div>
                    <div class="font-semibold text-slate-800">
                        @if($report->report_generated_at)
                            @if(is_string($report->report_generated_at))
                                {{ \Carbon\Carbon::parse($report->report_generated_at)->format('M j, Y g:i A') }}
                            @else
                                {{ $report->report_generated_at->format('M j, Y g:i A') }}
                            @endif
                        @else
                            Not generated
                        @endif
                    </div>
                </div>
            </div>
            
            @if($report->access_code)
                <div class="mt-4 bg-green-50 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-green-800">Access code generated: {{ $report->access_code->access_code_plain_preview }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
