<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_first_name' => fake()->firstName(),
            'student_last_name' => fake()->lastName(),
            'student_grade_level' => fake()->randomElement(['K', '1', '2', '3', '4', '5', '6', '7', '8']),
            'student_parent_name' => fake()->name(),
            'student_parent_email' => fake()->safeEmail(),
        ];
    }

    /**
     * Indicate that the student belongs to a specific school.
     */
    public function forSchool(School $school): static
    {
        return $this->state(fn () => [
            'school_id' => $school->id,
        ]);
    }

    /**
     * Indicate that the student has a specific grade level.
     */
    public function inGrade(string $grade): static
    {
        return $this->state(fn () => [
            'student_grade_level' => $grade,
        ]);
    }
}