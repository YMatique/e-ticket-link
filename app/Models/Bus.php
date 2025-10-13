<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_number',
        'model',
        'total_seats',
        'seat_configuration',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'seat_configuration' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get all schedules for this bus.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get bus display name.
     */
    public function displayName(): string
    {
        return "{$this->model} ({$this->registration_number})";
    }

    /**
     * Check if bus is available for a specific date/time.
     */
    public function isAvailableForSchedule(string $date, string $time): bool
    {
        return !$this->schedules()
            ->where('departure_date', $date)
            ->where('departure_time', $time)
            ->whereIn('status', ['active', 'departed'])
            ->exists();
    }
}
