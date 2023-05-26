<?php

namespace Database\Factories;

use App\Models\WorkLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkLog>
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
        $hrs = fake()->numberBetween(1, 4);
        return [
            'rate' => $rate,
            'hrs' => $hrs,
            'total' => $rate * $hrs,
        ];
    }
}
