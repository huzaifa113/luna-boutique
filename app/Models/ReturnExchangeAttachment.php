<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnExchangeAttachment extends Model
{
    protected $fillable = [
        'return_exchange_id',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function returnExchange()
    {
        return $this->belongsTo(ReturnExchange::class, 'return_exchange_id');
    }
}