<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Sale;

class ThermalReceiptController extends Controller
{
    public function show(Sale $sale)
    {
        if (! auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $sale->loadMissing(['customer', 'items', 'user']);

        return view('pos.receipts.thermal', compact('sale'));
    }
}