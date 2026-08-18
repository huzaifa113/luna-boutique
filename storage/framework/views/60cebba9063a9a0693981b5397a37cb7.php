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

    <section class="space-y-12 py-10">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="rounded-[1.75rem] border border-emerald-200/70 bg-emerald-50 px-6 py-4 text-emerald-900 shadow-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->images->isNotEmpty()): ?>
                    <img src="<?php echo e($product->images->first()->url); ?>" alt="<?php echo e($product->name); ?>" class="w-full rounded-[2rem] object-cover" />
                <?php else: ?>
                    <div class="flex h-96 items-center justify-center rounded-[2rem] bg-slate-100 text-slate-500">No product image available</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex rounded-full bg-indigo-100 px-4 py-1.5 text-sm font-semibold uppercase tracking-[0.25em] text-indigo-700"><?php echo e($product->category->name); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->brand): ?>
                                <span class="text-slate-500">by <?php echo e($product->brand->name); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <h1 class="text-4xl font-semibold tracking-tight text-slate-900"><?php echo e($product->name); ?></h1>
                        <p class="max-w-2xl text-lg leading-8 text-slate-600"><?php echo e($product->short_description); ?></p>
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="space-y-1">
                                <p class="text-3xl font-semibold text-slate-900">$<?php echo e(number_format($product->price, 2)); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->compare_price && $product->price < $product->compare_price): ?>
                                    <p class="text-sm text-slate-400 line-through">$<?php echo e(number_format($product->compare_price, 2)); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                                <?php echo e($product->stock_quantity > 0 ? $product->formatted_stock . ' in stock' : 'Out of stock'); ?>

                            </div>
                        </div>
                        <p class="text-slate-600"><?php echo e($product->description); ?></p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <form action="<?php echo e(route('cart.store')); ?>" method="POST" class="flex w-full items-center gap-3" data-ajax-cart>
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                    <input type="number" name="quantity" value="1" min="1" class="input-field w-20 text-center" />
                                    <button type="submit" class="button-primary w-full whitespace-nowrap">Add to Cart</button>
                                </form>
                                <form action="<?php echo e(route('wishlist.store', $product)); ?>" method="POST" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <?php
                                        $inWishlist = auth()->user()->wishlist()->where('product_id', $product->id)->exists();
                                    ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inWishlist): ?>
                                        <button type="submit" formaction="<?php echo e(route('wishlist.destroy', $product)); ?>" formmethod="POST" class="button-secondary w-full text-center">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            ❤️ Remove from Wishlist
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="button-secondary w-full text-center">♡ Add to Wishlist</button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </form>
                            </div>
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="<?php echo e(route('checkout.index')); ?>" class="button-primary w-full text-center">Proceed to Checkout</a>
                                <a href="<?php echo e(route('shop.index')); ?>" class="button-secondary w-full text-center">Continue Shopping</a>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="<?php echo e(route('login')); ?>" class="button-primary w-full text-center">Login to Purchase</a>
                                <a href="<?php echo e(route('shop.index')); ?>" class="button-secondary w-full text-center">Continue Shopping</a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-[1.75rem] bg-white p-6 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                        <h2 class="text-xl font-semibold text-slate-900">Product details</h2>
                        <p class="mt-4 text-slate-600"><?php echo e($product->description); ?></p>
                        <ul class="mt-6 space-y-3 text-slate-600">
                            <li><strong class="text-slate-900">Material:</strong> Premium fabric blend</li>
                            <li><strong class="text-slate-900">Care:</strong> Machine wash cold, lay flat to dry</li>
                            <li><strong class="text-slate-900">Shipping:</strong> Free shipping on orders over $100</li>
                        </ul>
                    </div>
                    <div class="rounded-[1.75rem] bg-white p-6 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                        <h2 class="text-xl font-semibold text-slate-900">Product information</h2>
                        <dl class="mt-6 space-y-3 text-slate-600">
                            <div class="flex justify-between">
                                <dt class="font-medium text-slate-900">SKU</dt>
                                <dd><?php echo e($product->sku); ?></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-slate-900">Category</dt>
                                <dd><?php echo e($product->category->name); ?></dd>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->brand): ?>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-slate-900">Brand</dt>
                                    <dd><?php echo e($product->brand->name); ?></dd>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($similarProducts->isNotEmpty()): ?>
            <section class="space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">You May Also Like</h2>
                    <p class="mt-1 text-slate-600">Discover more products in this collection.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $similarProducts->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $similar]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($similar)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $attributes = $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $component = $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <section class="space-y-6" x-data="{ visibleCount: 2 }">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-slate-900">Customer reviews</h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->reviews->count() > 0): ?>
                    <span class="rounded-full bg-slate-100 px-4 py-1.5 text-sm font-medium text-slate-600"><?php echo e($product->reviews->count()); ?> <?php echo e(Str::plural('review', $product->reviews->count())); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article x-show="visibleCount > <?php echo e($index); ?>"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="rounded-[2rem] bg-white p-6 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900"><?php echo e($review->user->name); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e($review->created_at->format('M d, Y')); ?></p>
                        </div>
                        <div class="text-amber-500"><?php echo e(str_repeat('★', $review->rating)); ?><?php echo e(str_repeat('☆', 5 - $review->rating)); ?></div>
                    </div>
                    <p class="mt-4 text-slate-600"><?php echo e($review->comment); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="rounded-[2rem] bg-white p-6 text-slate-600 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                    No reviews yet. Be the first to share your thoughts.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->reviews->count() > 2): ?>
                <div class="text-center" x-show="visibleCount < <?php echo e($product->reviews->count()); ?>">
                    <button @click="visibleCount = Math.min(visibleCount + 3, <?php echo e($product->reviews->count()); ?>)" type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        See more <span aria-hidden="true">↓</span>
                    </button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>
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
<?php endif; ?><?php /**PATH C:\Projects\ecomm\resources\views/products/show.blade.php ENDPATH**/ ?>