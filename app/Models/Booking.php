<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kos_id',
        'tanggal_mulai',
        'durasi',
        'total_harga',
        'status_pemesanan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'total_harga' => 'decimal:2',
            'durasi' => 'integer',
        ];
    }

    /**
     * Mutator to ensure durasi is always an integer
     */
    public function setDurasiAttribute($value)
    {
        $this->attributes['durasi'] = (int) $value;
    }

    /**
     * Get the user who made this booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kos for this booking
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    /**
     * Get the payment for this booking
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Calculate end date based on start date and duration
     */
    public function getEndDateAttribute()
    {
        // Use copy() to avoid mutating the original Carbon instance
        // Ensure durasi is cast to integer to prevent type errors
        return $this->tanggal_mulai->copy()->addMonths((int) $this->durasi);
    }

    /**
     * Check if booking is pending
     */
    public function isPending()
    {
        return $this->status_pemesanan === 'pending';
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed()
    {
        return $this->status_pemesanan === 'confirmed';
    }

    /**
     * Check if booking is canceled
     */
    public function isCanceled()
    {
        return $this->status_pemesanan === 'canceled';
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pemesanan', $status);
    }
}
