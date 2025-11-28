<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Account extends Authenticatable
{
      use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Helpers
    public function getTotalTickets()
    {
        return $this->tickets()->count();
    }

    public function getActiveTickets()
    {
        return $this->tickets()
            ->whereIn('status', ['reserved', 'paid'])
            ->count();
    }
}
