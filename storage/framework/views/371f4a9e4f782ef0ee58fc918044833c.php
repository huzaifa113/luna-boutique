<?php
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
?>



<?php $__env->startSection('content'); ?>
    
    <table style="margin-bottom:4mm;">
        <tr>
            <td style="width:50%;">
                <h1 class="header-title"><?php echo e($company['name']); ?></h1>
                <p><?php echo e($company['address']); ?><br>
                Phone: <?php echo e($company['phone']); ?><br>
                Email: <?php echo e($company['email']); ?><br>
                Tax#: <?php echo e($company['tax_number']); ?></p>
            </td>
            <td style="width:50%;text-align:right;">
                <h1 style="font-size:22px;margin:0;">SALES INVOICE</h1>
            </td>
        </tr>
    </table>

    
    <table class="meta">
        <tr><td><strong>Invoice #:</strong> <?php echo e($sale->invoice_number); ?></td><td><strong>Date:</strong> <?php echo e($sale->sale_date->format('d M Y')); ?></td></tr>
        <tr><td><strong>Status:</strong> <?php echo e(ucfirst($sale->status)); ?></td><td><strong>Payment:</strong> <?php echo e(ucfirst($sale->payment_status)); ?></td></tr>
        <tr><td><strong>Created by:</strong> <?php echo e($sale->user?->name ?? 'N/A'); ?></td><td><strong>Printed at:</strong> <?php echo e(now()->format('d M Y H:i:s')); ?></td></tr>
    </table>

    
    <div class="party-box">
        <strong>CUSTOMER</strong><br>
        <?php echo e($customerName); ?><br>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customerPhone): ?> Phone: <?php echo e($customerPhone); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->customer?->address): ?> <?php echo e($sale->customer->address); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->customer?->city): ?> <?php echo e($sale->customer->city); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->customer?->tax_number): ?> Tax#: <?php echo e($sale->customer->tax_number); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <tr>
                <td><?php echo e($idx + 1); ?></td>
                <td><?php echo e($item->product_name); ?></td>
                <td><?php echo e($item->product_sku ?? '-'); ?></td>
                <td class="num"><?php echo e($item->unit_name); ?></td>
                <td class="num"><?php echo e($ucs->formatQuantity($item->quantity)); ?></td>
                <td class="num"><?php echo e($ucs->formatQuantity($item->factor)); ?> <?php echo e(strtolower($item->unit_name)); ?>/base</td>
                <td class="num"><?php echo e($ucs->formatQuantity($item->gross_base_quantity)); ?></td>
                <td class="num"><?php echo e($fmt::money($item->rate)); ?></td>
                <td class="num"><?php echo e($fmt::money($item->base_unit_rate)); ?></td>
                <td class="num"><?php echo e($item->shortage_quantity > 0 ? $fmt::money($item->shortage_amount) . '(' . $ucs->formatQuantity($item->shortage_quantity) . ')' : '-'); ?></td>
                <td class="num"><?php echo e($ucs->formatQuantity($item->billed_base_quantity)); ?></td>
                <td class="num"><?php echo e($fmt::money($item->net_amount)); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    
    <table class="totals">
        <tr><td>Subtotal (gross)</td><td class="num"><?php echo e($fmt::money($sale->subtotal)); ?></td></tr>
        <tr><td><strong>Less: Shortage Adjustment</strong></td><td class="num"><strong>(<?php echo e($fmt::money($sale->shortage_adjustment)); ?>)</strong></td></tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->discount > 0): ?>
        <tr><td>Less: Discount</td><td class="num">(<?php echo e($fmt::money($sale->discount)); ?>)</td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->tax > 0): ?>
        <tr><td>Add: Tax</td><td class="num"><?php echo e($fmt::money($sale->tax)); ?></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sale->delivery_charges > 0): ?>
        <tr><td>Add: Delivery Charges</td><td class="num"><?php echo e($fmt::money($sale->delivery_charges)); ?></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <tr class="double-rule"><td><strong>GRAND TOTAL</strong></td><td class="num"><strong><?php echo e($fmt::money($sale->total)); ?></strong></td></tr>
    </table>

    
    <p style="margin-top:4mm;"><strong>Amount in words:</strong> <?php echo e($fmt::amountInWords($sale->total)); ?></p>

    
    <div class="payment-summary">
        <table>
            <tr><td><strong>Previous Balance</strong></td><td class="num"><?php echo e($fmt::money(max($prevBalance, 0))); ?></td></tr>
            <tr><td><strong>This Invoice</strong></td><td class="num"><?php echo e($fmt::money($sale->total)); ?></td></tr>
            <tr><td><strong>Paid Against This Invoice</strong></td><td class="num"><?php echo e($fmt::money($sale->paidAmount())); ?></td></tr>
            <tr><td><strong>Invoice Balance</strong></td><td class="num"><?php echo e($fmt::money($sale->balanceAmount())); ?></td></tr>
            <tr style="border-top:1px solid #999;"><td><strong>Closing Party Balance</strong></td><td class="num"><strong><?php echo e($fmt::money($balance)); ?></strong></td></tr>
        </table>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payments->count() > 0): ?>
        <table style="margin-top:4px;font-size:9px;">
            <tr><th>Date</th><th>Method</th><th>Ref#</th><th class="num">Amount</th></tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <tr><td><?php echo e($p->payment_date->format('d M Y')); ?></td><td><?php echo e(ucfirst($p->method)); ?></td><td><?php echo e($p->reference_no ?? '-'); ?></td><td class="num"><?php echo e($fmt::money($p->amount)); ?></td></tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </table>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="shortage-note">* Shortage borne by seller; customer billed on delivered quantity only.</div>

    
    <div class="footer-text">
        <p><?php echo e($config['invoice']['terms']); ?></p>
    </div>
    <div class="signatures">
        <table>
            <tr>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Authorised Signature</td>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Customer Signature</td>
            </tr>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('pos.invoices.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\ecomm\resources\views/pos/invoices/sale.blade.php ENDPATH**/ ?>