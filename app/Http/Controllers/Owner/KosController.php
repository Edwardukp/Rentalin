<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KosController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of owner's kos properties
     */
    public function index()
    {
        // Admin can see all kos properties, regular owners see only their own
        if ($this->isAdmin(Auth::user())) {
            $kos = Kos::with(['owner'])
                ->withCount(['bookings', 'reviews'])
                ->latest()
                ->paginate(10);
        } else {
            $kos = Auth::user()->ownedKos()
                ->withCount(['bookings', 'reviews'])
                ->latest()
                ->paginate(10);
        }

        return view('owner.kos.index', compact('kos'));
    }

    /**
     * Show the form for creating a new kos
     */
    public function create()
    {
        return view('owner.kos.create');
    }

    /**
     * Store a newly created kos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0|max:999999999999.99',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'google_maps_url' => 'nullable|url|max:2048',
            'status' => 'boolean',
        ]);

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $photo) {
                $path = $photo->store('kos-photos', 'public');
                $photos[] = $path;
            }
        }

        $kos = Auth::user()->ownedKos()->create([
            'nama_kos' => $validated['nama_kos'],
            'alamat' => $validated['alamat'],
            'harga' => $validated['harga'],
            'fasilitas' => $validated['fasilitas'],
            'foto' => $photos,
            'google_maps_url' => $validated['google_maps_url'] ?? null,
            'status' => $validated['status'] ?? true,
        ]);

        return redirect()->route('owner.kos.index')
            ->with('success', 'Kos property created successfully!');
    }

    /**
     * Display the specified kos
     */
    public function show(Kos $kos)
    {
        $this->authorize('view', $kos);

        $kos->load(['bookings.user', 'reviews.user']);
        $averageRating = $kos->averageRating();
        $totalReviews = $kos->reviews->count();

        return view('owner.kos.show', compact('kos', 'averageRating', 'totalReviews'));
    }

    /**
     * Show the form for editing the specified kos
     */
    public function edit(Kos $kos)
    {
        $this->authorize('update', $kos);

        return view('owner.kos.edit', compact('kos'));
    }

    /**
     * Update the specified kos
     */
    public function update(Request $request, Kos $kos)
    {
        $this->authorize('update', $kos);

        $validated = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0|max:999999999999.99',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'google_maps_url' => 'nullable|url|max:2048',
            'status' => 'boolean',
        ]);

        // Handle photo uploads
        $photos = $kos->foto ?? [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $photo) {
                $path = $photo->store('kos-photos', 'public');
                $photos[] = $path;
            }
        }

        $kos->update([
            'nama_kos' => $validated['nama_kos'],
            'alamat' => $validated['alamat'],
            'harga' => $validated['harga'],
            'fasilitas' => $validated['fasilitas'],
            'foto' => $photos,
            'google_maps_url' => $validated['google_maps_url'] ?? $kos->google_maps_url,
            'status' => $validated['status'] ?? $kos->status,
        ]);

        return redirect()->route('owner.kos.index')
            ->with('success', 'Kos property updated successfully!');
    }

    /**
     * Remove the specified kos
     */
    public function destroy(Kos $kos)
    {
        $this->authorize('delete', $kos);

        // Delete associated photos
        if ($kos->foto) {
            foreach ($kos->foto as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $kos->delete();

        return redirect()->route('owner.kos.index')
            ->with('success', 'Kos property deleted successfully!');
    }

    /**
     * Check if the user is an admin
     */
    private function isAdmin($user): bool
    {
        // Check if user email is admin@rentalin.com or has admin role
        return $user->email === 'admin@rentalin.com' ||
               (isset($user->is_admin) && $user->is_admin) ||
               $user->name === 'Admin User';
    }
}
