<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'name', 'phone', 'email', 'address', 'city', 'opening_balance', 'credit_limit', 'notes', 'is_active'])]
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'opening_balance' => 'decimal:2',
            'credit_limit' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function customerPayments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class);
    }

    /**
     * Total receivable = opening_balance + sum of completed sale totals.
     */
    public function totalReceivable(): float
    {
        return round((float) $this->opening_balance + (float) $this->sales()->where('status', Sale::STATUS_COMPLETED)->sum('total'), 2);
    }

    /**
     * Total paid = sum of all payments.
     */
    public function totalPaid(): float
    {
        return round((float) $this->customerPayments()->sum('amount'), 2);
    }

    /**
     * Balance = totalReceivable - totalPaid. Positive = they owe me.
     */
    public function balance(): float
    {
        return round($this->totalReceivable() - $this->totalPaid(), 2);
    }
}
