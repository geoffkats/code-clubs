<div>
	<div class="flex items-center justify-between mb-3">
		<h2 class="text-lg font-semibold">Scores</h2>
		<button wire:click="save_scores" class="btn btn-primary">Save</button>
	</div>
	<table class="min-w-full bg-white rounded shadow">
		<thead>
			<tr>
				<th class="px-4 py-2 text-left">Student</th>
				<th class="px-4 py-2 text-left">Score</th>
				<th class="px-4 py-2 text-left">Max</th>
			</tr>
		</thead>
		<tbody>
			@foreach($assessment->club->students as $student)
				<tr class="border-t">
					<td class="px-4 py-2">{{ $student->student_first_name }} {{ $student->student_last_name }}</td>
					<td class="px-4 py-2">
						<input type="number" step="0.01" wire:model.defer="score_value_by_student.{{ $student->id }}" class="input w-28">
					</td>
					<td class="px-4 py-2">
						<input type="number" step="0.01" wire:model.defer="score_max_value_by_student.{{ $student->id }}" class="input w-28">
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>


