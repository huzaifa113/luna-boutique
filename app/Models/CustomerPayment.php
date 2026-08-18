<?php

namespace App\Models;

use Database\Factories\CustomerPaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['customer_id', 'sale_id', 'payment_date', 'amount', 'method', 'reference_no', 'notes', 'user_id'])]
class CustomerPayment extends Model
{
    /** @use HasFactory<CustomerPaymentFactory> */
    use HasFactory;

    const METHOD_CASH = 'cash';

    const METHOD_BANK = 'bank';

    const METHOD_EASYPAISA = 'easypaisa';

    const METHOD_JAZZCASH = 'jazzcash';

    const METHOD_CHEQUE = 'cheque';

    const METHODS = [
        self::METHOD_CASH,
        self::METHOD_BANK,
        self::METHOD_EASYPAISA,
        self::METHOD_JAZZCASH,
        self::METHOD_CHEQUE,
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
