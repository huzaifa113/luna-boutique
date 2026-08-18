<?php
    $ucs = app(\App\Services\UnitConversionService::class);
    $fmt = app(\App\Services\InvoiceFormatterService::class);
    $config = config('pos');
    $company = $config['company'];
    $customerName = $sale->customer?->name ?? $sale->walk_in_name ?? 'Walk-in Customer';
    $customerPhone = $sale->customer?->phone ?? $sale->walk_in_phone ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($sale->invoice_number); ?></title>
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
<body onload="<?php echo e(request('autoprint') ? 'window.print()' : ''); ?>">
    <div class="no-print">
        <button onclick="window.print()" style="padding:8px 16px;font-size:13px;cursor:pointer;">🖨️ Print Receipt</button>
    </div>

    <div class="receipt">
        
        <div class="company-name"><?php echo e($company['name']); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company['address']): ?>
            <div class="company-details"><?php echo e($company['address']); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company['phone']): ?>
            <div class="company-details">Tel: <?php echo e($company['phone']); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company['email']): ?>
            <div class="company-details"><?php echo e($company['email']); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company['tax_number']): ?>
            <div class="company-details">Tax#: <?php echo e($company['tax_number']); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="divider"></div>
        <div class="center bold" style="font-size:12px;">SALES RECEIPT</div>
        <div class="divider"></div>

        
        <table>
            <tr>
                <td>Inv #:</td>
                <td class="right bold"><?php echo e($sale->invoice_number); ?></td>
            </tr>
            <tr>
                <td>Date:</td>
                <td class="right"><?php echo e($sale->sale_date->format('d M Y H:i')); ?></td>
            </tr>
            <tr>
                <td>Cashier:</td>
                <td class="right"><?php echo e($sale->user?->name ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Customer:</td>
                <td class="right"><?php echo e($customerName); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customerPhone): ?>
            <tr>
                <td>Phone:</td>
                <td class="right"><?php echo e($customerPhone); ?></td>
            </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </table>

        <div class="divider"></div>

        
        <table class="items">
            <thead>
                <tr>
                    <th style="text-align:left;">Item</th>
                    <th class="item-qty">Qty</th>
                    <th class="item-amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <tr>
                    <td class="item-name">
                        <?php echo e($item->product_name); ?><br>
                        <span style="font-size:9px;"><?php echo e($item->product_sku ?? ''); ?> @ <?php echo e($ucs->formatQuantity($item->quantity)); ?> <?php echo e($item->unit_name); ?> × <?php echo e($fmt::money($item->rate)); ?></span>
                    </td>
                    <td class="item-qty"><?php echo e($ucs->formatQuantity($item->quantity)); ?></td>
                    <td class="item-amount"><?php echo e($fmt::money($item->net_amount)); ?></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>

        <div class="divider"></div>

        
        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td class="right"><?php echo e($fmt::money($sale->subtotal)); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->discount > 0): ?>
            <tr>
                <td>Discount</td>
                <td class="right">-<?php echo e($fmt::money($sale->discount)); ?></td>
            </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->tax > 0): ?>
            <tr>
                <td>Tax</td>
                <td class="right">+<?php echo e($fmt::money($sale->tax)); ?></td>
            </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->delivery_charges > 0): ?>
            <tr>
                <td>Delivery</td>
                <td class="right">+<?php echo e($fmt::money($sale->delivery_charges)); ?></td>
            </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </table>

        <div class="double-divider"></div>
        <table>
            <tr>
                <td class="grand-total">TOTAL</td>
                <td class="right grand-total"><?php echo e($fmt::money($sale->total)); ?></td>
            </tr>
        </table>
        <div class="divider"></div>

        
        <div style="font-size:9px;">
            <span class="bold">In words:</span> <?php echo e($fmt::amountInWords($sale->total)); ?>

        </div>

        
        <div class="divider"></div>
        <table>
            <tr>
                <td>Payment Method</td>
                <td class="right"><?php echo e(ucfirst(str_replace('_', ' ', $sale->notes ?? 'cash'))); ?></td>
            </tr>
        </table>

        
        <div class="footer">
            <p><?php echo e($config['invoice']['terms']); ?></p>
            <p>Thank you for shopping with us!</p>
        </div>

        
        <div class="signatures">
            <table>
                <tr>
                    <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Signature</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Projects\ecomm\resources\views/pos/receipts/thermal.blade.php ENDPATH**/ ?>