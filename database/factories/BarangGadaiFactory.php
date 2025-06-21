<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarangGadai>
 */
class BarangGadaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_nasabah' => \App\Models\Nasabah::factory(),
            'no_bon' => $this->faker->unique()->numerify('BON########'),
            'nama_barang' => $this->faker->word . ' ' . $this->faker->lastName,
            'deskripsi' => $this->faker->sentence,
            'harga_gadai' => $this->faker->numberBetween(1000000, 10000000),
            'tanggal_gadai' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'tanggal_jatuh_tempo' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement(['tergadai', 'ditebus', 'dilelang']),
            'id_kategori' => null, // Assuming this can be null or requires a KategoriBarang factory
            'id_user' => \App\Models\User::factory(),
            'id_bunga_tenor' => \App\Models\BungaTenor::factory(),
        ];
    }
}
