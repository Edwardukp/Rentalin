<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $booking = Booking::factory()->create();

        return [
            'booking_id' => $booking->id,
            'metode_pembayaran' => 'QRIS',
            'jumlah' => $booking->total_harga,
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'expired']),
            'tanggal' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'transaction_id' => $this->faker->optional()->regexify('[A-Z0-9]{10}'),
            'qr_code_data' => $this->faker->optional()->text(200),
            'payment_reference' => 'QRIS-' . strtoupper($this->faker->bothify('??##??##')) . '-' . time(),
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 hour'),
            'paid_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'payment_instructions' => "1. Open your mobile banking or e-wallet app\n2. Select 'Scan QR Code' or 'QRIS' option\n3. Scan the QR code displayed\n4. Complete the payment",
            'qris_metadata' => [
                'merchant_name' => 'Rentalin',
                'booking_id' => $booking->id,
                'amount' => $booking->total_harga,
                'created_at' => now()->toISOString(),
            ],
        ];
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
            'transaction_id' => null,
            'expires_at' => now()->addMinutes(30),
        ]);
    }

    /**
     * Indicate that the payment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'paid_at' => now(),
            'transaction_id' => 'TXN-' . strtoupper(uniqid()) . '-' . time(),
        ]);
    }

    /**
     * Indicate that the payment is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'expires_at' => now()->subMinutes(30),
            'paid_at' => null,
            'transaction_id' => null,
        ]);
    }

    /**
     * Indicate that the payment is failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'paid_at' => null,
            'transaction_id' => null,
        ]);
    }
}
