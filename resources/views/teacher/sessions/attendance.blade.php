@extends('layouts.teacher')

@section('title', 'Session Attendance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('teacher.sessions.show', $session) }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Session Attendance</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">
                    {{ $session->club->club_name ?? 'Unknown Club' }} - 
                    {{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('M d, Y') : 'Unknown Date' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Attendance Form -->
    <form action="{{ route('teacher.sessions.attendance.update', $session) }}" method="POST">
        @csrf
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Mark Attendance</h2>
            </div>
            
            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($session->club->students as $student)
                    @php
                        $attended = isset($attendanceRecords[$student->id]);
                    @endphp
                    <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </div>
                                    @if($attended)
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            Attended: {{ \Carbon\Carbon::parse($attendanceRecords[$student->id]->attended_at)->format('M d, Y h:i A') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" 
                                           id="attendance_present_{{ $student->id }}" 
                                           name="attendance[{{ $student->id }}]" 
                                           value="present"
                                           class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 focus:ring-blue-500 focus:ring-2"
                                           {{ $attended ? 'checked' : '' }}>
                                    <label for="attendance_present_{{ $student->id }}" class="text-sm font-medium text-green-700 dark:text-green-400">
                                        Present
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" 
                                           id="attendance_absent_{{ $student->id }}" 
                                           name="attendance[{{ $student->id }}]" 
                                           value="absent"
                                           class="w-4 h-4 text-red-600 bg-slate-100 border-slate-300 focus:ring-red-500 focus:ring-2"
                                           {{ !$attended ? 'checked' : '' }}>
                                    <label for="attendance_absent_{{ $student->id }}" class="text-sm font-medium text-red-700 dark:text-red-400">
                                        Absent
                                    </label>
                                </div>
                            </div>
                            <div class="w-64">
                                <input type="text" 
                                       name="notes[{{ $student->id }}]" 
                                       placeholder="Notes (optional)" 
                                       value="{{ $attended && $attendanceRecords[$student->id]->notes ? $attendanceRecords[$student->id]->notes : '' }}"
                                       class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No students enrolled in this club.</p>
                    </div>
                @endforelse
            </div>

            @if($session->club->students->count() > 0)
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('teacher.sessions.show', $session) }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Attendance
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection
