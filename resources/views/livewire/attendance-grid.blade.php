<div>
	<div class="flex items-center justify-between mb-3">
		<h2 class="text-lg font-semibold">Attendance - Week {{ $week }}</h2>
		<button wire:click="save_attendance" class="btn btn-primary">Save</button>
	</div>
	<table class="min-w-full bg-white rounded shadow">
		<thead>
			<tr>
				<th class="px-4 py-2 text-left">Student</th>
				<th class="px-4 py-2 text-left">Status</th>
			</tr>
		</thead>
		<tbody>
			@foreach($club->students as $student)
				<tr class="border-t">
					<td class="px-4 py-2">{{ $student->student_first_name }} {{ $student->student_last_name }}</td>
					<td class="px-4 py-2">
						<select wire:model.defer="attendance_status_by_student.{{ $student->id }}" class="input">
							<option value="">-</option>
							<option value="present">Present</option>
							<option value="absent">Absent</option>
							<option value="late">Late</option>
						</select>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>


