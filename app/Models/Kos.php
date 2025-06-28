<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\GoogleMapsHelper;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';

    protected $fillable = [
        'pemilik_id',
        'nama_kos',
        'alamat',
        'harga',
        'fasilitas',
        'foto',
        'status',
        'google_maps_url',
    ];

    protected function casts(): array
    {
        return [
            'foto' => 'array',
            'status' => 'boolean',
            'harga' => 'decimal:2',
        ];
    }

    /**
     * Get the owner of this kos
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    /**
     * Get the bookings for this kos
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for this kos
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the average rating for this kos
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Scope to filter available kos
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to filter by price range
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('harga', [$min, $max]);
    }

    /**
     * Get Google Maps embed URL for this kos
     */
    public function getGoogleMapsEmbedUrl($width = 600, $height = 450)
    {
        return GoogleMapsHelper::generateEmbedUrl($this->google_maps_url, $width, $height);
    }

    /**
     * Get clean Google Maps URL for this kos
     */
    public function getGoogleMapsUrl()
    {
        return GoogleMapsHelper::generateCleanMapUrl($this->google_maps_url);
    }

    /**
     * Get static map thumbnail for this kos
     */
    public function getStaticMapThumbnail($width = 400, $height = 300, $zoom = 15)
    {
        return GoogleMapsHelper::getStaticMapThumbnail($this->google_maps_url, $width, $height, $zoom);
    }

    /**
     * Check if this kos has a valid Google Maps URL
     */
    public function hasValidGoogleMapsUrl()
    {
        return GoogleMapsHelper::isValidGoogleMapsUrl($this->google_maps_url);
    }

    /**
     * Get coordinates from Google Maps URL
     */
    public function getCoordinatesFromGoogleMaps()
    {
        return GoogleMapsHelper::extractCoordinatesFromUrl($this->google_maps_url);
    }

    /**
     * Get Leaflet map HTML (free alternative to Google Maps)
     */
    public function getLeafletMapHtml($width = 600, $height = 450)
    {
        return GoogleMapsHelper::generateLeafletMapHtml($this->google_maps_url, $width, $height);
    }
}
