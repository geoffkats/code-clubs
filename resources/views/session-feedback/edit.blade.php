<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Header -->
        <div class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route(auth()->user()->user_role . '.feedback.index') }}" 
                           class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Session Feedback</h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $sessionFeedback->session->title }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $sessionFeedback->club->club_name }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-slate-700 dark:to-slate-700">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Edit Feedback</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Update your session feedback</p>
                    </div>

                    <div class="p-8">
                        <form method="POST" action="{{ route('facilitator.feedback.update', $sessionFeedback) }}" class="space-y-8">
                            @csrf
                            @method('PUT')
                            
                            <!-- Session Information -->
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Session Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teacher</label>
                                        <p class="text-slate-900 dark:text-white font-medium">{{ $sessionFeedback->teacher->name ?? 'No teacher assigned' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Session Time</label>
                                        <p class="text-slate-900 dark:text-white">{{ $sessionFeedback->session->session_date->format('M d, Y') }} at {{ $sessionFeedback->session->start_time }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating System -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    Performance Ratings
                                </h3>
                                
                                <!-- Content Delivery Rating -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        Content Delivery <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="content_delivery_rating" value="{{ $i }}" id="content_delivery_{{ $i }}" required
                                                   class="sr-only star-rating" {{ $sessionFeedback->content_delivery_rating == $i ? 'checked' : '' }}>
                                            <label for="content_delivery_{{ $i }}" class="star-label cursor-pointer">
                                                <svg class="w-8 h-8 {{ $i <= $sessionFeedback->content_delivery_rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Student Engagement Rating -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        Student Engagement <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="student_engagement_rating" value="{{ $i }}" id="student_engagement_{{ $i }}" required
                                                   class="sr-only star-rating" {{ $sessionFeedback->student_engagement_rating == $i ? 'checked' : '' }}>
                                            <label for="student_engagement_{{ $i }}" class="star-label cursor-pointer">
                                                <svg class="w-8 h-8 {{ $i <= $sessionFeedback->student_engagement_rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Session Management Rating -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        Session Management <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="session_management_rating" value="{{ $i }}" id="session_management_{{ $i }}" required
                                                   class="sr-only star-rating" {{ $sessionFeedback->session_management_rating == $i ? 'checked' : '' }}>
                                            <label for="session_management_{{ $i }}" class="star-label cursor-pointer">
                                                <svg class="w-8 h-8 {{ $i <= $sessionFeedback->session_management_rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Preparation Rating -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        Preparation <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="preparation_rating" value="{{ $i }}" id="preparation_{{ $i }}" required
                                                   class="sr-only star-rating" {{ $sessionFeedback->preparation_rating == $i ? 'checked' : '' }}>
                                            <label for="preparation_{{ $i }}" class="star-label cursor-pointer">
                                                <svg class="w-8 h-8 {{ $i <= $sessionFeedback->preparation_rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Overall Rating -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        Overall Performance <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="overall_rating" value="{{ $i }}" id="overall_{{ $i }}" required
                                                   class="sr-only star-rating" {{ $sessionFeedback->overall_rating == $i ? 'checked' : '' }}>
                                            <label for="overall_{{ $i }}" class="star-label cursor-pointer">
                                                <svg class="w-8 h-8 {{ $i <= $sessionFeedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Feedback Type -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Feedback Type <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <label class="flex items-center space-x-3 p-4 border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors">
                                        <input type="radio" name="feedback_type" value="positive" required class="text-green-500 focus:ring-green-500" {{ $sessionFeedback->feedback_type == 'positive' ? 'checked' : '' }}>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Positive</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors">
                                        <input type="radio" name="feedback_type" value="constructive" required class="text-blue-500 focus:ring-blue-500" {{ $sessionFeedback->feedback_type == 'constructive' ? 'checked' : '' }}>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Constructive</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors">
                                        <input type="radio" name="feedback_type" value="critical" required class="text-red-500 focus:ring-red-500" {{ $sessionFeedback->feedback_type == 'critical' ? 'checked' : '' }}>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Critical</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors">
                                        <input type="radio" name="feedback_type" value="mixed" required class="text-yellow-500 focus:ring-yellow-500" {{ $sessionFeedback->feedback_type == 'mixed' ? 'checked' : '' }}>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Mixed</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Feedback Content -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Detailed Feedback <span class="text-red-500">*</span>
                                </label>
                                <textarea name="content" rows="6" required
                                          class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md"
                                          placeholder="Describe what went well, what could be improved, and specific suggestions for the teacher...">{{ old('content', $sessionFeedback->content) }}</textarea>
                            </div>

                            <!-- Suggestions -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Improvement Suggestions
                                </label>
                                <div id="suggestions-container" class="space-y-3">
                                    @if($sessionFeedback->suggestions && count($sessionFeedback->suggestions) > 0)
                                        @foreach($sessionFeedback->suggestions as $index => $suggestion)
                                            <div class="suggestion-item flex items-center space-x-3">
                                                <input type="text" name="suggestions[]" value="{{ $suggestion }}"
                                                       class="flex-1 px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md"
                                                       placeholder="Enter a specific suggestion for improvement...">
                                                <button type="button" onclick="removeSuggestion(this)" 
                                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="suggestion-item">
                                            <input type="text" name="suggestions[]" 
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md"
                                                   placeholder="Enter a specific suggestion for improvement...">
                                        </div>
                                    @endif
                                </div>
                                <button type="button" onclick="addSuggestion()" 
                                        class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Add Suggestion</span>
                                </button>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ route('facilitator.feedback.index') }}" 
                                   class="px-6 py-3 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors border border-slate-300 dark:border-slate-600">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                    Update Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .star-rating:checked ~ .star-label svg,
        .star-label:hover svg {
            color: #fbbf24 !important;
        }
        
        .star-label:hover ~ .star-label svg {
            color: #d1d5db !important;
        }
    </style>

    <script>
        // Star rating functionality
        document.querySelectorAll('.star-rating').forEach(radio => {
            radio.addEventListener('change', function() {
                const container = this.closest('.space-y-3');
                const labels = container.querySelectorAll('.star-label');
                const rating = parseInt(this.value);
                
                labels.forEach((label, index) => {
                    const svg = label.querySelector('svg');
                    if (index < rating) {
                        svg.style.color = '#fbbf24';
                    } else {
                        svg.style.color = '#d1d5db';
                    }
                });
            });
        });

        // Suggestions functionality
        function addSuggestion() {
            const container = document.getElementById('suggestions-container');
            const newSuggestion = document.createElement('div');
            newSuggestion.className = 'suggestion-item flex items-center space-x-3';
            newSuggestion.innerHTML = `
                <input type="text" name="suggestions[]" 
                       class="flex-1 px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md"
                       placeholder="Enter a specific suggestion for improvement...">
                <button type="button" onclick="removeSuggestion(this)" 
                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            container.appendChild(newSuggestion);
        }

        function removeSuggestion(button) {
            button.closest('.suggestion-item').remove();
        }
    </script>
</x-layouts.app>
