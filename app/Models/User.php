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
        'company',
        'phone',
        'tier',
        'subscription_status',
        'subscription_expires_at',
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
            'subscription_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->tier === 'admin';
    }

    /**
     * Check if user has paid subscription
     */
    public function isPaid(): bool
    {
        return $this->tier === 'paid' || $this->tier === 'admin';
    }

    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription(): bool
    {
        if ($this->tier === 'admin') {
            return true;
        }

        if ($this->tier === 'paid') {
            return $this->subscription_expires_at === null || 
                   $this->subscription_expires_at->isFuture();
        }

        return false;
    }

    /**
     * Get user's subscription tier display name
     */
    public function getTierDisplayName(): string
    {
        return match($this->tier) {
            'admin' => 'Administrator',
            'paid' => 'Professional',
            'user' => 'Starter',
            default => 'Unknown'
        };
    }
}
