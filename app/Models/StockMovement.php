<?php

namespace App\Models;

use Database\Factories\StockMovementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['product_id', 'type', 'reason', 'base_quantity', 'balance_after', 'unit_cost', 'reference_type', 'reference_id', 'notes', 'user_id'])]
class StockMovement extends Model
{
    /** @use HasFactory<StockMovementFactory> */
    use HasFactory;

    const TYPE_IN = 'in';

    const TYPE_OUT = 'out';

    const REASON_PURCHASE = 'purchase';

    const REASON_PURCHASE_CANCEL = 'purchase_cancel';

    const REASON_SALE = 'sale';

    const REASON_SALE_SHORTAGE = 'sale_shortage';

    const REASON_SALE_CANCEL = 'sale_cancel';

    const REASON_ONLINE_ORDER = 'online_order';

    const REASON_ONLINE_RETURN = 'online_return';

    const REASON_ADJUSTMENT = 'adjustment';

    const REASON_OPENING = 'opening';

    const REASONS = [
        self::REASON_PURCHASE,
        self::REASON_PURCHASE_CANCEL,
        self::REASON_SALE,
        self::REASON_SALE_SHORTAGE,
        self::REASON_SALE_CANCEL,
        self::REASON_ONLINE_ORDER,
        self::REASON_ONLINE_RETURN,
        self::REASON_ADJUSTMENT,
        self::REASON_OPENING,
    ];

    protected function casts(): array
    {
        return [
            'base_quantity' => 'decimal:3',
            'balance_after' => 'decimal:3',
            'unit_cost' => 'decimal:4',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
