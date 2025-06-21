<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BungaTenor>
 */
class BungaTenorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenor' => $this->faker->randomElement([7, 14, 30]),
            'bunga_percent' => $this->faker->numberBetween(5, 20),
        ];
    }
}
