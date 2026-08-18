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

    <section
        class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-16 text-white shadow-[0_40px_120px_-35px_rgba(15,23,42,0.65)] sm:px-10 lg:px-14">
        <div class="site-container grid gap-12 lg:grid-cols-[1.1fr_0.9fr] items-center">
            <div class="space-y-6">
                <span
                    class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-semibold tracking-[0.2em] text-white/85">New
                    collection</span>
                <h1 class="max-w-3xl text-5xl font-black leading-tight tracking-[-0.04em] sm:text-6xl">Sleek essentials
                    for modern living.</h1>
                <p class="max-w-2xl text-lg leading-8 text-slate-200/90">Discover premium products designed for everyday
                    comfort, polished style, and effortless refinement across your wardrobe and home.</p>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="<?php echo e(route('shop.index')); ?>" class="button-primary bg-sky-500 hover:bg-sky-600">Shop
                        collection</a>
                    <a href="<?php echo e(route('about')); ?>"
                        class="button-secondary bg-white/10 text-white border-white/20 hover:bg-white/20">Our story</a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 sm:grid-rows-2">
                <div
                    class="rounded-[2rem] bg-white/10 p-6 backdrop-blur-xl shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <p class="text-sm uppercase tracking-[0.3em] text-sky-200">Featured edit</p>
                    <h2 class="mt-4 text-2xl font-semibold text-white">Refined wardrobe staples</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-200/90">Handpicked pieces for timeless outfits and
                        modern silhouettes.</p>
                </div>
                <div class="rounded-[2rem] overflow-hidden bg-slate-950 shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <img src="https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=1200&q=80"
                        alt="Boutique hero" class="h-full w-full object-cover">
                </div>
                <div class="rounded-[2rem] overflow-hidden bg-slate-950 shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80"
                        alt="Product styling" class="h-full w-full object-cover">
                </div>
                <div
                    class="rounded-[2rem] bg-white/10 p-6 backdrop-blur-xl shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <p class="text-sm uppercase tracking-[0.3em] text-sky-200">Rapid delivery</p>
                    <h2 class="mt-4 text-2xl font-semibold text-white">Fast, premium shipping</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-200/90">Enjoy quick delivery and elevated packaging for
                        every order.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-10 py-16">
        <div class="space-y-3">
            <span class="section-title">Featured products</span>
            <h2 class="text-4xl font-semibold tracking-tight text-slate-900">Discover this season's best sellers.</h2>
            <p class="max-w-2xl text-base text-slate-600">Shop the latest curated collection of wardrobe, accessories,
                and home picks.</p>
        </div>

        <div class="card-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div
                    class="col-span-full rounded-[2rem] bg-white p-10 text-center text-slate-500 shadow-[0_20px_50px_-25px_rgba(15,23,42,0.15)]">
                    No featured products yet.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <section class="space-y-10 py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="section-title">Collections</span>
                <h2 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900">Shop by category</h2>
                <p class="mt-3 max-w-2xl text-base text-slate-600">Browse collections designed for every mood and room.
                </p>
            </div>
            <a href="<?php echo e(route('shop.index')); ?>"
                class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Browse all categories
                →</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal77343b4405a3e8bf66a7a88cb0d29606 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal77343b4405a3e8bf66a7a88cb0d29606 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.category-card','data' => ['category' => $category]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('category-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal77343b4405a3e8bf66a7a88cb0d29606)): ?>
<?php $attributes = $__attributesOriginal77343b4405a3e8bf66a7a88cb0d29606; ?>
<?php unset($__attributesOriginal77343b4405a3e8bf66a7a88cb0d29606); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal77343b4405a3e8bf66a7a88cb0d29606)): ?>
<?php $component = $__componentOriginal77343b4405a3e8bf66a7a88cb0d29606; ?>
<?php unset($__componentOriginal77343b4405a3e8bf66a7a88cb0d29606); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div
                    class="col-span-full rounded-[2rem] bg-white p-10 text-center text-slate-500 shadow-[0_20px_50px_-25px_rgba(15,23,42,0.15)]">
                    Categories will be available soon.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <section
        class="rounded-[2rem] bg-slate-900 px-6 py-12 text-white shadow-[0_28px_80px_-40px_rgba(15,23,42,0.35)] sm:px-10">
        <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
            <div>
                <span class="section-title bg-white/10 text-white">Why shop with us</span>
                <h2 class="mt-4 text-4xl font-semibold tracking-tight text-white">A refined shopping experience from
                    browse to delivery.</h2>
                <p class="mt-4 max-w-xl text-slate-200/85">Enjoy premium packaging, seamless checkout, and dedicated
                    support for every order.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.75rem] bg-white/10 p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-semibold text-white">Fast shipping</h3>
                    <p class="mt-3 text-slate-300">Quick delivery with premium packaging for every purchase.</p>
                </div>
                <div class="rounded-[1.75rem] bg-white/10 p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-semibold text-white">Curated quality</h3>
                    <p class="mt-3 text-slate-300">Handpicked products chosen for lasting style and comfort.</p>
                </div>
            </div>
        </div>
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
<?php /**PATH C:\Projects\ecomm\resources\views/home.blade.php ENDPATH**/ ?>