<x-layouts.app>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Edit Assessment</h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">{{ $assessment->assessment_name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('assessments.show', $assessment->id) }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Assessment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                <form method="POST" action="{{ route('assessments.update', $assessment->id) }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Name</label>
                                <input name="assessment_name" value="{{ old('assessment_name', $assessment->assessment_name) }}" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="e.g., Python Basics Quiz" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Type</label>
                                <select name="assessment_type" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                    <option value="quiz" {{ old('assessment_type', $assessment->assessment_type) == 'quiz' ? 'selected' : '' }}>📝 Quiz (Multiple Choice Questions)</option>
                                    <option value="test" {{ old('assessment_type', $assessment->assessment_type) == 'test' ? 'selected' : '' }}>📊 Test (Mixed Questions)</option>
                                    <option value="assignment" {{ old('assessment_type', $assessment->assessment_type) == 'assignment' ? 'selected' : '' }}>📋 Assignment (Text Questions)</option>
                                    <option value="project" {{ old('assessment_type', $assessment->assessment_type) == 'project' ? 'selected' : '' }}>🎯 Project (Practical Work)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total Points</label>
                                <input name="total_points" type="number" value="{{ old('total_points', $assessment->total_points) }}" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="e.g., 100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Due Date</label>
                                <input name="due_date" type="date" value="{{ old('due_date', $assessment->due_date) }}" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                            <textarea name="description" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="Assessment description...">{{ old('description', $assessment->description) }}</textarea>
                        </div>
                    </div>
                    
                    <!-- Questions Section -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Questions</h2>
                        
                        <!-- Question Type Selection -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <button type="button" onclick="addQuestion('multiple_choice')" class="p-3 border-2 border-blue-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">📝</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Multiple Choice</div>
                                </div>
                            </button>
                            <button type="button" onclick="addQuestion('practical_project')" class="p-3 border-2 border-green-200 rounded-lg hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🎯</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Practical Project</div>
                                </div>
                            </button>
                            <button type="button" onclick="addQuestion('image_question')" class="p-3 border-2 border-purple-200 rounded-lg hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🖼️</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Image Question</div>
                                </div>
                            </button>
                            <button type="button" onclick="addQuestion('text_question')" class="p-3 border-2 border-orange-200 rounded-lg hover:border-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">📝</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Text Question</div>
                                </div>
                            </button>
                        </div>
                        
                        <!-- Existing Questions -->
                        <div id="questionsContainer" class="space-y-4">
                            @if($assessment->questions->count() > 0)
                                @foreach($assessment->questions as $index => $question)
                                    <div class="question-item bg-slate-50 dark:bg-slate-700 rounded-lg p-4 border border-slate-200 dark:border-slate-600">
                                        <div class="flex items-center justify-between mb-3">
                                            <h5 class="font-medium text-slate-900 dark:text-white">Question {{ $index + 1 }} - {{ $question->question_type_label }}</h5>
                                            <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Question form fields based on type -->
                                        @include('assessments.partials.question-form', ['question' => $question, 'index' => $index])
                                    </div>
                                @endforeach
                            @else
                                <p class="text-slate-500 dark:text-slate-400 text-center py-8">Click a question type above to add questions to your assessment</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-200 dark:border-slate-700 mt-8">
                        <a href="{{ route('assessments.show', $assessment->id) }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            Update Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for question management -->
    <script>
        let questionCounter = {{ $assessment->questions->count() }};
        
        function addQuestion(type) {
            questionCounter++;
            const container = document.getElementById('questionsContainer');
            
            // Remove the placeholder text if it exists
            const placeholder = container.querySelector('p');
            if (placeholder) {
                placeholder.remove();
            }
            
            let questionHtml = '';
            
            switch(type) {
                case 'multiple_choice':
                    questionHtml = createMultipleChoiceQuestion(questionCounter);
                    break;
                case 'practical_project':
                    questionHtml = createPracticalProjectQuestion(questionCounter);
                    break;
                case 'image_question':
                    questionHtml = createImageQuestion(questionCounter);
                    break;
                case 'text_question':
                    questionHtml = createTextQuestion(questionCounter);
                    break;
            }
            
            container.insertAdjacentHTML('beforeend', questionHtml);
        }
        
        function removeQuestion(button) {
            button.closest('.question-item').remove();
            
            // Show placeholder if no questions left
            const container = document.getElementById('questionsContainer');
            if (container.children.length === 0) {
                container.innerHTML = '<p class="text-slate-500 dark:text-slate-400 text-center py-8">Click a question type above to add questions to your assessment</p>';
            }
        }
        
        // Question creation functions (same as in index.blade.php)
        function createMultipleChoiceQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Multiple Choice</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="multiple_choice">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text</label>
                            <textarea name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="Enter your question here..." required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option A</label>
                                <input name="questions[${counter}][option_a]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option B</label>
                                <input name="questions[${counter}][option_b]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option C</label>
                                <input name="questions[${counter}][option_c]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option D</label>
                                <input name="questions[${counter}][option_d]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Correct Answer</label>
                            <select name="questions[${counter}][correct_answer]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                <option value="">Select correct answer</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="1" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function createPracticalProjectQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Practical Project</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="practical_project">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Project Title</label>
                            <input name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="e.g., Create a Calculator App" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Project Instructions</label>
                            <textarea name="questions[${counter}][project_instructions]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="Describe what students need to build..." required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Output Format</label>
                            <select name="questions[${counter}][project_output_format]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                <option value="">Select output format</option>
                                <option value="scratch_project">🎨 Scratch Project</option>
                                <option value="python_file">🐍 Python File</option>
                                <option value="html_file">🌐 HTML File</option>
                                <option value="javascript_file">📜 JavaScript File</option>
                                <option value="mobile_app">📱 Mobile App</option>
                                <option value="robotics_project">🤖 Robotics Project</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Requirements (one per line)</label>
                            <textarea name="questions[${counter}][project_requirements]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="• Must have at least 3 functions&#10;• Should include error handling&#10;• Must be interactive"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="10" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function createImageQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Image Question</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="image_question">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text</label>
                            <textarea name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="What do you see in this image? What is wrong with this code?" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Upload Image</label>
                            <input type="file" name="questions[${counter}][image_file]" accept="image/*" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Image Description</label>
                            <textarea name="questions[${counter}][image_description]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="Describe what students should see or identify in the image..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Expected Answer</label>
                            <textarea name="questions[${counter}][correct_answer]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="What should students identify or explain?"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="5" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function createTextQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Text Question</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="text_question">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text</label>
                            <textarea name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="Explain the concept of..." required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sample Answer (for reference)</label>
                            <textarea name="questions[${counter}][correct_answer]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="Key points students should mention..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="3" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
    </script>
</x-layouts.app>
