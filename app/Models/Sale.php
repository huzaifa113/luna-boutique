<?php

namespace App\Models;

use Database\Factories\SaleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['customer_id', 'walk_in_name', 'walk_in_phone', 'invoice_number', 'sale_date', 'status', 'subtotal', 'shortage_adjustment', 'discount', 'tax', 'delivery_charges', 'total', 'shortage_cost', 'payment_status', 'notes', 'user_id'])]
class Sale extends Model
{
    /** @use HasFactory<SaleFactory> */
    use HasFactory;

    const STATUS_DRAFT = 'draft';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    const PAYMENT_STATUS_UNPAID = 'unpaid';

    const PAYMENT_STATUS_PARTIAL = 'partial';

    const PAYMENT_STATUS_PAID = 'paid';

    const PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_UNPAID,
        self::PAYMENT_STATUS_PARTIAL,
        self::PAYMENT_STATUS_PAID,
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'status' => 'string',
            'payment_status' => 'string',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customerPayments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Total amount paid against this sale.
     */
    public function paidAmount(): float
    {
        return round((float) $this->customerPayments()->sum('amount'), 2);
    }

    /**
     * Remaining balance on this sale.
     */
    public function balanceAmount(): float
    {
        return round((float) $this->total - $this->paidAmount(), 2);
    }

    /**
     * Refresh payment status based on paid amount vs total.
     */
    public function refreshPaymentStatus(): static
    {
        $paid = $this->paidAmount();
        $total = (float) $this->total;

        if ($total <= 0 || $paid <= 0) {
            $this->payment_status = self::PAYMENT_STATUS_UNPAID;
        } elseif (abs($paid - $total) < 0.01) {
            $this->payment_status = self::PAYMENT_STATUS_PAID;
        } elseif ($paid < $total) {
            $this->payment_status = self::PAYMENT_STATUS_PARTIAL;
        } else {
            $this->payment_status = self::PAYMENT_STATUS_PAID; // overpaid is still paid
        }

        $this->save();

        return $this;
    }
}
