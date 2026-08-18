@php
    $ucs = app(\App\Services\UnitConversionService::class);
    $fmt = app(\App\Services\InvoiceFormatterService::class);
    $config = config('pos');
    $company = $config['company'];
    $isDraft = $sale->status === \App\Models\Sale::STATUS_DRAFT;
    $isCancelled = $sale->status === \App\Models\Sale::STATUS_CANCELLED;
    $payments = $sale->customerPayments;
    $customerName = $sale->customer?->name ?? $sale->walk_in_name ?? 'Walk-in Customer';
    $customerPhone = $sale->customer?->phone ?? $sale->walk_in_phone ?? '';
    $prevBalance = $sale->customer ? ($sale->customer->totalReceivable() - $sale->total) : 0;
    $balance = $sale->customer?->balance() ?? 0;
@endphp

@extends('pos.invoices.layout')

@section('content')
    {{-- Header --}}
    <table style="margin-bottom:4mm;">
        <tr>
            <td style="width:50%;">
                <h1 class="header-title">{{ $company['name'] }}</h1>
                <p>{{ $company['address'] }}<br>
                Phone: {{ $company['phone'] }}<br>
                Email: {{ $company['email'] }}<br>
                Tax#: {{ $company['tax_number'] }}</p>
            </td>
            <td style="width:50%;text-align:right;">
                <h1 style="font-size:22px;margin:0;">SALES INVOICE</h1>
            </td>
        </tr>
    </table>

    {{-- Meta --}}
    <table class="meta">
        <tr><td><strong>Invoice #:</strong> {{ $sale->invoice_number }}</td><td><strong>Date:</strong> {{ $sale->sale_date->format('d M Y') }}</td></tr>
        <tr><td><strong>Status:</strong> {{ ucfirst($sale->status) }}</td><td><strong>Payment:</strong> {{ ucfirst($sale->payment_status) }}</td></tr>
        <tr><td><strong>Created by:</strong> {{ $sale->user?->name ?? 'N/A' }}</td><td><strong>Printed at:</strong> {{ now()->format('d M Y H:i:s') }}</td></tr>
    </table>

    {{-- Party --}}
    <div class="party-box">
        <strong>CUSTOMER</strong><br>
        {{ $customerName }}<br>
        @if($customerPhone) Phone: {{ $customerPhone }}<br> @endif
        @if($sale->customer?->address) {{ $sale->customer->address }}<br> @endif
        @if($sale->customer?->city) {{ $sale->customer->city }}<br> @endif
        @if($sale->customer?->tax_number) Tax#: {{ $sale->customer->tax_number }} @endif
    </div>

    {{-- Items table --}}
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>SKU</th>
                <th class="num">Unit</th>
                <th class="num">Qty</th>
                <th class="num">Pack</th>
                <th class="num">Gross Base</th>
                <th class="num">Rate</th>
                <th class="num">Rate/Base</th>
                <th class="num">Shortage</th>
                <th class="num">Billed</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $idx => $item)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->product_sku ?? '-' }}</td>
                <td class="num">{{ $item->unit_name }}</td>
                <td class="num">{{ $ucs->formatQuantity($item->quantity) }}</td>
                <td class="num">{{ $ucs->formatQuantity($item->factor) }} {{ strtolower($item->unit_name) }}/base</td>
                <td class="num">{{ $ucs->formatQuantity($item->gross_base_quantity) }}</td>
                <td class="num">{{ $fmt::money($item->rate) }}</td>
                <td class="num">{{ $fmt::money($item->base_unit_rate) }}</td>
                <td class="num">{{ $item->shortage_quantity > 0 ? $fmt::money($item->shortage_amount) . '(' . $ucs->formatQuantity($item->shortage_quantity) . ')' : '-' }}</td>
                <td class="num">{{ $ucs->formatQuantity($item->billed_base_quantity) }}</td>
                <td class="num">{{ $fmt::money($item->net_amount) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals">
        <tr><td>Subtotal (gross)</td><td class="num">{{ $fmt::money($sale->subtotal) }}</td></tr>
        <tr><td><strong>Less: Shortage Adjustment</strong></td><td class="num"><strong>({{ $fmt::money($sale->shortage_adjustment) }})</strong></td></tr>
        @if($sale->discount > 0)
        <tr><td>Less: Discount</td><td class="num">({{ $fmt::money($sale->discount) }})</td></tr>
        @endif
        @if($sale->tax > 0)
        <tr><td>Add: Tax</td><td class="num">{{ $fmt::money($sale->tax) }}</td></tr>
        @endif
        @if($sale->delivery_charges > 0)
        <tr><td>Add: Delivery Charges</td><td class="num">{{ $fmt::money($sale->delivery_charges) }}</td></tr>
        @endif
        <tr class="double-rule"><td><strong>GRAND TOTAL</strong></td><td class="num"><strong>{{ $fmt::money($sale->total) }}</strong></td></tr>
    </table>

    {{-- Amount in words --}}
    <p style="margin-top:4mm;"><strong>Amount in words:</strong> {{ $fmt::amountInWords($sale->total) }}</p>

    {{-- Payment summary --}}
    <div class="payment-summary">
        <table>
            <tr><td><strong>Previous Balance</strong></td><td class="num">{{ $fmt::money(max($prevBalance, 0)) }}</td></tr>
            <tr><td><strong>This Invoice</strong></td><td class="num">{{ $fmt::money($sale->total) }}</td></tr>
            <tr><td><strong>Paid Against This Invoice</strong></td><td class="num">{{ $fmt::money($sale->paidAmount()) }}</td></tr>
            <tr><td><strong>Invoice Balance</strong></td><td class="num">{{ $fmt::money($sale->balanceAmount()) }}</td></tr>
            <tr style="border-top:1px solid #999;"><td><strong>Closing Party Balance</strong></td><td class="num"><strong>{{ $fmt::money($balance) }}</strong></td></tr>
        </table>
        @if($payments->count() > 0)
        <table style="margin-top:4px;font-size:9px;">
            <tr><th>Date</th><th>Method</th><th>Ref#</th><th class="num">Amount</th></tr>
            @foreach($payments as $p)
            <tr><td>{{ $p->payment_date->format('d M Y') }}</td><td>{{ ucfirst($p->method) }}</td><td>{{ $p->reference_no ?? '-' }}</td><td class="num">{{ $fmt::money($p->amount) }}</td></tr>
            @endforeach
        </table>
        @endif
    </div>

    {{-- Shortage note --}}
    <div class="shortage-note">* Shortage borne by seller; customer billed on delivered quantity only.</div>

    {{-- Footer --}}
    <div class="footer-text">
        <p>{{ $config['invoice']['terms'] }}</p>
    </div>
    <div class="signatures">
        <table>
            <tr>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Authorised Signature</td>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Customer Signature</td>
            </tr>
        </table>
    </div>
@endsection