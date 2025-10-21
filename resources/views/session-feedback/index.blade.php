<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Header -->
        <div class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Session Feedback</h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Manage and review session feedback</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if(auth()->user()->user_role === 'facilitator')
                            <a href="{{ route('facilitator.clubs') }}" 
                               class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                                View Sessions
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @if(auth()->user()->user_role === 'admin')
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Club</label>
                                <select name="club_id" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">All Clubs</option>
                                    @foreach($clubs as $club)
                                        <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                            {{ $club->club_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teacher</label>
                                <select name="teacher_id" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">All Teachers</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="actioned" {{ request('status') == 'actioned' ? 'selected' : '' }}>Actioned</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                Filter
                            </button>
                            <a href="{{ request()->url() }}" class="px-4 py-2 bg-slate-300 hover:bg-slate-400 text-slate-700 font-semibold rounded-lg transition-colors">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-4">
            <div class="max-w-7xl mx-auto">
                @if($feedbacks->count() > 0)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Session</th>
                                        @if(auth()->user()->user_role === 'admin')
                                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Facilitator</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Teacher</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rating</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($feedbacks as $feedback)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                        {{ $feedback->session->title }}
                                                    </div>
                                                    <div class="text-sm text-slate-500 dark:text-slate-400">
                                                        {{ $feedback->club->club_name }}
                                                    </div>
                                                </div>
                                            </td>
                                            @if(auth()->user()->user_role === 'admin')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                                    {{ $feedback->facilitator->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                                    {{ $feedback->teacher->name }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex items-center space-x-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= $feedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                                        {{ $feedback->overall_rating }}/5
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $feedback->feedback_type === 'positive' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $feedback->feedback_type === 'constructive' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $feedback->feedback_type === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $feedback->feedback_type === 'mixed' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                    {{ ucfirst($feedback->feedback_type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $feedback->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $feedback->status === 'submitted' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $feedback->status === 'reviewed' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $feedback->status === 'actioned' ? 'bg-purple-100 text-purple-800' : '' }}">
                                                    {{ ucfirst($feedback->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                {{ $feedback->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route(auth()->user()->user_role . '.feedback.show', $feedback) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    View
                                                </a>
                                                @if($feedback->status === 'draft' && $feedback->facilitator_id === auth()->id())
                                                    <a href="{{ route('facilitator.feedback.edit', $feedback) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        Edit
                                                    </a>
                                                @endif
                                                @if(auth()->user()->user_role === 'admin')
                                                    <form method="POST" action="{{ route('admin.feedback.destroy', $feedback) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                                                onclick="return confirm('Are you sure you want to delete this feedback?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                            {{ $feedbacks->links() }}
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No Feedback Found</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">
                            @if(auth()->user()->user_role === 'facilitator')
                                You haven't submitted any session feedback yet.
                            @elseif(auth()->user()->user_role === 'teacher')
                                No feedback has been submitted about your sessions yet.
                            @else
                                No session feedback has been submitted yet.
                            @endif
                        </p>
                        @if(auth()->user()->user_role === 'facilitator')
                            <a href="{{ route('facilitator.clubs') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                View Sessions
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
