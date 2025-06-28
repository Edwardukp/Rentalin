<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings for owner's properties
     */
    public function index(Request $request)
    {
        $query = Booking::whereHas('kos', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->with(['user', 'kos', 'payment']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pemesanan', $request->status);
        }

        // Filter by property
        if ($request->filled('kos_id')) {
            $query->where('kos_id', $request->kos_id);
        }

        $bookings = $query->latest()->paginate(15);

        // Get owner's properties for filter
        $properties = Auth::user()->ownedKos()->pluck('nama_kos', 'id');

        return view('owner.bookings.index', compact('bookings', 'properties'));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Check if booking belongs to owner's property
        if ($booking->kos->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['user', 'kos', 'payment']);

        return view('owner.bookings.show', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        // Check if booking belongs to owner's property
        if ($booking->kos->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status_pemesanan' => 'required|in:pending,confirmed,canceled'
        ]);

        $booking->update($validated);

        $statusMessage = [
            'confirmed' => 'Booking confirmed successfully!',
            'canceled' => 'Booking canceled successfully!',
            'pending' => 'Booking status updated to pending.'
        ];

        return back()->with('success', $statusMessage[$validated['status_pemesanan']]);
    }
}
