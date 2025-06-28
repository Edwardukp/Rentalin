<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'boolean',
        ];
    }

    /**
     * Check if user is an owner
     */
    public function isOwner(): bool
    {
        return $this->role === true;
    }

    /**
     * Check if user is a tenant
     */
    public function isTenant(): bool
    {
        return $this->role === false;
    }

    /**
     * Get the kos properties owned by this user
     */
    public function ownedKos()
    {
        return $this->hasMany(Kos::class, 'pemilik_id');
    }

    /**
     * Get the bookings made by this user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews written by this user
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
