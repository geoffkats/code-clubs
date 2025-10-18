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

                <!-- Summary Text -->
                <div>
                    <label for="report_summary_text" class="block text-sm font-semibold text-slate-700 mb-2">
                        Report Summary
                    </label>
                    <textarea id="report_summary_text" 
                              name="report_summary_text" 
                              rows="8"
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-medium text-slate-700 resize-none"
                              placeholder="Enter detailed report summary..."
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
