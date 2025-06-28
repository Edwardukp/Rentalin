<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class KosController extends Controller
{
    /**
     * Display a listing of available kos for tenants
     */
    public function index(Request $request)
    {
        $query = Kos::with(['owner', 'reviews'])
            ->where('status', true);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kos', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('fasilitas', 'like', "%{$search}%");
            });
        }

        // Location filter
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where('alamat', 'like', "%{$location}%");
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        // Facilities filter
        if ($request->filled('facilities')) {
            $facilities = $request->facilities;
            $query->where(function($q) use ($facilities) {
                foreach ($facilities as $facility) {
                    $q->where('fasilitas', 'like', "%{$facility}%");
                }
            });
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $minRating = $request->min_rating;
            $query->whereHas('reviews', function($q) use ($minRating) {
                $q->selectRaw('kos_id, AVG(rating) as avg_rating')
                  ->groupBy('kos_id')
                  ->havingRaw('AVG(rating) >= ?', [$minRating]);
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'name':
                $query->orderBy('nama_kos', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $kos = $query->paginate(12);

        // Get filter options for the view
        $priceRanges = [
            ['min' => 0, 'max' => 1000000, 'label' => 'Under Rp 1,000,000'],
            ['min' => 1000000, 'max' => 2000000, 'label' => 'Rp 1,000,000 - Rp 2,000,000'],
            ['min' => 2000000, 'max' => 3000000, 'label' => 'Rp 2,000,000 - Rp 3,000,000'],
            ['min' => 3000000, 'max' => 5000000, 'label' => 'Rp 3,000,000 - Rp 5,000,000'],
            ['min' => 5000000, 'max' => null, 'label' => 'Above Rp 5,000,000'],
        ];

        $commonFacilities = [
            'WiFi', 'AC', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja Belajar',
            'Parkir Motor', 'Parkir Mobil', 'Dapur Bersama', 'Laundry', 'Security 24 Jam'
        ];

        return view('kos.index', compact('kos', 'priceRanges', 'commonFacilities'));
    }

    /**
     * Display the specified kos details
     */
    public function show(Kos $ko)
    {
        $ko->load(['owner', 'reviews.user', 'bookings']);
        $averageRating = $ko->averageRating();
        $totalReviews = $ko->reviews->count();

        return view('kos.show', compact('ko', 'averageRating', 'totalReviews'));
    }
}
