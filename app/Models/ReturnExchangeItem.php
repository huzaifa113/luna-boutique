<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnExchangeItem extends Model
{
    protected $fillable = [
        'return_exchange_id',
        'order_item_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function returnExchange()
    {
        return $this->belongsTo(ReturnExchange::class, 'return_exchange_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}