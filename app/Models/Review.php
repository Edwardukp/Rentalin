<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kos_id',
        'rating',
        'ulasan',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'rating' => 'integer',
        ];
    }

    /**
     * Get the user who wrote this review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kos for this review
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    /**
     * Scope to filter by rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope to filter by minimum rating
     */
    public function scopeMinRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
