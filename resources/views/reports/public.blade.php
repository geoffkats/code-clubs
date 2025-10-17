<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{{ $report->report_name }}</title>
	<link rel="stylesheet" href="/build/assets/app.css">
</head>
<body class="bg-gray-50">
	<div class="max-w-3xl mx-auto px-4 py-8">
		<h1 class="text-2xl font-bold mb-2">{{ $report->report_name }}</h1>
		<p class="text-gray-600 mb-6">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }} — {{ $report->club->club_name }}</p>
		<div class="bg-white rounded shadow p-6 space-y-4">
			<div>
				<div class="text-sm text-gray-500 mb-1">Overall score</div>
				<div class="w-full bg-gray-200 rounded h-3">
					<div class="bg-green-500 h-3 rounded" style="width: {{ (float) $report->report_overall_score }}%"></div>
				</div>
				<div class="text-sm mt-1">{{ (float) $report->report_overall_score }}%</div>
			</div>
			<p class="leading-relaxed">{{ $report->report_summary_text }}</p>
		</div>
	</div>
</body>
</html>


