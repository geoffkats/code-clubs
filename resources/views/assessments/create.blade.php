<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-amber-50 to-orange-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             assessmentType: 'quiz',
             questions: [],
             addQuestion: function() {
                 this.questions.push({
                     id: Date.now(),
                     question: '',
                     type: 'multiple_choice',
                     options: ['', '', '', ''],
                     correctAnswer: 0,
                     points: 1
                 });
             },
             removeQuestion: function(id) {
                 this.questions = this.questions.filter(q => q.id !== id);
             }
         }">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('clubs.show', request('club_id')) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-amber-900 to-orange-900 dark:from-white dark:via-amber-100 dark:to-orange-100 bg-clip-text text-transparent">
                                Create Assessment
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Design quizzes and tests for your students</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            Save Assessment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <div class="max-w-4xl mx-auto">
                <form method="post" action="{{ route('assessments.store', ['club_id' => request('club_id')]) }}" class="space-y-8">
                    @csrf
                    
                    <!-- Assessment Details -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Assessment Details</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Name</label>
                                <input name="assessment_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="e.g., Python Basics Quiz" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Type</label>
                                <select name="assessment_type" x-model="assessmentType" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                    <option value="quiz">Quiz</option>
                                    <option value="test">Test</option>
                                    <option value="assignment">Assignment</option>
                                    <option value="project">Project</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Due Date</label>
                                <input type="datetime-local" name="due_date" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total Points</label>
                                <input type="number" name="total_points" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="100" required>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Describe what this assessment covers..."></textarea>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Attachments</label>
                            <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-amber-400 dark:hover:border-amber-500 transition-colors">
                                <svg class="w-12 h-12 text-slate-400 dark:text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-slate-600 dark:text-slate-400 mb-2">Drop files here or click to upload</p>
                                <p class="text-sm text-slate-500 dark:text-slate-500">Images, PDFs, documents (Max 10MB each)</p>
                                <input type="file" name="attachments[]" multiple accept="image/*,application/pdf,.doc,.docx,.txt" class="hidden" id="file-upload">
                                <button type="button" onclick="document.getElementById('file-upload').click()" class="mt-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                    Choose Files
                                </button>
                            </div>
                            <div id="file-list" class="mt-4 space-y-2"></div>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Questions</h2>
                            <button type="button" @click="addQuestion()" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Question
                            </button>
                        </div>
                        
                        <div x-show="questions.length === 0" class="text-center py-12">
                            <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No questions yet</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-4">Add questions to create your assessment</p>
                            <button type="button" @click="addQuestion()" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                                Add First Question
                            </button>
                        </div>
                        
                        <div class="space-y-6" x-show="questions.length > 0">
                            <template x-for="(question, index) in questions" :key="question.id">
                                <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-medium text-slate-900 dark:text-white">Question <span x-text="index + 1"></span></h3>
                                        <button type="button" @click="removeQuestion(question.id)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Question Text</label>
                                            <textarea x-model="question.question" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="Enter your question..."></textarea>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Question Type</label>
                                                <select x-model="question.type" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                                    <option value="multiple_choice">Multiple Choice</option>
                                                    <option value="true_false">True/False</option>
                                                    <option value="short_answer">Short Answer</option>
                                                    <option value="essay">Essay</option>
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Points</label>
                                                <input type="number" x-model="question.points" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" min="1">
                                            </div>
                                        </div>
                                        
                                        <div x-show="question.type === 'multiple_choice'" class="space-y-3">
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Answer Options</label>
                                            <template x-for="(option, optionIndex) in question.options" :key="optionIndex">
                                                <div class="flex items-center space-x-3">
                                                    <input type="radio" :name="`question_${question.id}_correct`" :value="optionIndex" x-model="question.correctAnswer" class="text-amber-600 focus:ring-amber-500">
                                                    <input type="text" x-model="question.options[optionIndex]" class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" :placeholder="`Option ${optionIndex + 1}`">
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('clubs.show', request('club_id')) }}" class="px-6 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            Create Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // File upload handling
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file-upload');
            const fileList = document.getElementById('file-list');
            const dropZone = fileInput.closest('.border-dashed');

            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });

            // Handle drag and drop
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-amber-400', 'bg-amber-50', 'dark:bg-amber-900/20');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-amber-400', 'bg-amber-50', 'dark:bg-amber-900/20');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-amber-400', 'bg-amber-50', 'dark:bg-amber-900/20');
                handleFiles(e.dataTransfer.files);
            });

            function handleFiles(files) {
                Array.from(files).forEach(file => {
                    if (validateFile(file)) {
                        addFileToList(file);
                    }
                });
            }

            function validateFile(file) {
                const maxSize = 10 * 1024 * 1024; // 10MB
                const allowedTypes = [
                    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                    'application/pdf', 'text/plain',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (file.size > maxSize) {
                    alert('File size must be less than 10MB');
                    return false;
                }

                if (!allowedTypes.includes(file.type)) {
                    alert('File type not allowed');
                    return false;
                }

                return true;
            }

            function addFileToList(file) {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg';
                fileItem.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">${file.name}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">${formatFileSize(file.size)}</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(this)" class="p-1 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/20 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                fileList.appendChild(fileItem);
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            window.removeFile = function(button) {
                button.closest('.flex').remove();
            };
        });
    </script>
</x-layouts.app>