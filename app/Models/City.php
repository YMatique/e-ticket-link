<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'province_id',
    ];

    /**
     * Get the province that this city belongs to.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get all routes originating from this city.
     */
    public function originRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'origin_city_id');
    }

    /**
     * Get all routes ending in this city.
     */
    public function destinationRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'destination_city_id');
    }

    /**
     * Get full city name with province.
     */
    public function fullName(): string
    {
        return "{$this->name}, {$this->province->name}";
    }
}
