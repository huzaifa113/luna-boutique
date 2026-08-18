<?php

return [
    'company' => [
        'name' => env('POS_COMPANY_NAME', config('app.name')),
        'address' => env('POS_COMPANY_ADDRESS', ''),
        'phone' => env('POS_COMPANY_PHONE', ''),
        'email' => env('POS_COMPANY_EMAIL', ''),
        'tax_number' => env('POS_COMPANY_TAX_NUMBER', ''),
        'logo' => env('POS_COMPANY_LOGO', null),
    ],
    'currency' => [
        'symbol' => env('POS_CURRENCY_SYMBOL', 'Rs'),
        'code' => env('POS_CURRENCY_CODE', 'PKR'),
        'words' => env('POS_CURRENCY_WORDS', 'Rupees'),
        'subunit_words' => env('POS_CURRENCY_SUBUNIT_WORDS', 'Paisa'),
    ],
    'invoice' => [
        'purchase_prefix' => 'PUR-',
        'sale_prefix' => 'INV-',
        'number_padding' => 5,
        'terms' => env('POS_INVOICE_TERMS', 'Goods once sold are not returnable without prior approval.'),
    ],
    'quantity_decimals' => 3,
    'low_stock_threshold' => 10,
];
