<?php

namespace App\Policies;

use App\Models\Kos;
use App\Models\User;

class KosPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view kos listings
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kos $kos): bool
    {
        // Admin can view any kos
        if ($this->isAdmin($user)) {
            return true;
        }

        // Owners can view their own kos, tenants can view available kos
        return $user->isOwner() ? $kos->pemilik_id === $user->id : $kos->status;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kos $kos): bool
    {
        // Admin can update any kos
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->isOwner() && $kos->pemilik_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kos $kos): bool
    {
        // Admin can delete any kos
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->isOwner() && $kos->pemilik_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kos $kos): bool
    {
        // Admin can restore any kos
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->isOwner() && $kos->pemilik_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kos $kos): bool
    {
        // Admin can force delete any kos
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->isOwner() && $kos->pemilik_id === $user->id;
    }

    /**
     * Check if the user is an admin
     */
    private function isAdmin(User $user): bool
    {
        // Check if user email is admin@rentalin.com or has admin role
        return $user->email === 'admin@rentalin.com' ||
               (isset($user->is_admin) && $user->is_admin) ||
               $user->name === 'Admin User';
    }
}
