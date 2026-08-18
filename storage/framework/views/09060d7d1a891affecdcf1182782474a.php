<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['product']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $inWishlist = auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists();
?>

<div class="product-card group relative">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->images->isNotEmpty()): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->compare_price && $product->price < $product->compare_price): ?>
            <div class="absolute left-5 top-5 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-md">Sale</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <a href="<?php echo e(route('products.show', $product)); ?>" class="block overflow-hidden">
            <div class="aspect-[4/5] w-full">
                <img src="<?php echo e($product->images->first()->url); ?>" alt="<?php echo e($product->images->first()->alt_text ?? $product->name); ?>" class="h-full w-full object-cover">
            </div>
        </a>
    <?php else: ?>
        <div class="flex h-72 items-center justify-center bg-slate-100 text-slate-500">No image available</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="space-y-4 p-6">
        <a href="<?php echo e(route('products.show', $product)); ?>" class="block text-lg font-semibold text-slate-900 transition hover:text-indigo-600"><?php echo e($product->name); ?></a>
        <p class="text-sm text-slate-500"><?php echo e($product->brand?->name ?? 'Brand'); ?></p>
        <div class="flex items-center justify-between gap-4">
            <div class="text-xl font-semibold text-slate-900">$<?php echo e(number_format($product->price, 2)); ?></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->compare_price && $product->price < $product->compare_price): ?>
                <div class="text-sm text-slate-400 line-through">$<?php echo e(number_format($product->compare_price, 2)); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <div class="flex flex-col gap-2">
                <form action="<?php echo e(route('cart.store')); ?>" method="POST" data-ajax-cart>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="button-primary w-full text-center text-sm">Add to Cart</button>
                </form>
                <form action="<?php echo e(route($inWishlist ? 'wishlist.destroy' : 'wishlist.store', $product)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inWishlist): ?>
                        <?php echo method_field('DELETE'); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button type="submit" class="button-secondary w-full text-center text-sm">
                        <?php echo e($inWishlist ? '❤️ Saved' : '♡ Wishlist'); ?>

                    </button>
                </form>
            </div>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="button-primary w-full text-center text-sm">Login to Purchase</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div><?php /**PATH C:\Projects\ecomm\resources\views/components/product-card.blade.php ENDPATH**/ ?>