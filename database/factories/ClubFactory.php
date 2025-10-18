<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    protected $model = Club::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'club_name' => fake()->words(3, true) . ' Club',
            'club_level' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'club_duration_weeks' => fake()->numberBetween(4, 12),
            'club_start_date' => fake()->dateTimeBetween('now', '+3 months'),
        ];
    }

    /**
     * Indicate that the club belongs to a specific school.
     */
    public function forSchool(School $school): static
    {
        return $this->state(fn () => [
            'school_id' => $school->id,
        ]);
    }

    /**
     * Indicate that the club has a specific duration.
     */
    public function withDuration(int $weeks): static
    {
        return $this->state(fn () => [
            'club_duration_weeks' => $weeks,
        ]);
    }
}