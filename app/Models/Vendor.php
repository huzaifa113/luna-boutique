<?php

namespace App\Models;

use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'company', 'phone', 'email', 'address', 'city', 'tax_number', 'opening_balance', 'notes', 'is_active'])]
class Vendor extends Model
{
    /** @use HasFactory<VendorFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'opening_balance' => 'decimal:2',
        ];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function vendorPayments(): HasMany
    {
        return $this->hasMany(VendorPayment::class);
    }

    /**
     * Total payable = opening_balance + sum of confirmed purchase totals.
     */
    public function totalPayable(): float
    {
        return round((float) $this->opening_balance + (float) $this->purchases()->where('status', Purchase::STATUS_CONFIRMED)->sum('total'), 2);
    }

    /**
     * Total paid = sum of all payments.
     */
    public function totalPaid(): float
    {
        return round((float) $this->vendorPayments()->sum('amount'), 2);
    }

    /**
     * Balance = totalPayable - totalPaid. Positive = I owe them.
     */
    public function balance(): float
    {
        return round($this->totalPayable() - $this->totalPaid(), 2);
    }
}
