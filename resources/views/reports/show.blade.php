@extends('layouts.admin')
@section('title', 'Report Details')

@section('content')
	<div class="px-6 py-4 max-w-3xl">
		<h1 class="text-xl font-semibold mb-2">Report: {{ $report->report_name }}</h1>
		<p class="text-sm text-gray-600 mb-4">Student: {{ $report->student->student_first_name }} {{ $report->student->student_last_name }} | Club: {{ $report->club->club_name }}</p>
		<div class="bg-white rounded shadow p-4 space-y-3">
			<p>{{ $report->report_summary_text }}</p>
			<p><strong>Overall score:</strong> {{ $report->report_overall_score }}%</p>
			<form method="post" action="{{ route('reports.send', ['report_id' => $report->id]) }}">
				@csrf
				<button type="submit" class="btn btn-primary">Send to Parent</button>
			</form>
		</div>
	</div>
@endsection


