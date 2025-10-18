<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    protected $model = School::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_name' => fake()->company() . ' School',
            'address' => fake()->address(),
            'contact_email' => fake()->unique()->safeEmail(),
        ];
    }

    /**
     * Indicate that the school has a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn () => [
            'school_name' => $name,
        ]);
    }
}