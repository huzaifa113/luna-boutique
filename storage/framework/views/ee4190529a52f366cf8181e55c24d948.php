<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="space-y-6">
        
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <?php echo e($this->getFiltersFormComponent()); ?>

        </div>

        
        <div class="flex justify-end">
            <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['color' => 'primary','icon' => 'heroicon-m-printer','onclick' => 'window.print()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'primary','icon' => 'heroicon-m-printer','onclick' => 'window.print()']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                Print Report
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
        </div>

        
        <div class="text-center mb-4 print-header">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Billing & Profit Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($this->getReportTitle()); ?></p>
            <p class="text-xs text-gray-400">Generated on <?php echo e(now()->format('F j, Y g:i A')); ?></p>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['total_revenue'], 2)); ?></div>
                <div class="text-xs text-gray-400 mt-1">Online + POS combined</div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Gross Profit</div>
                <div class="text-2xl font-bold <?php echo e($this->getSummaryData()['gross_profit'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['gross_profit'], 2)); ?></div>
                <div class="text-xs text-gray-400 mt-1">Revenue - Cost of goods</div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Profit Margin</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($this->getSummaryData()['margin']); ?>%</div>
                <div class="text-xs text-gray-400 mt-1">Gross profit / Revenue</div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">Purchases</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['purchase_total'], 2)); ?></div>
                <div class="text-xs text-gray-400 mt-1"><?php echo e($this->getSummaryData()['purchase_count']); ?> purchase(s)</div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Online Store</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Revenue</span><span class="font-medium"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['online_revenue'], 2)); ?></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Orders</span><span class="font-medium"><?php echo e($this->getSummaryData()['online_orders']); ?></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Cost of Goods</span><span class="font-medium"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['online_cost'], 2)); ?></span></div>
                    <div class="flex justify-between border-t pt-2"><span class="text-gray-500">Profit</span><span class="font-bold <?php echo e($this->getSummaryData()['online_profit'] >= 0 ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['online_profit'], 2)); ?></span></div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">POS (In-Store)</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Revenue</span><span class="font-medium"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['pos_revenue'], 2)); ?></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Sales</span><span class="font-medium"><?php echo e($this->getSummaryData()['pos_sales']); ?></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Cost of Goods</span><span class="font-medium"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['pos_cost'], 2)); ?></span></div>
                    <div class="flex justify-between border-t pt-2"><span class="text-gray-500">Profit</span><span class="font-bold <?php echo e($this->getSummaryData()['pos_profit'] >= 0 ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($this->getSummaryData()['pos_profit'], 2)); ?></span></div>
                </div>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Product Performance</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                            <th class="py-2 pr-4">Product</th>
                            <th class="py-2 pr-4 text-right">Online Qty</th>
                            <th class="py-2 pr-4 text-right">POS Qty</th>
                            <th class="py-2 pr-4 text-right">Total Qty</th>
                            <th class="py-2 pr-4 text-right">Online Revenue</th>
                            <th class="py-2 pr-4 text-right">POS Revenue</th>
                            <th class="py-2 pr-4 text-right">Total Revenue</th>
                            <th class="py-2 pr-4 text-right">Cost</th>
                            <th class="py-2 text-right">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->getProductPerformance(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="py-2 pr-4 font-medium"><?php echo e($product['product_name']); ?></td>
                                <td class="py-2 pr-4 text-right"><?php echo e(number_format($product['online_qty'], 2)); ?></td>
                                <td class="py-2 pr-4 text-right"><?php echo e(number_format($product['pos_qty'], 2)); ?></td>
                                <td class="py-2 pr-4 text-right font-medium"><?php echo e(number_format($product['total_qty'], 2)); ?></td>
                                <td class="py-2 pr-4 text-right"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($product['online_revenue'], 2)); ?></td>
                                <td class="py-2 pr-4 text-right"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($product['pos_revenue'], 2)); ?></td>
                                <td class="py-2 pr-4 text-right font-medium"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($product['total_revenue'], 2)); ?></td>
                                <td class="py-2 pr-4 text-right"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($product['cost'], 2)); ?></td>
                                <td class="py-2 text-right font-medium <?php echo e($product['profit'] >= 0 ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($product['profit'], 2)); ?></td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="9" class="py-4 text-center text-gray-400">No product data for the selected filters.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Customer Performance</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                            <th class="py-2 pr-4">Customer</th>
                            <th class="py-2 pr-4">Channel</th>
                            <th class="py-2 pr-4 text-right">Orders / Sales</th>
                            <th class="py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->getCustomerPerformance(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="py-2 pr-4 font-medium"><?php echo e($customer['name']); ?></td>
                                <td class="py-2 pr-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($customer['channel'] === 'Online' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'); ?>">
                                        <?php echo e($customer['channel']); ?>

                                    </span>
                                </td>
                                <td class="py-2 pr-4 text-right"><?php echo e($customer['orders']); ?></td>
                                <td class="py-2 text-right font-medium"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($customer['total'], 2)); ?></td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-400">No customer data for the selected filters.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Vendor Performance</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                            <th class="py-2 pr-4">Vendor</th>
                            <th class="py-2 pr-4 text-right">Purchases</th>
                            <th class="py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->getVendorPerformance(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="py-2 pr-4 font-medium"><?php echo e($vendor['name']); ?></td>
                                <td class="py-2 pr-4 text-right"><?php echo e($vendor['purchases']); ?></td>
                                <td class="py-2 text-right font-medium"><?php echo e($this->getSummaryData()['symbol']); ?> <?php echo e(number_format($vendor['total'], 2)); ?></td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-400">No vendor data for the selected filters.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .fi-sidebar,
            .fi-topbar,
            .fi-header,
            .fi-filters-panel,
            .print-header {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .fi-main-ctn {
                padding: 0 !important;
                margin: 0 !important;
            }

            .fi-main {
                padding: 0 !important;
            }

            .fi-body {
                padding: 0 !important;
            }

            .fi-widget {
                break-inside: avoid;
            }

            .bg-white {
                background: white !important;
                border: 1px solid #e5e7eb !important;
                box-shadow: none !important;
            }

            .dark\:bg-gray-900 {
                background: white !important;
            }

            .dark\:text-white {
                color: #111827 !important;
            }

            .dark\:text-gray-400 {
                color: #6b7280 !important;
            }

            .dark\:border-gray-700 {
                border-color: #e5e7eb !important;
            }

            .dark\:border-gray-800 {
                border-color: #f3f4f6 !important;
            }
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?><?php /**PATH C:\Projects\ecomm\resources\views/filament/pages/billing-report.blade.php ENDPATH**/ ?>