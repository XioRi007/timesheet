<?php

namespace Database\Factories;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Developer>
 */
class DeveloperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $user->assignRole('developer');
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'rate' => fake()->numberBetween(0, 999.99),
            'user_id' => $user->id
        ];
    }
}
