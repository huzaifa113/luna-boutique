<div>
    @if (session()->has('unit_saved'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 p-4 text-sm text-success-700 dark:border-success-700 dark:bg-success-500/10 dark:text-success-400">
            {{ session('unit_saved') }}
        </div>
    @endif

    {{-- Inventory list --}}
    <div class="overflow-hidden rounded-xl border bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">Product</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">SKU</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">Base Unit</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">Stock</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">Avg Cost</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">Selling Price</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">Units</th>
                    <th class="px-5 py-3.5 text-right font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($products as $product)
                    <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40">
                        <td class="px-5 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $product['name'] }}</td>
                        <td class="px-5 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $product['sku'] }}</td>
                        <td class="px-5 py-4 text-gray-700 dark:text-gray-300">{{ $product['base_unit'] }}</td>
                        <td class="px-5 py-4">
                            <span class="{{ $product['track_stock'] && $product['stock_quantity'] <= $product['low_stock_threshold'] ? 'font-semibold text-danger-600' : 'text-gray-900 dark:text-gray-100' }}">
                                {{ number_format($product['stock_quantity'], 3) }}
                            </span>
                            @if ($product['track_stock'] && $product['stock_quantity'] <= $product['low_stock_threshold'])
                                <span class="ml-1 text-danger-500" title="Low stock">⚠</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-900 dark:text-gray-100">{{ config('pos.currency.symbol', 'Rs') }}{{ number_format($product['cost_price'], 2) }}</td>
                        <td class="px-5 py-4 text-gray-900 dark:text-gray-100">{{ config('pos.currency.symbol', 'Rs') }}{{ number_format($product['price'], 2) }}</td>
                        <td class="px-5 py-4 text-gray-700 dark:text-gray-300">{{ $product['units_count'] }}</td>
                        <td class="px-5 py-4 text-right">
                            <button
                                wire:click="editUnits({{ $product['id'] }})"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-primary-50 px-3.5 py-2 text-xs font-semibold text-primary-700 transition-colors hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20"
                            >
                                Manage Units
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Unit editor modal --}}
    @if ($showUnitModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:key="unit-modal">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="cancelEdit"></div>
            <div class="relative z-10 flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900 dark:shadow-gray-950">
                {{-- Modal header --}}
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Manage Units</h3>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $editingProductName }}</p>
                    </div>
                    <button wire:click="cancelEdit" class="rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal body --}}
                <div class="flex-1 overflow-y-auto px-6 py-5">
                    <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-gray-700">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
                                    <th class="px-4 py-2.5 font-semibold text-gray-500 dark:text-gray-400">Unit</th>
                                    <th class="px-4 py-2.5 font-semibold text-gray-500 dark:text-gray-400">Parent</th>
                                    <th class="px-4 py-2.5 font-semibold text-gray-500 dark:text-gray-400">Contains Qty</th>
                                    <th class="px-4 py-2.5 font-semibold text-gray-500 dark:text-gray-400">Barcode</th>
                                    <th class="px-4 py-2.5 font-semibold text-gray-500 dark:text-gray-400">Selling Price</th>
                                    <th class="px-4 py-2.5 font-semibold text-gray-500 dark:text-gray-400">Purchase Rate</th>
                                    <th class="px-4 py-2.5 font-semibold text-gray-500 dark:text-gray-400">Factor</th>
                                    <th class="px-4 py-2.5"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($unitRows as $index => $row)
                                    <tr>
                                        <td class="px-4 py-2.5">
                                            <select wire:model="unitRows.{{ $index }}.unit_id" class="w-full min-w-[8rem] rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                                <option value="">Select Unit</option>
                                                @foreach ($this->getUnitOptions() as $unitId => $unitName)
                                                    <option value="{{ $unitId }}">{{ $unitName }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <select wire:model="unitRows.{{ $index }}.parent_product_unit_id" class="w-full min-w-[8rem] rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                                <option value="">Top-level</option>
                                                @foreach ($this->getParentUnitOptions() as $parentId => $parentName)
                                                    <option value="{{ $parentId }}">{{ $parentName }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <input type="number" step="0.001" wire:model="unitRows.{{ $index }}.contains_quantity" class="w-24 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <input type="text" wire:model="unitRows.{{ $index }}.barcode" class="w-32 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <input type="number" step="0.01" wire:model="unitRows.{{ $index }}.sale_rate" class="w-24 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <input type="number" step="0.01" wire:model="unitRows.{{ $index }}.purchase_rate" class="w-24 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <input type="number" step="0.001" wire:model="unitRows.{{ $index }}.factor" class="w-24 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                        </td>
                                        <td class="px-4 py-2.5 text-right">
                                            <button wire:click="removeUnitRow({{ $index }})" class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-danger-600 transition-colors hover:bg-danger-50 dark:text-danger-400 dark:hover:bg-danger-500/10">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (empty($unitRows))
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-400">No units defined. Click "Add Unit" to create one.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Modal footer --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                    <button wire:click="addUnitRow" class="inline-flex items-center gap-1.5 rounded-lg border border-primary-600 px-3.5 py-2 text-xs font-medium text-primary-600 transition-colors hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-500/10">
                        + Add Unit
                    </button>
                    <div class="flex gap-2">
                        <button wire:click="cancelEdit" class="rounded-lg border border-gray-300 px-3.5 py-2 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</button>
                        <button wire:click="saveUnits" class="rounded-lg bg-primary-600 px-4 py-2 text-xs font-semibold text-white transition-colors hover:bg-primary-500">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>