<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Kos;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:10|max:1000',
        ]);

        $kos = Kos::findOrFail($validated['kos_id']);

        // Check if user has a confirmed booking for this kos
        $hasBooking = Booking::where('user_id', Auth::id())
            ->where('kos_id', $kos->id)
            ->where('status_pemesanan', 'confirmed')
            ->exists();

        if (!$hasBooking) {
            return back()->with('error', 'You can only review properties you have booked.');
        }

        // Check if user has already reviewed this kos
        $existingReview = Review::where('user_id', Auth::id())
            ->where('kos_id', $kos->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this property.');
        }

        // Create review
        Review::create([
            'user_id' => Auth::id(),
            'kos_id' => $validated['kos_id'],
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'],
            'tanggal' => Carbon::now()->toDateString(),
        ]);

        return back()->with('success', 'Thank you for your review!');
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'],
            'tanggal' => Carbon::now()->toDateString(),
        ]);

        return back()->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully!');
    }
}
