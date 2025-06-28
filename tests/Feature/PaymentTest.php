<?php

use App\Models\User;
use App\Models\Kos;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\QrisService;

beforeEach(function () {
    // Create a user with tenant role
    $this->user = User::factory()->create([
        'role' => 'tenant'
    ]);

    // Create an owner
    $this->owner = User::factory()->create([
        'role' => 'owner'
    ]);

    // Create a kos property
    $this->kos = Kos::factory()->create([
        'pemilik_id' => $this->owner->id,
        'status' => true
    ]);

    // Create a confirmed booking
    $this->booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'kos_id' => $this->kos->id,
        'status_pemesanan' => 'confirmed',
        'tanggal_mulai' => now()->addDays(7),
        'durasi' => 3,
        'total_harga' => 3000000
    ]);
});

test('user can create payment for confirmed booking', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('payments.create', $this->booking));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Check that payment was created
    $this->assertDatabaseHas('payments', [
        'booking_id' => $this->booking->id,
        'metode_pembayaran' => 'QRIS',
        'jumlah' => $this->booking->total_harga,
        'status' => 'pending'
    ]);
});

test('user cannot create payment for pending booking', function () {
    $this->booking->update(['status_pemesanan' => 'pending']);
    $this->actingAs($this->user);

    $response = $this->get(route('payments.create', $this->booking));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('user can view payment page', function () {
    $payment = Payment::factory()->create([
        'booking_id' => $this->booking->id,
        'status' => 'pending',
        'expires_at' => now()->addMinutes(30)
    ]);

    $this->actingAs($this->user);

    $response = $this->get(route('payments.show', $payment));

    $response->assertStatus(200);
    $response->assertViewIs('payments.show');
    $response->assertViewHas('payment', $payment);
});

test('user can complete payment', function () {
    $payment = Payment::factory()->create([
        'booking_id' => $this->booking->id,
        'status' => 'pending',
        'expires_at' => now()->addMinutes(30)
    ]);

    $this->actingAs($this->user);

    $response = $this->post(route('payments.complete', $payment));

    $response->assertRedirect(route('payments.success', $payment));

    $payment->refresh();
    expect($payment->status)->toBe('completed');
    expect($payment->paid_at)->not->toBeNull();
    expect($payment->transaction_id)->not->toBeNull();
});

test('qris service generates fallback qr code', function () {
    $payment = Payment::factory()->create([
        'booking_id' => $this->booking->id,
        'jumlah' => 1500000,
        'payment_reference' => 'QRIS-TEST-123'
    ]);

    $qrisService = new QrisService();
    $qrCode = $qrisService->generateQRCode($payment);

    expect($qrCode)->not->toBeEmpty();
    expect($qrCode)->toBeString();

    // Decode and check if it contains SVG
    $decoded = base64_decode($qrCode);
    expect($decoded)->toContain('svg');
    expect($decoded)->toContain('QRIS Payment');
});
