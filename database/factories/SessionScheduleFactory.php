<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\SessionSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionSchedule>
 */
class SessionScheduleFactory extends Factory
{
    protected $model = SessionSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'club_id' => Club::factory(),
            'session_week_number' => fake()->numberBetween(1, 12),
            'session_date' => fake()->dateTimeBetween('now', '+3 months'),
        ];
    }

    /**
     * Indicate that the session belongs to a specific club.
     */
    public function forClub(Club $club): static
    {
        return $this->state(fn () => [
            'club_id' => $club->id,
        ]);
    }

    /**
     * Indicate that the session is for a specific week number.
     */
    public function forWeek(int $weekNumber): static
    {
        return $this->state(fn () => [
            'session_week_number' => $weekNumber,
        ]);
    }
}