@extends('layouts.admin')
@section('title', 'Teacher Proofs Analytics')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <a href="{{ route('admin.proofs.index') }}" 
                               class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white mr-4">
                                ← Back to Proofs
                            </a>
                        </div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            📊 Teacher Proofs Analytics
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400">
                            Comprehensive analytics and insights for teacher session proofs
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="exportAnalytics()" 
                                class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            📊 Export Excel
                        </button>
                        <button onclick="exportPDF()" 
                                class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            📄 Export PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Proofs</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['total_proofs'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Approval Rate</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['approval_rate'] }}%</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending Review</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_proofs'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Rejected</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['rejected_proofs'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Proofs by Club -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Proofs by Club</h3>
                    <div class="space-y-4">
                        @forelse($proofsByClub as $clubName => $clubStats)
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-slate-900 dark:text-white">{{ $clubName }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $clubStats['total'] }} total proofs
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        {{ $clubStats['approved'] }} approved
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                                        {{ $clubStats['pending'] }} pending
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                        {{ $clubStats['rejected'] }} rejected
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-center py-8">No club data available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Proofs by Teacher -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Proofs by Teacher</h3>
                    <div class="space-y-4">
                        @forelse($proofsByTeacher as $teacherName => $teacherStats)
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-slate-900 dark:text-white">{{ $teacherName }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $teacherStats['total'] }} total proofs
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        {{ $teacherStats['approved'] }} approved
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                                        {{ $teacherStats['pending'] }} pending
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                        {{ $teacherStats['rejected'] }} rejected
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-center py-8">No teacher data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Proofs -->
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Recent Proof Submissions</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Club</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($recentProofs as $proof)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $proof->uploader->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900 dark:text-white">
                                            {{ $proof->session->club->club_name ?? 'Unknown Club' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900 dark:text-white">
                                            {{ $proof->isImage() ? '📷 Photo' : '🎥 Video' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($proof->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($proof->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($proof->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $proof->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900 dark:text-white">
                                            {{ $proof->created_at->format('M d, Y h:i A') }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-slate-500 dark:text-slate-400">
                                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium mb-2">No recent proofs</p>
                                            <p class="text-sm">No teacher proofs have been submitted recently.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportAnalytics() {
            // Create a simple CSV export
            const stats = @json($stats);
            const proofsByClub = @json($proofsByClub);
            const proofsByTeacher = @json($proofsByTeacher);
            
            let csvContent = "Proof Analytics Report\n\n";
            csvContent += "Summary Statistics\n";
            csvContent += `Total Proofs,${stats.total_proofs}\n`;
            csvContent += `Approval Rate,${stats.approval_rate}%\n`;
            csvContent += `Pending Proofs,${stats.pending_proofs}\n`;
            csvContent += `Rejected Proofs,${stats.rejected_proofs}\n\n`;
            
            csvContent += "Proofs by Club\n";
            csvContent += "Club Name,Total,Approved,Pending,Rejected\n";
            Object.entries(proofsByClub).forEach(([club, data]) => {
                csvContent += `${club},${data.total},${data.approved},${data.pending},${data.rejected}\n`;
            });
            
            csvContent += "\nProofs by Teacher\n";
            csvContent += "Teacher Name,Total,Approved,Pending,Rejected\n";
            Object.entries(proofsByTeacher).forEach(([teacher, data]) => {
                csvContent += `${teacher},${data.total},${data.approved},${data.pending},${data.rejected}\n`;
            });
            
            // Download the CSV file
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'proof-analytics-' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        function exportPDF() {
            // Simple PDF export using browser print
            window.print();
        }
    </script>
@endsection
