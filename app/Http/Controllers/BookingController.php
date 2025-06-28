<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with(['kos.owner', 'payment'])
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1|max:24',
        ]);

        $kos = Kos::findOrFail($validated['kos_id']);

        // Check if kos is available
        if (!$kos->status) {
            return back()->with('error', 'This property is not available for booking.');
        }

        // Calculate total price - ensure durasi is treated as integer
        $durasi = (int) $validated['durasi'];
        $totalHarga = $kos->harga * $durasi;

        // Check for overlapping bookings
        $startDate = Carbon::parse($validated['tanggal_mulai']);
        $endDate = $startDate->copy()->addMonths($durasi);

        $overlappingBookings = Booking::where('kos_id', $kos->id)
            ->where('status_pemesanan', '!=', 'canceled')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate) {
                          $q->where('tanggal_mulai', '<=', $startDate)
                            ->whereRaw('DATE_ADD(tanggal_mulai, INTERVAL durasi MONTH) > ?', [$startDate]);
                      });
            })
            ->exists();

        if ($overlappingBookings) {
            return back()->with('error', 'This property is already booked for the selected dates.');
        }

        // Create booking
        $booking = Auth::user()->bookings()->create([
            'kos_id' => $validated['kos_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'durasi' => $durasi,
            'total_harga' => $totalHarga,
            'status_pemesanan' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully! Please proceed with payment.');
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['kos.owner', 'payment']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow cancellation of pending bookings
        if ($booking->status_pemesanan !== 'pending') {
            return back()->with('error', 'Only pending bookings can be canceled.');
        }

        $booking->update(['status_pemesanan' => 'canceled']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking canceled successfully.');
    }
}
