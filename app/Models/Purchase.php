<?php

namespace App\Models;

use Database\Factories\PurchaseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['vendor_id', 'invoice_number', 'vendor_invoice_no', 'purchase_date', 'status', 'subtotal', 'shortage_adjustment', 'discount', 'tax', 'freight', 'total', 'notes', 'user_id'])]
class Purchase extends Model
{
    /** @use HasFactory<PurchaseFactory> */
    use HasFactory;

    const STATUS_DRAFT = 'draft';

    const STATUS_CONFIRMED = 'confirmed';

    const STATUS_CANCELLED = 'cancelled';

    const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_CONFIRMED,
        self::STATUS_CANCELLED,
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'status' => 'string',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function vendorPayments(): HasMany
    {
        return $this->hasMany(VendorPayment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Total amount paid against this purchase.
     */
    public function paidAmount(): float
    {
        return round((float) $this->vendorPayments()->sum('amount'), 2);
    }

    /**
     * Remaining balance on this purchase (can be negative = overpaid).
     */
    public function balanceAmount(): float
    {
        return round((float) $this->total - $this->paidAmount(), 2);
    }
}
