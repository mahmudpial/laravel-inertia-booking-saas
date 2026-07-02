<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * CORE IDENTITY NODE:
 * This model represents a unique entity within the SmartBooking grid.
 * It manages authentication credentials, session security, and role-based authority levels.
 * 
 * ROLES:
 * - customer: Standard client node.
 * - owner: Administrative business node.
 * - super_admin: Sovereign platform oversight.
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * 
     * TACTICAL NOTE: 'role' is included to allow initialization during onboarding
     * and administrative role synchronization.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Secured role identifier
    ];

    /**
     * The attributes that should be hidden for serialization.
     * 
     * DATA CONFIDENTIALITY: Masking sensitive credentials from API/Inertia responses.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * 
     * Laravel 11+ Standard Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SECURITY HANDSHAKE HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Verify if node has Sovereign Oversight (Super Admin).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Verify if node has Operational Authority (Business Owner).
     */
    public function isBusinessOwner(): bool
    {
        /**
         * Logic: User is a business owner if they hold the 'owner' role 
         * or if a business entity is already indexed under their ID.
         */
        return $this->role === 'owner' || $this->business()->exists();
    }

    /**
     * Verify if node is a Standard Client (Customer).
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /*
    |--------------------------------------------------------------------------
    | TACTICAL INFRASTRUCTURE RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relationship: The Business Node owned by this user.
     */
    public function business(): HasOne
    {
        return $this->hasOne(Business::class);
    }

    /**
     * Relationship: All session packets (Bookings) initialized by this user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}