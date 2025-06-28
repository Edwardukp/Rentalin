<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'metode_pembayaran',
        'jumlah',
        'status',
        'tanggal',
        'transaction_id',
        'qr_code_data',
        'payment_reference',
        'expires_at',
        'paid_at',
        'payment_instructions',
        'qris_metadata',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:2',
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
            'qris_metadata' => 'array',
        ];
    }

    /**
     * Get the booking for this payment
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if payment is expired
     */
    public function isExpired()
    {
        return $this->status === 'expired' || ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Generate unique payment reference
     */
    public static function generatePaymentReference()
    {
        return 'QRIS-' . strtoupper(uniqid()) . '-' . time();
    }

    /**
     * Check if payment is still valid (not expired)
     */
    public function isValid()
    {
        return !$this->isExpired() && $this->isPending();
    }

    /**
     * Get time remaining until expiration
     */
    public function getTimeRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }

        return $this->expires_at->diffInSeconds(now(), false);
    }

    /**
     * Scope to filter completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to filter pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter expired payments
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere('expires_at', '<', now());
    }

    /**
     * Scope to filter valid payments (pending and not expired)
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'pending')
                    ->where('expires_at', '>', now());
    }
}
