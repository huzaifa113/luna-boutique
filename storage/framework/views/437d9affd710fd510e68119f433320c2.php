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
            <span class="section-title">Returns</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Request a return</h1>
            <p class="max-w-2xl text-base text-slate-600">Submit a return request for a delivered order and receive a refund coupon.</p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="rounded-[2rem] bg-red-50 border border-red-200 p-6 text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="rounded-[2rem] bg-amber-50 border border-amber-200 p-6">
            <h3 class="font-semibold text-amber-800 mb-2">Return Policy</h3>
            <ul class="text-sm text-amber-700 space-y-1 list-disc list-inside">
                <li>You must dispatch the item(s) back to us on the same day of the request.</li>
                <li>Items must be unused and in original packaging.</li>
                <li>Once approved, you will receive a refund coupon to use on your next order.</li>
                <li>Processing time: 5-7 business days after we receive the items.</li>
            </ul>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders->isEmpty()): ?>
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <p class="text-slate-600">You don't have any eligible orders for return. Only delivered and paid orders can be returned.</p>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('return-exchanges.store')); ?>" enctype="multipart/form-data"
                class="space-y-6 rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <?php echo csrf_field(); ?>

                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Order</label>
                    <select name="order_id" id="order_id" required
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-slate-400 focus:ring-0">
                        <option value="">Choose an order...</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($order->id); ?>" data-items="<?php echo e($order->items->toJson()); ?>">
                                <?php echo e($order->order_number); ?> — $<?php echo e(number_format($order->total, 2)); ?> (<?php echo e($order->created_at->format('M d, Y')); ?>)
                            </option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Items to Return</label>
                    <div id="items-container" class="space-y-2">
                        <p class="text-sm text-slate-500">Please select an order first.</p>
                    </div>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Reason</label>
                    <select name="reason" required
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-slate-400 focus:ring-0">
                        <option value="">Select a reason...</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\ReturnExchange::REASONS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Additional Details</label>
                    <textarea name="details" rows="4"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-slate-400 focus:ring-0"
                        placeholder="Please provide any additional information about your return..."></textarea>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Attachments (optional, max 5)</label>
                    <input type="file" name="attachments[]" multiple accept="image/*,.pdf"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm file:mr-4 file:rounded-full file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                    <p class="mt-1 text-xs text-slate-500">Accepted: JPG, PNG, PDF (max 10MB each)</p>
                </div>

                <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 rounded-xl p-4">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>By submitting, you agree to our return policy. You must dispatch the item(s) back to us today.</span>
                </div>

                <button type="submit"
                    class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Submit Return Request
                </button>
            </form>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('order_id').addEventListener('change', function() {
            const container = document.getElementById('items-container');
            const selected = this.options[this.selectedIndex];
            
            if (!selected || !selected.value) {
                container.innerHTML = '<p class="text-sm text-slate-500">Please select an order first.</p>';
                return;
            }

            try {
                const items = JSON.parse(selected.dataset.items || '[]');
                container.innerHTML = items.map(item => `
                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer hover:border-slate-400">
                        <input type="checkbox" name="items[]" value="${item.id}" class="rounded border-slate-300 text-slate-900 focus:ring-0">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-900">${item.product_name}</p>
                            <p class="text-xs text-slate-500">Qty: ${item.quantity} — $${parseFloat(item.total_price).toFixed(2)}</p>
                        </div>
                    </label>
                `).join('');
            } catch (e) {
                container.innerHTML = '<p class="text-sm text-slate-500">Unable to load items.</p>';
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Projects\ecomm\resources\views/return-exchanges/create.blade.php ENDPATH**/ ?>