<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Cart</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Your selections are ready to checkout.</h1>
            <p class="max-w-2xl text-base text-slate-600">Review items, update quantities, and continue to a seamless
                checkout experience.</p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div
                class="rounded-[1.75rem] border border-emerald-200/70 bg-emerald-50 px-6 py-4 text-emerald-900 shadow-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->isEmpty()): ?>
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.08)]">
                <h2 class="text-2xl font-semibold text-slate-900">Your cart is empty</h2>
                <p class="mt-3 text-slate-600">Browse our collection and add pieces you love to complete your order.</p>
                <a href="<?php echo e(route('shop.index')); ?>" class="button-primary mt-6 inline-flex">Continue shopping</a>
            </div>
        <?php else: ?>
            <div class="grid gap-8 lg:grid-cols-[1.6fr_0.9fr]">
                <div class="space-y-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <article class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                                <div class="overflow-hidden rounded-[1.75rem] bg-slate-100 lg:w-52">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product->images->isNotEmpty()): ?>
                                        <img src="<?php echo e($item->product->images->first()?->url); ?>"
                                            alt="<?php echo e($item->product->name); ?>" class="h-44 w-full object-cover sm:h-52">
                                    <?php else: ?>
                                        <div
                                            class="flex h-44 items-center justify-center bg-slate-200 text-slate-500 sm:h-52">
                                            No image</div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="flex-1 space-y-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <a href="<?php echo e(route('products.show', $item->product)); ?>"
                                                class="text-xl font-semibold text-slate-900 transition hover:text-indigo-600"><?php echo e($item->product->name); ?></a>
                                            <p class="mt-2 text-sm text-slate-500">
                                                <?php echo e($item->product->brand?->name ?? 'Brand'); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-slate-900">
                                                $<?php echo e(number_format($item->product->price, 2)); ?></p>
                                            <p class="text-sm text-slate-500">each</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <form action="<?php echo e(route('cart.update', $item)); ?>" method="POST"
                                            class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <label for="quantity-<?php echo e($item->id); ?>" class="sr-only">Quantity</label>
                                            <input id="quantity-<?php echo e($item->id); ?>" type="number" name="quantity"
                                                value="<?php echo e($item->quantity); ?>" min="1"
                                                class="input-field w-full max-w-[120px]" />
                                            <button type="submit" class="button-secondary">Update</button>
                                        </form>

                                        <form action="<?php echo e(route('cart.destroy', $item)); ?>" method="POST"
                                            class="w-full sm:w-auto">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="button-secondary w-full">Remove</button>
                                        </form>
                                    </div>

                                    <p class="text-sm text-slate-500">Subtotal: <span
                                            class="font-semibold text-slate-900">$<?php echo e(number_format($item->product->price * $item->quantity, 2)); ?></span>
                                    </p>
                                </div>
                            </div>
                        </article>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                        <h2 class="text-xl font-semibold text-slate-900">Order summary</h2>
                        <div class="mt-6 space-y-4 text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span>$<?php echo e(number_format($subtotal, 2)); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping</span>
                                <span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shipping > 0): ?>
                                        $<?php echo e(number_format($shipping, 2)); ?>

                                        <span class="ml-2 text-xs text-slate-400">(Free on orders over $100)</span>
                                    <?php else: ?>
                                        <span class="text-emerald-600">Free</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-slate-200 pt-4 text-lg font-semibold text-slate-900">
                            <div class="flex items-center justify-between">
                                <span>Total</span>
                                <span>$<?php echo e(number_format($total, 2)); ?></span>
                            </div>
                        </div>
                        <a href="<?php echo e(route('checkout.index')); ?>"
                            class="button-primary mt-6 inline-flex w-full justify-center">Proceed to checkout</a>
                    </div>
                </aside>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Projects\ecomm\resources\views/cart/index.blade.php ENDPATH**/ ?>