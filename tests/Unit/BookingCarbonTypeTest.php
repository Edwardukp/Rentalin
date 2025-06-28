<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\User;
use App\Models\Kos;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingCarbonTypeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kos;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user
        $this->user = User::factory()->create();
        
        // Create a kos
        $this->kos = Kos::factory()->create([
            'pemilik_id' => $this->user->id,
            'harga' => 1000000,
            'status' => true,
        ]);
    }

    /** @test */
    public function it_handles_string_durasi_values_correctly()
    {
        // Create booking with string durasi (simulating form input)
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'kos_id' => $this->kos->id,
            'tanggal_mulai' => '2024-01-01',
            'durasi' => '3', // String value
            'total_harga' => 3000000,
            'status_pemesanan' => 'pending',
        ]);

        // Refresh to get the actual stored values
        $booking->refresh();

        // Assert durasi is properly cast to integer
        $this->assertIsInt($booking->durasi);
        $this->assertEquals(3, $booking->durasi);
    }

    /** @test */
    public function it_calculates_end_date_without_mutating_original_date()
    {
        $startDate = Carbon::parse('2024-01-01');
        
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'kos_id' => $this->kos->id,
            'tanggal_mulai' => $startDate->copy(),
            'durasi' => 3,
            'total_harga' => 3000000,
            'status_pemesanan' => 'pending',
        ]);

        $originalDate = $booking->tanggal_mulai->copy();
        
        // Call getEndDateAttribute multiple times
        $endDate1 = $booking->end_date;
        $endDate2 = $booking->end_date;
        
        // Original date should not be mutated
        $this->assertEquals($originalDate, $booking->tanggal_mulai);
        
        // End dates should be consistent
        $this->assertEquals($endDate1, $endDate2);
        
        // End date should be correct
        $expectedEndDate = $originalDate->copy()->addMonths(3);
        $this->assertEquals($expectedEndDate, $endDate1);
    }

    /** @test */
    public function it_handles_numeric_string_durasi_in_mutator()
    {
        $booking = new Booking();
        
        // Test various string formats
        $booking->durasi = '5';
        $this->assertEquals(5, $booking->durasi);
        
        $booking->durasi = '10';
        $this->assertEquals(10, $booking->durasi);
        
        // Test with actual integer
        $booking->durasi = 7;
        $this->assertEquals(7, $booking->durasi);
    }

    /** @test */
    public function it_prevents_carbon_type_errors_in_date_calculations()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'kos_id' => $this->kos->id,
            'tanggal_mulai' => '2024-01-01',
            'durasi' => '6', // String value that could cause type error
            'total_harga' => 6000000,
            'status_pemesanan' => 'pending',
        ]);

        // This should not throw a Carbon type error
        $endDate = $booking->end_date;
        
        $this->assertInstanceOf(Carbon::class, $endDate);
        $this->assertEquals('2024-07-01', $endDate->format('Y-m-d'));
    }

    /** @test */
    public function it_works_with_blade_template_style_calls()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'kos_id' => $this->kos->id,
            'tanggal_mulai' => '2024-01-01',
            'durasi' => '4',
            'total_harga' => 4000000,
            'status_pemesanan' => 'pending',
        ]);

        // Simulate what happens in blade templates
        $originalDate = $booking->tanggal_mulai;
        
        // This should work without type errors (like in the fixed blade templates)
        $checkoutDate = $booking->tanggal_mulai->copy()->addMonths((int) $booking->durasi);
        
        // Original date should not be mutated
        $this->assertEquals($originalDate, $booking->tanggal_mulai);
        
        // Checkout date should be correct
        $this->assertEquals('2024-05-01', $checkoutDate->format('Y-m-d'));
    }
}
