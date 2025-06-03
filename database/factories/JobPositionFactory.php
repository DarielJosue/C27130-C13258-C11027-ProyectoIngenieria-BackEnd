<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Interest;
use App\Models\JobPosition;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPosition>
 */
class JobPositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle,
        ];
    }
}