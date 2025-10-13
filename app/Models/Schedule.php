<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'route_id',
        'bus_id',
        'departure_date',
        'departure_time',
        'price',
        'status',
        'created_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'departure_date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Get the route for this schedule.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the bus for this schedule.
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the user who created this schedule.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get all tickets for this schedule.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get temporary reservations for this schedule.
     */
    public function temporaryReservations(): HasMany
    {
        return $this->hasMany(TemporaryReservation::class);
    }

    /**
     * Get available seats count.
     */
    public function availableSeats(): int
    {
        $bookedSeats = $this->tickets()
            ->whereIn('status', ['reserved', 'paid', 'validated'])
            ->count();

        $temporaryReservations = $this->temporaryReservations()
            ->where('expires_at', '>', now())
            ->count();

        return $this->bus->total_seats - ($bookedSeats + $temporaryReservations);
    }

    /**
     * Check if schedule is bookable.
     */
    public function isBookable(): bool
    {
        return $this->status === 'active' && $this->availableSeats() > 0;
    }

    /**
     * Get departure datetime.
     */
    public function departureDatetime(): string
    {
        return $this->departure_date->format('Y-m-d') . ' ' . $this->departure_time;
    }
}
