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
            'address' => $this->faker->address,
            'contact_email' => $this->faker->safeEmail,
        ];
    }
}
