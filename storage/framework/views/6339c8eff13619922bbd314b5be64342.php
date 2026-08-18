<?php
    $ucs = app(\App\Services\UnitConversionService::class);
    $fmt = app(\App\Services\InvoiceFormatterService::class);
    $config = config('pos');
    $company = $config['company'];
    $isDraft = $purchase->status === \App\Models\Purchase::STATUS_DRAFT;
    $isCancelled = $purchase->status === \App\Models\Purchase::STATUS_CANCELLED;
    $payments = $purchase->vendorPayments;
    $prevBalance = $purchase->vendor->totalPayable() - $purchase->total;
    $balance = $purchase->vendor->balance();
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
                <h1 style="font-size:22px;margin:0;">PURCHASE INVOICE</h1>
            </td>
        </tr>
    </table>

    
    <table class="meta">
        <tr><td><strong>Invoice #:</strong> <?php echo e($purchase->invoice_number); ?></td><td><strong>Date:</strong> <?php echo e($purchase->purchase_date->format('d M Y')); ?></td></tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->vendor_invoice_no): ?>
            <tr><td><strong>Vendor Bill #:</strong> <?php echo e($purchase->vendor_invoice_no); ?></td><td></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <tr><td><strong>Status:</strong> <?php echo e(ucfirst($purchase->status)); ?></td><td><strong>Created by:</strong> <?php echo e($purchase->user?->name ?? 'N/A'); ?></td></tr>
        <tr><td colspan="2"><strong>Printed at:</strong> <?php echo e(now()->format('d M Y H:i:s')); ?></td></tr>
    </table>

    
    <div class="party-box">
        <strong>VENDOR</strong><br>
        <?php echo e($purchase->vendor->name); ?><br>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->vendor->company): ?> <?php echo e($purchase->vendor->company); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->vendor->address): ?> <?php echo e($purchase->vendor->address); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->vendor->city): ?> <?php echo e($purchase->vendor->city); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        Phone: <?php echo e($purchase->vendor->phone ?? 'N/A'); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->vendor->tax_number): ?><br>Tax#: <?php echo e($purchase->vendor->tax_number); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                <th class="num">Received</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $purchase->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
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
                <td class="num"><?php echo e($ucs->formatQuantity($item->received_base_quantity)); ?></td>
                <td class="num"><?php echo e($fmt::money($item->net_amount)); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    
    <table class="totals">
        <tr><td>Subtotal (gross)</td><td class="num"><?php echo e($fmt::money($purchase->subtotal)); ?></td></tr>
        <tr><td><strong>Less: Shortage Adjustment</strong></td><td class="num"><strong>(<?php echo e($fmt::money($purchase->shortage_adjustment)); ?>)</strong></td></tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->discount > 0): ?>
        <tr><td>Less: Discount</td><td class="num">(<?php echo e($fmt::money($purchase->discount)); ?>)</td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->tax > 0): ?>
        <tr><td>Add: Tax</td><td class="num"><?php echo e($fmt::money($purchase->tax)); ?></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($purchase->freight > 0): ?>
        <tr><td>Add: Freight</td><td class="num"><?php echo e($fmt::money($purchase->freight)); ?></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <tr class="double-rule"><td><strong>GRAND TOTAL</strong></td><td class="num"><strong><?php echo e($fmt::money($purchase->total)); ?></strong></td></tr>
    </table>

    
    <p style="margin-top:4mm;"><strong>Amount in words:</strong> <?php echo e($fmt::amountInWords($purchase->total)); ?></p>

    
    <div class="payment-summary">
        <table>
            <tr><td><strong>Previous Balance</strong></td><td class="num"><?php echo e($fmt::money(max($prevBalance, 0))); ?></td></tr>
            <tr><td><strong>This Invoice</strong></td><td class="num"><?php echo e($fmt::money($purchase->total)); ?></td></tr>
            <tr><td><strong>Paid Against This Invoice</strong></td><td class="num"><?php echo e($fmt::money($purchase->paidAmount())); ?></td></tr>
            <tr><td><strong>Invoice Balance</strong></td><td class="num"><?php echo e($fmt::money($purchase->balanceAmount())); ?></td></tr>
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

    
    <div class="shortage-note">* Shortage deducted from vendor payable.</div>

    
    <div class="footer-text">
        <p><?php echo e($config['invoice']['terms']); ?></p>
    </div>
    <div class="signatures">
        <table>
            <tr>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Received By</td>
                <td style="width:50%;border-top:1px solid #000;padding-top:2mm;">Vendor Signature</td>
            </tr>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pos.invoices.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\ecomm\resources\views/pos/invoices/purchase.blade.php ENDPATH**/ ?>