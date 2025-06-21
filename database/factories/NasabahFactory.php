<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nasabah>
 */
class NasabahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => \App\Models\User::factory(),
            'nama' => $this->faker->name,
            'nik' => $this->faker->unique()->numerify('################'),
            'alamat' => $this->faker->address,
            'telepon' => $this->faker->phoneNumber,
            'status_blacklist' => $this->faker->boolean,
        ];
    }
}
