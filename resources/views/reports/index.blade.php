<x-layouts.app :title="__('Reports')">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-lg p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">📊 Student Reports</h1>
                    <p class="text-blue-100">View and manage student performance reports</p>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 rounded-lg px-6 py-4">
                        <div class="text-2xl font-bold">{{ $reports->count() }}</div>
                        <div class="text-sm text-blue-100">Total Reports</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Club Filter -->
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Filter by Club:</label>
                    <select onchange="filterByClub(this.value)" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Clubs</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}" {{ $clubId == $club->id ? 'selected' : '' }}>
                                {{ $club->club_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Generate Reports Button -->
                @if($clubId)
                    <a href="{{ route('reports.create', ['club_id' => $clubId]) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Generate Reports
                    </a>
                @endif
            </div>
        </div>

        <!-- Debug Info (Temporary) -->
        <div class="bg-yellow-100 border border-yellow-400 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-yellow-800">Debug Info:</h3>
            <p><strong>Total Reports Found:</strong> {{ $reports->count() }}</p>
            <p><strong>Club ID Filter:</strong> {{ $clubId ?? 'None' }}</p>
            <p><strong>User School ID:</strong> {{ auth()->user()->school_id }}</p>
            @if($reports->count() > 0)
                <p><strong>First Report Club ID:</strong> {{ $reports->first()->club_id }}</p>
                <p><strong>First Report Club School ID:</strong> {{ $reports->first()->club->school_id }}</p>
            @endif
        </div>

        <!-- Reports Grid -->
        @if($reports->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reports as $report)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow">
                        <!-- Report Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</h3>
                                    <p class="text-blue-100 text-sm">{{ $report->club->club_name }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold">{{ round($report->report_overall_score) }}%</div>
                                    <div class="text-xs text-blue-100">Score</div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Content -->
                        <div class="p-6">
                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ round($report->report_overall_score) }}%</div>
                                    <div class="text-sm text-gray-500">Overall Score</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">
                                        @if($report->report_generated_at)
                                            @if(is_string($report->report_generated_at))
                                                {{ \Carbon\Carbon::parse($report->report_generated_at)->format('M j') }}
                                            @else
                                                {{ $report->report_generated_at->format('M j') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">Generated</div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-1000" 
                                         style="width: {{ $report->report_overall_score }}%"></div>
                                </div>
                            </div>

                            <!-- Summary Preview -->
                            <div class="mb-6">
                                <p class="text-gray-600 text-sm line-clamp-3">{{ Str::limit($report->report_summary_text, 100) }}</p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('reports.show', $report->id) }}" 
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                    View Report
                                </a>
                                <form method="post" action="{{ route('reports.send', $report->id) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Send to Parent
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Report Footer -->
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>
                                    @if($report->report_generated_at)
                                        @if(is_string($report->report_generated_at))
                                            {{ \Carbon\Carbon::parse($report->report_generated_at)->format('M j, Y') }}
                                        @else
                                            {{ $report->report_generated_at->format('M j, Y') }}
                                        @endif
                                    @else
                                        Not generated
                                    @endif
                                </span>
                                @if($report->access_code)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Access Code Generated</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">No Access Code</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reports Found</h3>
                <p class="text-gray-600 mb-6">
                    @if($clubId)
                        No reports have been generated for this club yet.
                    @else
                        No reports have been generated yet. Select a club to generate reports.
                    @endif
                </p>
                @if($clubId)
                    <a href="{{ route('reports.create', ['club_id' => $clubId]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Generate Reports
                    </a>
                @else
                    <p class="text-sm text-gray-500">Select a club from the filter above to generate reports.</p>
                @endif
            </div>
        @endif
    </div>

    <!-- JavaScript for filtering -->
    <script>
        function filterByClub(clubId) {
            if (clubId) {
                window.location.href = '{{ route("reports.index") }}?club_id=' + clubId;
            } else {
                window.location.href = '{{ route("reports.index") }}';
            }
        }
    </script>

    <!-- Success Message -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.top-4.right-4').remove();
            }, 5000);
        </script>
    @endif
</x-layouts.app>
