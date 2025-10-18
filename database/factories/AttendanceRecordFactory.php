<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\SessionSchedule;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'session_id' => SessionSchedule::factory(),
            'student_id' => Student::factory(),
            'attendance_status' => fake()->randomElement(['present', 'absent', 'late', 'excused']),
            'attendance_notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the attendance record is for a specific session and student.
     */
    public function forSessionAndStudent(SessionSchedule $session, Student $student): static
    {
        return $this->state(fn () => [
            'session_id' => $session->id,
            'student_id' => $student->id,
        ]);
    }

    /**
     * Indicate that the student was present.
     */
    public function present(): static
    {
        return $this->state(fn () => [
            'attendance_status' => 'present',
        ]);
    }

    /**
     * Indicate that the student was absent.
     */
    public function absent(): static
    {
        return $this->state(fn () => [
            'attendance_status' => 'absent',
        ]);
    }

    /**
     * Indicate that the student was late.
     */
    public function late(): static
    {
        return $this->state(fn () => [
            'attendance_status' => 'late',
        ]);
    }

    /**
     * Indicate that the student was excused.
     */
    public function excused(): static
    {
        return $this->state(fn () => [
            'attendance_status' => 'excused',
        ]);
    }
}