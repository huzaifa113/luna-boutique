<div class="space-y-4">
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($successMessage): ?>
        <div class="flex items-center justify-between rounded-lg border border-success-200 bg-success-50 p-4 text-sm text-success-700 dark:border-success-700 dark:bg-success-500/10 dark:text-success-400">
            <span><?php echo e($successMessage); ?></span>
            <div class="flex shrink-0 items-center gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastSaleId): ?>
                    <a href="<?php echo e(route('pos.sales.receipt', $lastSaleId)); ?>" target="_blank" class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-primary-600 shadow-sm ring-1 ring-primary-200 hover:bg-primary-50 dark:bg-gray-800 dark:text-primary-400 dark:ring-primary-500/30">Print Receipt</a>
                    <a href="<?php echo e(route('pos.sales.invoice', $lastSaleId)); ?>" target="_blank" class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-primary-600 shadow-sm ring-1 ring-primary-200 hover:bg-primary-50 dark:bg-gray-800 dark:text-primary-400 dark:ring-primary-500/30">Print A4</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <button wire:click="$set('successMessage', null)" class="text-success-600 hover:text-success-800 dark:text-success-400">✕</button>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-5">
        
        <div class="lg:col-span-3">
            <div class="rounded-xl border bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-100 p-4 dark:border-gray-800">
                    <label for="pos-search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Search Products</label>
                    <div class="relative">
                        <input
                            id="pos-search"
                            type="text"
                            wire:model.live.debounce.150ms="search"
                            wire:keydown.enter="searchProducts"
                            placeholder="Search by name, SKU, or barcode..."
                            class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                        >
                    </div>
                </div>

                
                <div class="p-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(strlen(trim($search)) > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($searchResults)): ?>
                            <p class="py-1 text-center text-xs text-gray-400 dark:text-gray-500">No products found for "<?php echo e($search); ?>"</p>
                        <?php else: ?>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <button
                                        wire:click="addToCart(<?php echo e($result['id']); ?>, <?php echo e($result['product_unit_id'] ?: 'null'); ?>)"
                                        class="flex w-full items-center justify-between gap-4 rounded-lg border border-gray-100 bg-white p-3 text-left transition-colors hover:border-primary-200 hover:bg-primary-50/50 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-primary-500/30 dark:hover:bg-primary-500/5"
                                    >
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100"><?php echo e($result['name']); ?></p>
                                            <p class="mt-0.5 font-mono text-xs text-gray-500 dark:text-gray-400"><?php echo e($result['sku']); ?> · <?php echo e($result['unit_name']); ?></p>
                                        </div>
                                        <div class="flex shrink-0 items-center gap-4">
                                            <div class="text-right">
                                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"><?php echo e(config('pos.currency.symbol', 'Rs')); ?><?php echo e(number_format($result['price'], 2)); ?></p>
                                                <p class="text-xs <?php echo e($result['stock'] <= 0 ? 'text-danger-600' : ($result['track_stock'] && $result['stock'] <= 5 ? 'text-amber-600' : 'text-gray-500 dark:text-gray-400')); ?>">
                                                    <?php echo e($result['track_stock'] ? number_format($result['stock'], 3) . ' ' . $result['unit_name'] . ' available' : 'No stock tracking'); ?>

                                                </p>
                                            </div>
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </span>
                                        </div>
                                    </button>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <p class="py-1 text-center text-xs text-gray-400 dark:text-gray-500">Type to search for products by name, SKU, or barcode.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2">
            <div class="flex h-full flex-col rounded-xl border bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Current Sale</h3>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-semibold text-primary-700 dark:bg-primary-500/10 dark:text-primary-400"><?php echo e(count($cart)); ?> items</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($cart)): ?>
                            <button wire:click="clearCart" class="text-xs font-medium text-danger-600 hover:text-danger-700 dark:text-danger-400">Clear</button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="flex-1 overflow-y-auto p-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($cart)): ?>
                        <div class="flex h-full flex-col items-center justify-center py-12 text-center">
                            <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            <p class="mt-3 text-sm text-gray-400">Cart is empty. Search and add products.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-3 dark:border-gray-700 dark:bg-gray-800/50">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100"><?php echo e($item['name']); ?></p>
                                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400"><?php echo e($item['sku']); ?> · <?php echo e($item['unit_name']); ?></p>
                                        </div>
                                        <button wire:click="removeFromCart(<?php echo e($index); ?>)" class="shrink-0 rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-danger-600 dark:hover:bg-gray-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between gap-3">
                                        <div class="flex items-center rounded-lg border border-gray-200 dark:border-gray-600">
                                            <button wire:click="updateQuantity(<?php echo e($index); ?>, <?php echo e($item['quantity'] - 1); ?>)" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">−</button>
                                            <input
                                                type="number"
                                                step="0.001"
                                                min="0"
                                                wire:model.live="cart.<?php echo e($index); ?>.quantity"
                                                wire:change="updateQuantity(<?php echo e($index); ?>, $event.target.value)"
                                                class="w-16 border-x border-gray-200 bg-white py-1.5 text-center text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                                            >
                                            <button type="button" wire:click="updateQuantity(<?php echo e($index); ?>, <?php echo e($item['quantity'] + 1); ?>)" class="px-2.5 py-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200">+</button>
                                        </div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100"><?php echo e(config('pos.currency.symbol', 'Rs')); ?><?php echo e(number_format($item['line_total'], 2)); ?></p>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($cart)): ?>
                    <div class="border-t border-gray-100 p-4 dark:border-gray-800">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100"><?php echo e(config('pos.currency.symbol', 'Rs')); ?><?php echo e(number_format($this->subtotal, 2)); ?></span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Discount</span>
                                <input type="number" step="0.01" min="0" wire:model.live="discount" class="w-28 rounded-lg border border-gray-300 bg-white px-2 py-1 text-right text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Tax</span>
                                <input type="number" step="0.01" min="0" wire:model.live="tax" class="w-28 rounded-lg border border-gray-300 bg-white px-2 py-1 text-right text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Delivery</span>
                                <input type="number" step="0.01" min="0" wire:model.live="deliveryCharges" class="w-28 rounded-lg border border-gray-300 bg-white px-2 py-1 text-right text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-100 pt-2 dark:border-gray-800">
                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Total</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100"><?php echo e(config('pos.currency.symbol', 'Rs')); ?><?php echo e(number_format($this->total, 2)); ?></span>
                            </div>
                        </div>

                        <button wire:click="openPaymentModal" class="mt-4 w-full rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-primary-500">
                            Charge <?php echo e(config('pos.currency.symbol', 'Rs')); ?><?php echo e(number_format($this->total, 2)); ?>

                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPaymentModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'payment-modal'; ?>wire:key="payment-modal">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="closePaymentModal"></div>
            <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Complete Sale</h3>
                    <button wire:click="closePaymentModal" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">
                    
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Customer</label>
                        <select wire:model="customerId" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Walk-in Customer</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($customer['id']); ?>"><?php echo e($customer['name']); ?><?php echo e($customer['phone'] ? ' — ' . $customer['phone'] : ''); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$customerId): ?>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</label>
                                <input type="text" wire:model="walkInName" placeholder="Walk-in name" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Phone</label>
                                <input type="text" wire:model="walkInPhone" placeholder="Phone" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Payment Method</label>
                        <div class="grid grid-cols-3 gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['cash' => 'Cash', 'card' => 'Card', 'transfer' => 'Transfer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <button
                                    type="button"
                                    wire:click="$set('paymentMethod', '<?php echo e($value); ?>')"
                                    class="rounded-lg border px-3 py-2 text-sm font-medium transition-colors <?php echo e($paymentMethod === $value ? 'border-primary-600 bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400' : 'border-gray-300 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800'); ?>"
                                >
                                    <?php echo e($label); ?>

                                </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Total</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e(config('pos.currency.symbol', 'Rs')); ?><?php echo e(number_format($this->total, 2)); ?></span>
                        </div>
                        <div class="mt-3">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Amount Received</label>
                            <input type="number" step="0.01" min="0" wire:model.live="amountReceived" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-right text-lg font-bold text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Change</span>
                            <span class="font-semibold text-success-600 dark:text-success-400"><?php echo e(config('pos.currency.symbol', 'Rs')); ?><?php echo e(number_format($this->change, 2)); ?></span>
                        </div>
                    </div>

                    <button wire:click="completeSale" class="w-full rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-primary-500">
                        Complete Sale
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div
        x-data="{ show: false, message: '' }"
        x-on:pos-notify.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="pointer-events-none fixed bottom-6 right-6 z-[60]"
        style="display: none;"
    >
        <div class="rounded-lg bg-gray-900 px-4 py-3 text-sm font-medium text-white shadow-lg dark:bg-gray-100 dark:text-gray-900">
            <span x-text="message"></span>
        </div>
    </div>
</div><?php /**PATH C:\Projects\ecomm\resources\views/livewire/pos-terminal.blade.php ENDPATH**/ ?>