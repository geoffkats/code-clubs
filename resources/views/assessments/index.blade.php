<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-amber-50 to-orange-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             showCreate: false,
             showAI: false,
             selectedClub: '',
             clubs: @js($clubs ?? []),
             handleAISubmit(event) {
                 if (!this.selectedClub) {
                     alert('Please select a club first');
                     event.preventDefault();
                     return;
                 }
                 this.$el.action = `/clubs/${this.selectedClub}/assessments/ai-generate`;
                 this.showAI = false;
             }
         }">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-amber-900 to-orange-900 dark:from-white dark:via-amber-100 dark:to-orange-100 bg-clip-text text-transparent">
                                Assessments
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Create and manage quizzes, tests, and assignments</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="showCreate = true" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Assessment
                        </button>
                        <button @click="showAI = !showAI" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            AI Generate
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
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Assessments</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessments->total() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">This Month</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessments->where('created_at', '>=', now()->startOfMonth())->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessments->where('due_date', '>=', now())->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Clubs</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $clubs->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessments Grid -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">All Assessments</h2>
                </div>
                
                @if($assessments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-6">
                        @foreach($assessments as $assessment)
                            <div class="group relative bg-gradient-to-br from-white via-slate-50 to-slate-100 dark:from-slate-800 dark:via-slate-700 dark:to-slate-600 rounded-2xl border border-slate-200/60 dark:border-slate-600/60 p-6 hover:shadow-2xl hover:shadow-amber-500/10 hover:-translate-y-2 transition-all duration-500 ease-out overflow-hidden">
                                <!-- Decorative Background -->
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-400/20 to-orange-500/20 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-purple-400/20 to-pink-500/20 rounded-full translate-y-12 -translate-x-12 group-hover:scale-125 transition-transform duration-700 ease-out"></div>
                                
                                <!-- Assessment Type Icon -->
                                <div class="relative z-10 flex items-start justify-between mb-6">
                                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        @switch($assessment->assessment_type)
                                            @case('quiz')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @break
                                            @case('assignment')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                @break
                                            @case('test')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                </svg>
                                                @break
                                            @case('project')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 text-xs font-semibold bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full shadow-sm">
                                            {{ ucfirst($assessment->assessment_type) }}
                                        </span>
                                        @if($assessment->due_date)
                                            <span class="px-3 py-1 text-xs font-medium bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-full shadow-sm">
                                                {{ \Carbon\Carbon::parse($assessment->due_date)->format('M j') }}
                                            </span>
                                        @endif
                                        <span class="px-3 py-1 text-xs font-medium bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200 rounded-full shadow-sm" title="{{ ($assessment->scores->count() ?? 0) }} submissions">
                                            {{ $assessment->scores->count() ?? 0 }} submissions
                                        </span>
                                    </div>
                                </div>

                                <!-- Assessment Content -->
                                <div class="relative z-10 space-y-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-300">
                                            {{ $assessment->assessment_name }}
                                        </h3>
                                        <div class="flex items-center text-slate-600 dark:text-slate-400 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                            {{ $assessment->club->club_name ?? 'No Club' }}
                                    </div>
                                    </div>

                                    @if($assessment->description)
                                        <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                            {{ $assessment->description }}
                                        </p>
                                    @endif

                                    <!-- Stats Row -->
                                    <div class="flex items-center justify-between py-3 border-t border-slate-200/50 dark:border-slate-600/50">
                                        <div class="flex items-center space-x-4">
                                            @if($assessment->questions->count() > 0)
                                                <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900 dark:to-teal-900 rounded-lg flex items-center justify-center mr-2">
                                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="font-medium">{{ $assessment->questions->count() }}</span>
                                                    <span class="ml-1">Questions</span>
                                                </div>
                                            @endif
                                            @if($assessment->total_points)
                                                <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900 dark:to-pink-900 rounded-lg flex items-center justify-center mr-2">
                                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="font-medium">{{ $assessment->total_points }}</span>
                                                    <span class="ml-1">Points</span>
                                                </div>
                                    @endif
                                </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-lg">
                                            {{ $assessment->created_at->format('M j, Y') }}
                                    </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="relative z-10 flex items-center justify-between pt-4 border-t border-slate-200/50 dark:border-slate-600/50 mt-6">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('assessments.show', $assessment->id) }}" class="group/btn px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-indigo-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View
                                            </span>
                                        </a>
                                        <a href="{{ route('assessments.edit', $assessment->id) }}" class="group/btn px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                                Edit
                                            </span>
                                        </a>
                                        <a href="{{ route('assessments.duplicate', $assessment->id) }}" class="group/btn px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-teal-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                Duplicate
                                            </span>
                                        </a>
                                    </div>
                                    <form method="POST" action="{{ route('assessments.destroy', $assessment->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this assessment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="group/btn px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-pink-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900 dark:to-orange-900 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Assessments Yet</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Create your first assessment to get started with quizzes, tests, and assignments.</p>
                        <button @click="showCreate = true" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            Create First Assessment
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Assessment Modal -->
        <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Create New Assessment</h3>
                    <button @click="showCreate=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="createAssessmentForm" method="post" action="#" class="p-6 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Club</label>
                        <select name="club_id" id="clubSelect" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">Select a Club</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->club_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Name</label>
                            <input name="assessment_name" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Type</label>
                            <select name="assessment_type" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                <option value="quiz">Quiz</option>
                                <option value="assignment">Assignment</option>
                                <option value="test">Test</option>
                                <option value="project">Project</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total Points</label>
                            <input name="total_points" type="number" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Due Date</label>
                            <input name="due_date" type="date" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" @click="showCreate=false" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" form="createAssessmentForm" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            Create Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- AI Generate Assessment Modal -->
        <div x-show="showAI" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">AI Generate Assessment</h3>
                    <button @click="showAI=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="POST" action="#" @submit="handleAISubmit($event)">
                    @csrf
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Club</label>
                            <select x-model="selectedClub" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Select a Club</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->club_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Programming Topic</label>
                            <select name="topic" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <optgroup label="Scratch Programming">
                                    <option value="scratch_basics">Scratch Basics</option>
                                    <option value="scratch_animations">Scratch Animations</option>
                                    <option value="scratch_games">Scratch Games</option>
                                    <option value="scratch_storytelling">Scratch Storytelling</option>
                                </optgroup>
                                <optgroup label="Python Programming">
                                    <option value="python_basics">Python Basics</option>
                                    <option value="python_loops">Python Loops</option>
                                    <option value="python_functions">Python Functions</option>
                                    <option value="python_data_structures">Python Data Structures</option>
                                </optgroup>
                                <optgroup label="Robotics">
                                    <option value="robotics_basics">Robotics Basics</option>
                                    <option value="robotics_sensors">Robotics Sensors</option>
                                    <option value="robotics_programming">Robotics Programming</option>
                                </optgroup>
                                <optgroup label="Web Development">
                                    <option value="html_basics">HTML Basics</option>
                                    <option value="css_styling">CSS Styling</option>
                                    <option value="javascript_basics">JavaScript Basics</option>
                                </optgroup>
                                <optgroup label="Mobile Development">
                                    <option value="mobile_app_basics">Mobile App Basics</option>
                                    <option value="mobile_ui_design">Mobile UI Design</option>
                                </optgroup>
                                <optgroup label="Game Development">
                                    <option value="game_design">Game Design</option>
                                    <option value="game_mechanics">Game Mechanics</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Difficulty Level</label>
                                <select name="difficulty" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Type</label>
                                <select name="assessment_type" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="quiz">Quiz</option>
                                    <option value="assignment">Assignment</option>
                                    <option value="test">Test</option>
                                    <option value="project">Project</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Number of Questions</label>
                                <input name="question_count" type="number" min="3" max="20" value="5" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" @click="showAI = false" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                            🤖 Generate Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle form submission for create assessment
        document.getElementById('createAssessmentForm').addEventListener('submit', function(e) {
            const clubId = document.getElementById('clubSelect').value;
            if (!clubId) {
                alert('Please select a club first');
                e.preventDefault();
                return;
            }
            this.action = `{{ route('assessments.store', ['club_id' => ':club_id']) }}`.replace(':club_id', clubId);
        });
    </script>
</x-layouts.app>