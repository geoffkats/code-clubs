@extends('layouts.admin')

@section('title', 'Report Approval Workflow')

@section('content')
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @livewire('report-approval-workflow')
        </div>
    </div>
@endsection
