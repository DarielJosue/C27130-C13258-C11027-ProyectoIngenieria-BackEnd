<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyUser>
 */
class CompanyUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => null, // or a specific company ID if needed
            'name' => fake()->firstName(),
            'username' => fake()->userName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'), // Default password, can be overridden
            'role' => 'default_role', // Default role, can be overridden
            'active' => true,
            'register_date' => now(),
        ];
    }
}