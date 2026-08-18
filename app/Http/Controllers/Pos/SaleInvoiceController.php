<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Sale;

class SaleInvoiceController extends Controller
{
    public function show(Sale $sale)
    {
        if (! auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $sale->loadMissing(['customer', 'items', 'customerPayments', 'user']);

        return view('pos.invoices.sale', compact('sale'));
    }
}
