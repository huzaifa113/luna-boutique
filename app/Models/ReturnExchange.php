<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'reason',
        'details',
        'refund_amount',
        'coupon_id',
        'admin_notes',
        'requested_at',
        'admin_processed_at',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ITEMS_RECEIVED = 'items_received';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const REASONS = [
        'defective' => 'Defective / Damaged Product',
        'wrong_item' => 'Wrong Item Received',
        'not_as_described' => 'Not as Described',
        'size_issue' => 'Size / Fit Issue',
        'changed_mind' => 'Changed Mind',
        'other' => 'Other',
    ];

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ITEMS_RECEIVED,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'admin_processed_at' => 'datetime',
            'refund_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        // Restock returned items whenever a return transitions into the approved status,
        // regardless of whether that happens via the header action or the edit form.
        static::updated(function (ReturnExchange $returnExchange) {
            if ($returnExchange->wasChanged('status')
                && $returnExchange->status === self::STATUS_APPROVED
                && $returnExchange->getOriginal('status') !== self::STATUS_APPROVED) {
                $returnExchange->restockItems();
            }
        });
    }

    public function restockItems(): void
    {
        $this->loadMissing('items.orderItem.product');

        foreach ($this->items as $item) {
            $product = $item->orderItem?->product;

            if ($product) {
                $product->increment('stock_quantity', $item->quantity);
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnExchangeItem::class, 'return_exchange_id');
    }

    public function attachments()
    {
        return $this->hasMany(ReturnExchangeAttachment::class, 'return_exchange_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}