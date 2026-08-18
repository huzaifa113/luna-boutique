<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['category']));

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

foreach (array_filter((['category']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="category-card">
    <div class="space-y-4">
        <div>
            <a href="<?php echo e(route('categories.show', $category)); ?>"
                class="text-xl font-semibold text-slate-900 transition hover:text-indigo-600"><?php echo e($category->name); ?></a>
            <p class="mt-3 text-sm text-slate-500"><?php echo e(Str::limit($category->description, 100)); ?></p>
        </div>
        <a href="<?php echo e(route('categories.show', $category)); ?>"
            class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Shop
            <?php echo e(strtolower($category->name)); ?> <span aria-hidden="true">→</span></a>
    </div>
</div>
<?php /**PATH C:\Projects\ecomm\resources\views/components/category-card.blade.php ENDPATH**/ ?>