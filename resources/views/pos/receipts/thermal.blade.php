@php
    $ucs = app(\App\Services\UnitConversionService::class);
    $fmt = app(\App\Services\InvoiceFormatterService::class);
    $config = config('pos');
    $company = $config['company'];
    $customerName = $sale->customer?->name ?? $sale->walk_in_name ?? 'Walk-in Customer';
    $customerPhone = $sale->customer?->phone ?? $sale->walk_in_phone ?? '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sale->invoice_number }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        html, body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .receipt {
            width: 72mm; /* printable area of 80mm paper */
            margin: 0 auto;
            padding: 4mm 2mm;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .company-name { font-size: 14px; font-weight: bold; text-align: center; }
        .company-details { text-align: center; font-size: 10px; }
        .divider {
            border-top: 1px dashed #000;
            margin: 3px 0;
        }
        .double-divider {
            border-top: 3px double #000;
            margin: 4px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        .items td, .items th { padding: 2px 0; }
        .items th { font-size: 10px; border-bottom: 1px dashed #000; }
        .item-name { font-size: 10px; }
        .item-qty { text-align: center; }
        .item-amount { text-align: right; }
        .totals td { padding: 2px 0; }
        .grand-total { font-size: 13px; font-weight: bold; }
        .footer { text-align: center; font-size: 9px; margin-top: 5px; }
        .signatures { margin-top: 10px; font-size: 10px; }
        .no-print { display: block; text-align: center; padding: 8px; background: #f5f5f5; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="{{ request('autoprint') ? 'window.print()' : '' }}">
    <div class="no-print">
        <button onclick="window.print()" style="padding:8px 16px;font-size:13px;cursor:pointer;">🖨️ Print Receipt</button>
    </div>

    <div class="receipt">
        {{-- Header --}}
        <div class="company-name">{{ $company['name'] }}</div>
        @if($company['address'])
            <div class="company-details">{{ $company['address'] }}</div>
        @endif
        @if($company['phone'])
            <div class="company-details">Tel: {{ $company['phone'] }}</div>
        @endif
        @if($company['email'])
            <div class="company-details">{{ $company['email'] }}</div>
        @endif
        @if($company['tax_number'])
            <div class="company-details">Tax#: {{ $company['tax_number'] }}</div>
        @endif

        <div class="divider"></div>
        <div class="center bold" style="font-size:12px;">SALES RECEIPT</div>
        <div class="divider"></div>

        {{-- Meta --}}
        <table>
            <tr>
                <td>Inv #:</td>
                <td class="right bold">{{ $sale->invoice_number }}</td>
            </tr>
            <tr>
                <td>Date:</td>
                <td class="right">{{ $sale->sale_date->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <td>Cashier:</td>
                <td class="right">{{ $sale->user?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Customer:</td>
                <td class="right">{{ $customerName }}</td>
            </tr>
            @if($customerPhone)
            <tr>
                <td>Phone:</td>
                <td class="right">{{ $customerPhone }}</td>
            </tr>
            @endif
        </table>

        <div class="divider"></div>

        {{-- Items --}}
        <table class="items">
            <thead>
                <tr>
                    <th style="text-align:left;">Item</th>
                    <th class="item-qty">Qty</th>
                    <th class="item-amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td class="item-name">
                        {{ $item->product_name }}<br>
                        <span style="font-size:9px;">{{ $item->product_sku ?? '' }} @ {{ $ucs->formatQuantity($item->quantity) }} {{ $item->unit_name }} × {{ $fmt::money($item->rate) }}</span>
                    </td>
                    <td class="item-qty">{{ $ucs->formatQuantity($item->quantity) }}</td>
                    <td class="item-amount">{{ $fmt::money($item->net_amount) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        {{-- Totals --}}
        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td class="right">{{ $fmt::money($sale->subtotal) }}</td>
            </tr>
            @if($sale->discount > 0)
            <tr>
                <td>Discount</td>
                <td class="right">-{{ $fmt::money($sale->discount) }}</td>
            </tr>
            @endif
            @if($sale->tax > 0)
            <tr>
                <td>Tax</td>
                <td class="right">+{{ $fmt::money($sale->tax) }}</td>
            </tr>
            @endif
            @if($sale->delivery_charges > 0)
            <tr>
                <td>Delivery</td>
                <td class="right">+{{ $fmt::money($sale->delivery_charges) }}</td>
            </tr>
            @endif
        </table>

        <div class="double-divider"></div>
        <table>
            <tr>
                <td class="grand-total">TOTAL</td>
                <td class="right grand-total">{{ $fmt::money($sale->total) }}</td>
            </tr>
        </table>
        <div class="divider"></div>

        {{-- Amount in words --}}
        <div style="font-size:9px;">
            <span class="bold">In words:</span> {{ $fmt::amountInWords($sale->total) }}
        </div>

        {{-- Payment info --}}
        <div class="divider"></div>
        <table>
            <tr>
                <td>Payment Method</td>
                <td class="right">{{ ucfirst(str_replace('_', ' ', $sale->notes ?? 'cash')) }}</td>
            </tr>
        </table>

        {{-- Footer --}}
        <div class="footer">
            <p>{{ $config['invoice']['terms'] }}</p>
            <p>Thank you for shopping with us!</p>
        </div>

        {{-- Signatures --}}
        <div class="signatures">
            <table>
                <tr>
                    <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Signature</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>