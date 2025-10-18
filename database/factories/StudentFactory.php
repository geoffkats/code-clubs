<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_first_name' => $this->faker->firstName,
            'student_last_name' => $this->faker->lastName,
            'student_grade_level' => (string) $this->faker->numberBetween(1, 12),
            'student_parent_name' => $this->faker->name,
            'student_parent_email' => $this->faker->safeEmail,
        ];
    }
}
