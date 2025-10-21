@extends('layouts.admin')

@section('title', 'Reports Management')

@section('content')
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Main Reports Dashboard -->
            <div class="mb-8">
                @include('reports.index')
            </div>
            
            <!-- Report Approval Workflow -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Report Approval Workflow</h2>
                    <div class="text-sm text-slate-600 dark:text-slate-400">
                        Manage pending report approvals
                    </div>
                </div>
                @livewire('report-approval-workflow')
            </div>
        </div>
    </div>
@endsection
