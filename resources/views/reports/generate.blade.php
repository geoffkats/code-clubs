@extends('layouts.admin')
@section('title', 'Generate Reports')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             reportType: 'progress',
             dateRange: 'month',
             includeCharts: true,
             generateReport: function() {
                 // This would trigger the actual report generation
                 console.log('Generating report...');
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
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-green-900 to-emerald-900 dark:from-white dark:via-green-100 dark:to-emerald-100 bg-clip-text text-transparent">
                                Generate Report
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Create comprehensive reports for your club</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button @click="generateReport()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <div class="max-w-4xl mx-auto">
                <form method="post" action="{{ route('reports.generate', ['club_id' => request('club_id')]) }}" class="space-y-8">
                    @csrf
                    
                    <!-- Report Configuration -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Report Configuration</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Report Type</label>
                                <select name="report_type" x-model="reportType" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                    <option value="progress">Progress Report</option>
                                    <option value="attendance">Attendance Report</option>
                                    <option value="assessment">Assessment Report</option>
                                    <option value="comprehensive">Comprehensive Report</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Date Range</label>
                                <select name="date_range" x-model="dateRange" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                    <option value="week">Last Week</option>
                                    <option value="month">Last Month</option>
                                    <option value="quarter">Last Quarter</option>
                                    <option value="year">Last Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Format</label>
                                <select name="format" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                    <option value="pdf">PDF Document</option>
                                    <option value="excel">Excel Spreadsheet</option>
                                    <option value="csv">CSV File</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Include Charts</label>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="include_charts" x-model="includeCharts" class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Include visual charts and graphs</span>
                                </div>
                            </div>
                        </div>
                        
                        <div x-show="dateRange === 'custom'" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Start Date</label>
                                <input type="date" name="start_date" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">End Date</label>
                                <input type="date" name="end_date" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Report Sections -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Include Sections</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="overview" checked class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Club Overview</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="students" checked class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Student List</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="attendance" checked class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Attendance Summary</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="assessments" checked class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Assessment Results</span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="progress" checked class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Progress Tracking</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="recommendations" class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Recommendations</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="next_steps" class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Next Steps</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="sections[]" value="appendix" class="w-4 h-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Detailed Appendix</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Preview -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Report Preview</h2>
                        
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Club Overview - Basic information and statistics</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Student List - All enrolled students with details</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Attendance Summary - Weekly attendance patterns</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Assessment Results - Test scores and performance</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Progress Tracking - Individual student progress</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('clubs.show', request('club_id')) }}" class="px-6 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
