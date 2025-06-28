<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Kos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durasi = $this->faker->numberBetween(1, 12);
        $kos = Kos::factory()->create();
        
        return [
            'user_id' => User::factory(),
            'kos_id' => $kos->id,
            'tanggal_mulai' => $this->faker->dateTimeBetween('now', '+1 month'),
            'durasi' => $durasi,
            'total_harga' => $kos->harga * $durasi,
            'status_pemesanan' => $this->faker->randomElement(['pending', 'confirmed', 'canceled']),
        ];
    }

    /**
     * Indicate that the booking is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pemesanan' => 'pending',
        ]);
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pemesanan' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the booking is canceled.
     */
    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pemesanan' => 'canceled',
        ]);
    }
}
