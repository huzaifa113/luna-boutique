<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Invoice'); ?></title>
    <style>
        @page { size: A4 portrait; margin: 12mm; }
        html, body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 0; }
        .sheet { width: 186mm; min-height: 273mm; margin: 0 auto; position: relative; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr, td, th { page-break-inside: avoid; }
        .items th, .items td { border: 1px solid #999; padding: 4px 6px; }
        .items th { background: #f2f2f2; text-align: left; }
        .num { text-align: right; font-variant-numeric: tabular-nums; }
        .totals { width: 78mm; margin-left: auto; }
        .signatures { margin-top: 14mm; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 48pt; color: rgba(200, 0, 0, 0.08); font-weight: bold; pointer-events: none; white-space: nowrap; }
        .header-title { font-size: 18px; font-weight: bold; margin: 0; }
        .meta { width: 100%; margin-bottom: 6mm; }
        .meta td { vertical-align: top; padding: 2px 8px 2px 0; }
        .party { margin-bottom: 4mm; }
        .party-box { border: 1px solid #ccc; padding: 6px; margin-bottom: 4mm; }
        @media print { .no-print { display: none !important; } }
        .payment-summary { border: 1px solid #ccc; padding: 6px; margin-top: 4mm; }
        .payment-summary td { padding: 2px 6px; }
        .shortage-note { font-style: italic; color: #666; margin-top: 4mm; }
        .footer-text { font-size: 9px; color: #888; margin-top: 10mm; }
        .double-rule { border-top: 3px double #000; }
    </style>
</head>
<body onload="<?php echo e(request('autoprint') ? 'window.print()' : ''); ?>">
    <div class="no-print" style="text-align:center;padding:8px;background:#f5f5f5;margin-bottom:8px;">
        <button onclick="window.print()" style="padding:8px 20px;font-size:14px;cursor:pointer;">🖨️ Print</button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!request('autoprint')): ?>
            <button onclick="window.open('?autoprint=1','_blank')" style="padding:8px 20px;font-size:14px;cursor:pointer;">🖨️ Print (auto)</button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="sheet">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($isDraft) && $isDraft): ?>
            <div class="watermark">DRAFT</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($isCancelled) && $isCancelled): ?>
            <div class="watermark">CANCELLED</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</body>
</html><?php /**PATH C:\Projects\ecomm\resources\views/pos/invoices/layout.blade.php ENDPATH**/ ?>