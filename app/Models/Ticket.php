<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_number',
        'passenger_id',
        'schedule_id',
        'seat_number',
        'price',
        'status',
        'qr_code',
        'validated_at',
        'validated_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'validated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * Generate unique ticket number.
     */
    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * Get the passenger for this ticket.
     */
    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    /**
     * Get the schedule for this ticket.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the user who validated this ticket.
     */
    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by_user_id');
    }

    /**
     * Get the payment for this ticket.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Check if ticket is valid.
     */
    public function isValid(): bool
    {
        return $this->status === 'paid' && !$this->validated_at;
    }

    /**
     * Check if ticket can be cancelled.
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['reserved', 'paid']) 
            && $this->schedule->departure_date->isFuture();
    }
}
