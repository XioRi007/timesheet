<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkLog>
 */
class WorkLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rate = fake()->numberBetween(0, 999.99);
        $hrs = fake()->numberBetween(1, 12);
        return [
            'rate' => $rate,
            'hrs' => $hrs,
            'total' => $rate * $hrs,
        ];
    }
}
