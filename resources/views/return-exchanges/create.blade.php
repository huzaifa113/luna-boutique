<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Returns</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Request a return</h1>
            <p class="max-w-2xl text-base text-slate-600">Submit a return request for a delivered order and receive a refund coupon.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-[2rem] bg-red-50 border border-red-200 p-6 text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Terms & Conditions --}}
        <div class="rounded-[2rem] bg-amber-50 border border-amber-200 p-6">
            <h3 class="font-semibold text-amber-800 mb-2">Return Policy</h3>
            <ul class="text-sm text-amber-700 space-y-1 list-disc list-inside">
                <li>You must dispatch the item(s) back to us on the same day of the request.</li>
                <li>Items must be unused and in original packaging.</li>
                <li>Once approved, you will receive a refund coupon to use on your next order.</li>
                <li>Processing time: 5-7 business days after we receive the items.</li>
            </ul>
        </div>

        @if ($orders->isEmpty())
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <p class="text-slate-600">You don't have any eligible orders for return. Only delivered and paid orders can be returned.</p>
            </div>
        @else
            <form method="POST" action="{{ route('return-exchanges.store') }}" enctype="multipart/form-data"
                class="space-y-6 rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                @csrf

                {{-- Order Selection --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Order</label>
                    <select name="order_id" id="order_id" required
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-slate-400 focus:ring-0">
                        <option value="">Choose an order...</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}" data-items="{{ $order->items->toJson() }}">
                                {{ $order->order_number }} — ${{ number_format($order->total, 2) }} ({{ $order->created_at->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Items --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Items to Return</label>
                    <div id="items-container" class="space-y-2">
                        <p class="text-sm text-slate-500">Please select an order first.</p>
                    </div>
                </div>

                {{-- Reason --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Reason</label>
                    <select name="reason" required
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-slate-400 focus:ring-0">
                        <option value="">Select a reason...</option>
                        @foreach (\App\Models\ReturnExchange::REASONS as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Details --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Additional Details</label>
                    <textarea name="details" rows="4"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-slate-400 focus:ring-0"
                        placeholder="Please provide any additional information about your return..."></textarea>
                </div>

                {{-- Attachments --}}
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
        @endif
    </section>

    @push('scripts')
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
    @endpush
</x-app-layout>