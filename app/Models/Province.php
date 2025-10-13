<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Province extends Model
{
     protected $fillable = [
        'name',
        'code',
    ];

    
    /**
     * Get all cities in this province.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get all routes originating from this province.
     */
    public function originRoutes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Route::class,
            City::class,
            'province_id', // Foreign key on cities table
            'origin_city_id', // Foreign key on routes table
            'id', // Local key on provinces table
            'id' // Local key on cities table
        );
    }

    /**
     * Get all routes ending in this province.
     */
    public function destinationRoutes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Route::class,
            City::class,
            'province_id',
            'destination_city_id',
            'id',
            'id'
        );
    }
}

