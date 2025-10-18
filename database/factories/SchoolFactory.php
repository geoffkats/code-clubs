<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        return [
            'school_name' => $this->faker->company . ' School',
            'school_city' => $this->faker->city,
            'school_state' => $this->faker->stateAbbr,
        ];
    }
}
