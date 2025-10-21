@extends('layouts.admin')
@section('title', 'View Session Feedback')

@section('content')
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
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Session Feedback</h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $sessionFeedback->session->title }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $sessionFeedback->club->club_name }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Session Information -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Session Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teacher</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ $sessionFeedback->teacher->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Facilitator</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ $sessionFeedback->facilitator->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Session Date</label>
                            <p class="text-slate-900 dark:text-white">{{ $sessionFeedback->session->session_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Session Time</label>
                            <p class="text-slate-900 dark:text-white">{{ $sessionFeedback->session->start_time }}</p>
                        </div>
                    </div>
                </div>

                <!-- Feedback Type and Status -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Feedback Type</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $sessionFeedback->feedback_type === 'positive' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $sessionFeedback->feedback_type === 'constructive' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $sessionFeedback->feedback_type === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $sessionFeedback->feedback_type === 'mixed' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($sessionFeedback->feedback_type) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $sessionFeedback->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $sessionFeedback->status === 'submitted' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $sessionFeedback->status === 'reviewed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $sessionFeedback->status === 'actioned' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ ucfirst($sessionFeedback->status) }}
                                </span>
                            </div>
                        </div>
                        @if(auth()->user()->user_role === 'facilitator' && $sessionFeedback->status === 'draft')
                            <a href="{{ route('facilitator.feedback.edit', $sessionFeedback) }}" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                Edit Feedback
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Ratings -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Performance Ratings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Content Delivery</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $sessionFeedback->content_delivery_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->content_delivery_rating }}/5</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Student Engagement</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $sessionFeedback->student_engagement_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->student_engagement_rating }}/5</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Session Management</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $sessionFeedback->session_management_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->session_management_rating }}/5</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Preparation</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $sessionFeedback->preparation_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->preparation_rating }}/5</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Overall Rating -->
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Overall Rating</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= $sessionFeedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-lg font-semibold text-slate-900 dark:text-white">{{ $sessionFeedback->overall_rating }}/5</span>
                            @if($sessionFeedback->average_rating)
                                <span class="text-sm text-slate-600 dark:text-slate-400">(Average: {{ $sessionFeedback->average_rating }})</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detailed Feedback -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Detailed Feedback</h3>
                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $sessionFeedback->content }}</p>
                    </div>
                </div>

                <!-- Suggestions -->
                @if($sessionFeedback->suggestions && count($sessionFeedback->suggestions) > 0)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Improvement Suggestions</h3>
                        <ul class="space-y-3">
                            @foreach($sessionFeedback->suggestions as $suggestion)
                                <li class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-slate-700 dark:text-slate-300">{{ $suggestion }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Timeline</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Created</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @if($sessionFeedback->submitted_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">Submitted</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->submitted_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($sessionFeedback->reviewed_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">Reviewed</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $sessionFeedback->reviewed_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
