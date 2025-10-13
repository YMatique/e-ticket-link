<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryReservation extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'schedule_id',
        'seat_number',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the schedule for this reservation.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Check if reservation is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope to get only active reservations.
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope to get expired reservations.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }
}
