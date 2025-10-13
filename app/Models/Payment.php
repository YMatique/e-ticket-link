<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'transaction_reference',
        'amount',
        'payment_method',
        'status',
        'gateway_response',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the ticket for this payment.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get payment method display name.
     */
    public function paymentMethodName(): string
    {
        return match($this->payment_method) {
            'mpesa' => 'M-Pesa',
            'emola' => 'e-Mola',
            'cash' => 'Dinheiro',
            'pos' => 'POS (Cartão)',
            default => $this->payment_method,
        };
    }
}
