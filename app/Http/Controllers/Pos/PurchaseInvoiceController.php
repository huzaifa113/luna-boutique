<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Purchase;

class PurchaseInvoiceController extends Controller
{
    public function show(Purchase $purchase)
    {
        if (! auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $purchase->loadMissing(['vendor', 'items', 'vendorPayments', 'user']);

        return view('pos.invoices.purchase', compact('purchase'));
    }
}
