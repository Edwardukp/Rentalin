<?php

namespace Database\Factories;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kos>
 */
class KosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pemilik_id' => User::factory(),
            'nama_kos' => $this->faker->company . ' Kos',
            'alamat' => $this->faker->address,
            'harga' => $this->faker->numberBetween(500000, 2000000),
            'fasilitas' => $this->faker->paragraph,
            'foto' => ['default.jpg'],
            'status' => true,
            'google_maps_url' => 'https://maps.google.com/?q=' . urlencode($this->faker->address),
        ];
    }

    /**
     * Indicate that the kos is not available.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
