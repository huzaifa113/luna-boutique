<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'order_number', 'shipping_address_id', 'billing_address_id', 'coupon_id', 'status', 'payment_status', 'payment_method', 'transaction_id', 'payment_channel', 'payment_screenshot', 'subtotal', 'discount', 'tax', 'shipping', 'total', 'notes'])]
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_DISPATCHED = 'dispatched';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_RETURNED = 'returned';

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_DISPATCHED,
        self::STATUS_DELIVERED,
        self::STATUS_RETURNED,
    ];

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';

    const PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_PENDING,
        self::PAYMENT_STATUS_PAID,
    ];

    const PAYMENT_METHOD_ONLINE = 'online';
    const PAYMENT_METHOD_CASH = 'cash_on_delivery';

    const PAYMENT_METHODS = [
        self::PAYMENT_METHOD_ONLINE,
        self::PAYMENT_METHOD_CASH,
    ];

    const PAYMENT_CHANNEL_EASYPAISA = 'easypaisa';
    const PAYMENT_CHANNEL_JAZZCASH = 'jazzcash';
    const PAYMENT_CHANNEL_BANK = 'bank_account';

    const PAYMENT_CHANNELS = [
        self::PAYMENT_CHANNEL_EASYPAISA,
        self::PAYMENT_CHANNEL_JAZZCASH,
        self::PAYMENT_CHANNEL_BANK,
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
            'payment_status' => 'string',
            'payment_method' => 'string',
        ];
    }

    public function getTotalAttribute($value): float
    {
        return $value ?: round(($this->subtotal + $this->tax + $this->shipping) - $this->discount, 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnExchanges()
    {
        return $this->hasMany(ReturnExchange::class);
    }
}
