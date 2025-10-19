@extends('layouts.student')

@section('page-title', 'My Reports')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 fs:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-white via-blue-100 to-indigo-100 bg-clip-text text-transparent">Reports Access</h1>
            <p class="mt-2 text-slate-300">Report access is managed by your instructors</p>
        </div>

        <!-- Access Restricted Message -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl shadow-xl p-12 text-center border border-slate-600">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-lock text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-4">Reports Access Restricted</h2>
                <p class="text-slate-300 mb-6">
                    Your performance reports are managed by your instructors and administrators. 
                    You can view your progress through assignments and assessments in your dashboard.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('student.dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Back to Dashboard
                    </a>
                    <a href="{{ route('student.assignments') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-tasks mr-2"></i>
                        View Assignments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
