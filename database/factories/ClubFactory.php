<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'club_name' => $this->faker->words(3, true),
            'club_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'club_duration_weeks' => $this->faker->numberBetween(6, 16),
            'club_start_date' => $this->faker->date(),
        ];
    }
}
