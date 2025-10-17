<?php

namespace App\Livewire;

use App\Models\AttendanceRecord;
use App\Models\Club;
use App\Models\SessionSchedule;
use Livewire\Component;

class AttendanceGrid extends Component
{
	public int $club_id;
	public int $week = 1;
	public array $attendance_status_by_student = [];

	public function mount(int $club_id, int $week = 1): void
	{
		$this->club_id = $club_id;
		$this->week = $week;
		$this->load_attendance();
	}

	public function load_attendance(): void
	{
		$session = SessionSchedule::firstOrCreate(
			['club_id' => $this->club_id, 'session_week_number' => $this->week],
			['session_date' => null]
		);
		$club = Club::with('students')->findOrFail($this->club_id);
		$this->attendance_status_by_student = [];
		foreach ($club->students as $student) {
			$record = AttendanceRecord::firstOrNew([
				'session_id' => $session->id,
				'student_id' => $student->id,
			]);
			$this->attendance_status_by_student[$student->id] = $record->attendance_status ?? '';
		}
	}

	public function save_attendance(): void
	{
		$session = SessionSchedule::where('club_id', $this->club_id)->where('session_week_number', $this->week)->firstOrFail();
		foreach ($this->attendance_status_by_student as $student_id => $status) {
			AttendanceRecord::updateOrCreate(
				['session_id' => $session->id, 'student_id' => $student_id],
				['attendance_status' => $status]
			);
		}
		$this->dispatch('attendance-saved');
	}

	public function render()
	{
		$club = Club::with('students')->findOrFail($this->club_id);
		return view('livewire.attendance-grid', [
			'club' => $club,
			'week' => $this->week,
		]);
	}
}


