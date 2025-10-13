<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'origin_city_id',
        'destination_city_id',
        'distance_km',
        'estimated_duration_minutes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'distance_km' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the origin city of this route.
     */
    public function originCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    /**
     * Get the destination city of this route.
     */
    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    /**
     * Get all schedules for this route.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get route name (Origin - Destination).
     */
    public function name(): string
    {
        return "{$this->originCity->name} - {$this->destinationCity->name}";
    }

    /**
     * Get route name with provinces.
     */
    public function fullName(): string
    {
        return "{$this->originCity->fullName()} → {$this->destinationCity->fullName()}";
    }

    /**
     * Get estimated duration in hours.
     */
    public function durationInHours(): float
    {
        return round($this->estimated_duration_minutes / 60, 1);
    }
}
