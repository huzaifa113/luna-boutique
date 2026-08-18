@php
    $ucs = app(\App\Services\UnitConversionService::class);
    $fmt = app(\App\Services\InvoiceFormatterService::class);
    $config = config('pos');
    $company = $config['company'];
    $isDraft = $purchase->status === \App\Models\Purchase::STATUS_DRAFT;
    $isCancelled = $purchase->status === \App\Models\Purchase::STATUS_CANCELLED;
    $payments = $purchase->vendorPayments;
    $prevBalance = $purchase->vendor->totalPayable() - $purchase->total;
    $balance = $purchase->vendor->balance();
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
                <h1 style="font-size:22px;margin:0;">PURCHASE INVOICE</h1>
            </td>
        </tr>
    </table>

    {{-- Meta --}}
    <table class="meta">
        <tr><td><strong>Invoice #:</strong> {{ $purchase->invoice_number }}</td><td><strong>Date:</strong> {{ $purchase->purchase_date->format('d M Y') }}</td></tr>
        @if($purchase->vendor_invoice_no)
            <tr><td><strong>Vendor Bill #:</strong> {{ $purchase->vendor_invoice_no }}</td><td></td></tr>
        @endif
        <tr><td><strong>Status:</strong> {{ ucfirst($purchase->status) }}</td><td><strong>Created by:</strong> {{ $purchase->user?->name ?? 'N/A' }}</td></tr>
        <tr><td colspan="2"><strong>Printed at:</strong> {{ now()->format('d M Y H:i:s') }}</td></tr>
    </table>

    {{-- Party --}}
    <div class="party-box">
        <strong>VENDOR</strong><br>
        {{ $purchase->vendor->name }}<br>
        @if($purchase->vendor->company) {{ $purchase->vendor->company }}<br> @endif
        @if($purchase->vendor->address) {{ $purchase->vendor->address }}<br> @endif
        @if($purchase->vendor->city) {{ $purchase->vendor->city }}<br> @endif
        Phone: {{ $purchase->vendor->phone ?? 'N/A' }}
        @if($purchase->vendor->tax_number)<br>Tax#: {{ $purchase->vendor->tax_number }} @endif
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
                <th class="num">Received</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->items as $idx => $item)
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
                <td class="num">{{ $ucs->formatQuantity($item->received_base_quantity) }}</td>
                <td class="num">{{ $fmt::money($item->net_amount) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals">
        <tr><td>Subtotal (gross)</td><td class="num">{{ $fmt::money($purchase->subtotal) }}</td></tr>
        <tr><td><strong>Less: Shortage Adjustment</strong></td><td class="num"><strong>({{ $fmt::money($purchase->shortage_adjustment) }})</strong></td></tr>
        @if($purchase->discount > 0)
        <tr><td>Less: Discount</td><td class="num">({{ $fmt::money($purchase->discount) }})</td></tr>
        @endif
        @if($purchase->tax > 0)
        <tr><td>Add: Tax</td><td class="num">{{ $fmt::money($purchase->tax) }}</td></tr>
        @endif
        @if($purchase->freight > 0)
        <tr><td>Add: Freight</td><td class="num">{{ $fmt::money($purchase->freight) }}</td></tr>
        @endif
        <tr class="double-rule"><td><strong>GRAND TOTAL</strong></td><td class="num"><strong>{{ $fmt::money($purchase->total) }}</strong></td></tr>
    </table>

    {{-- Amount in words --}}
    <p style="margin-top:4mm;"><strong>Amount in words:</strong> {{ $fmt::amountInWords($purchase->total) }}</p>

    {{-- Payment summary --}}
    <div class="payment-summary">
        <table>
            <tr><td><strong>Previous Balance</strong></td><td class="num">{{ $fmt::money(max($prevBalance, 0)) }}</td></tr>
            <tr><td><strong>This Invoice</strong></td><td class="num">{{ $fmt::money($purchase->total) }}</td></tr>
            <tr><td><strong>Paid Against This Invoice</strong></td><td class="num">{{ $fmt::money($purchase->paidAmount()) }}</td></tr>
            <tr><td><strong>Invoice Balance</strong></td><td class="num">{{ $fmt::money($purchase->balanceAmount()) }}</td></tr>
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
    <div class="shortage-note">* Shortage deducted from vendor payable.</div>

    {{-- Footer --}}
    <div class="footer-text">
        <p>{{ $config['invoice']['terms'] }}</p>
    </div>
    <div class="signatures">
        <table>
            <tr>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Received By</td>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Vendor Signature</td>
            </tr>
        </table>
    </div>
@endsection
