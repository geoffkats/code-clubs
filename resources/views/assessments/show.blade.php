<x-layouts.app>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessment->assessment_name }}</h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            {{ $assessment->club->club_name }} - {{ $assessment->club->school->school_name ?? 'No School' }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('assessments.edit', $assessment->id) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Assessment
                        </a>
                        <a href="{{ route('assessments.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Assessments
                        </a>
                    </div>
                </div>
            </div>

            <!-- Assessment Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Assessment Info -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Assessment Details</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Type</label>
                                <div class="flex items-center">
                                    <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg text-sm font-medium">
                                        {{ ucfirst($assessment->assessment_type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total Points</label>
                                <p class="text-slate-900 dark:text-white font-semibold">{{ $assessment->total_points ?? 0 }} points</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Due Date</label>
                                <p class="text-slate-900 dark:text-white">
                                    @if($assessment->due_date)
                                        {{ \Carbon\Carbon::parse($assessment->due_date)->format('M d, Y') }}
                                    @else
                                        <span class="text-slate-500 dark:text-slate-400">No due date set</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Created</label>
                                <p class="text-slate-900 dark:text-white">{{ $assessment->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        @if($assessment->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                                <p class="text-slate-900 dark:text-white">{{ $assessment->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Questions -->
                    @if($assessment->questions->count() > 0)
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Questions ({{ $assessment->questions->count() }})</h2>
                            
                            <div class="space-y-6">
                                @foreach($assessment->questions as $index => $question)
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="font-semibold text-slate-900 dark:text-white">Question {{ $index + 1 }} - {{ $question->question_type_label }}</h3>
                                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs font-medium">
                                                {{ $question->points }} point{{ $question->points != 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <p class="text-slate-900 dark:text-white">{{ $question->question_text }}</p>
                                            
                                            @if($question->question_type === 'multiple_choice' && $question->question_options)
                                                <div class="grid grid-cols-2 gap-2">
                                                    @foreach($question->question_options as $option => $text)
                                                        <div class="flex items-center space-x-2">
                                                            <span class="w-6 h-6 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-sm font-medium">{{ $option }}</span>
                                                            <span class="text-slate-700 dark:text-slate-300">{{ $text }}</span>
                                                            @if($question->correct_answer === $option)
                                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            @if($question->question_type === 'practical_project')
                                                <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-3">
                                                    <h4 class="font-medium text-slate-900 dark:text-white mb-2">Project Instructions:</h4>
                                                    <p class="text-slate-700 dark:text-slate-300">{{ $question->project_instructions }}</p>
                                                    
                                                    @if($question->project_requirements)
                                                        <h4 class="font-medium text-slate-900 dark:text-white mb-2 mt-3">Requirements:</h4>
                                                        <ul class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-1">
                                                            @foreach($question->project_requirements as $requirement)
                                                                <li>{{ $requirement }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    
                                                    @if($question->project_output_format)
                                                        <div class="mt-3">
                                                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded text-xs">
                                                                Output: {{ $question->project_output_format_label }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            @if($question->question_type === 'image_question' && $question->image_url)
                                                <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-3">
                                                    <h4 class="font-medium text-slate-900 dark:text-white mb-2">Image:</h4>
                                                    <img src="{{ Storage::url($question->image_url) }}" alt="Question Image" class="max-w-full h-48 object-contain rounded border border-slate-200 dark:border-slate-600">
                                                    @if($question->image_description)
                                                        <p class="text-slate-700 dark:text-slate-300 mt-2 text-sm">{{ $question->image_description }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            @if($question->question_type === 'text_question' && $question->correct_answer)
                                                <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-3">
                                                    <h4 class="font-medium text-slate-900 dark:text-white mb-2">Sample Answer:</h4>
                                                    <p class="text-slate-700 dark:text-slate-300">{{ $question->correct_answer }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No Questions Added</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-4">This assessment doesn't have any questions yet.</p>
                                <a href="{{ route('assessments.edit', $assessment->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Questions
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Stats -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Statistics</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Total Submissions</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $assessment->scores->count() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Questions</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $assessment->questions->count() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Total Points</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $assessment->total_points ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Actions</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('assessments.scores', $assessment->id) }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                View Scores
                            </a>
                            
                            <form method="POST" action="{{ route('assessments.destroy', $assessment->id) }}" onsubmit="return confirm('Are you sure you want to delete this assessment? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Assessment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
