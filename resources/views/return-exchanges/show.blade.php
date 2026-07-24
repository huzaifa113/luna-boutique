<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Returns</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Return details</h1>
            <p class="max-w-2xl text-base text-slate-600">Order: {{ $returnExchange->order->order_number }}</p>
        </div>

        {{-- Status Progress --}}
        <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
            <div class="flex items-center justify-between">
                @php
                    $steps = [
                        'pending' => ['label' => 'Requested', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'items_received' => ['label' => 'Items Received', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                        'approved' => ['label' => 'Approved', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                    if ($returnExchange->status === 'rejected') {
                        $steps = array_slice($steps, 0, 2);
                        $steps['rejected'] = ['label' => 'Rejected', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'];
                    }
                    $currentIndex = array_search($returnExchange->status, array_keys($steps));
                @endphp

                @foreach ($steps as $key => $step)
                    <div class="flex flex-col items-center {{ $loop->index <= $currentIndex ? 'text-emerald-600' : 'text-slate-300' }}">
                        <div class="rounded-full p-3 {{ $loop->index <= $currentIndex ? 'bg-emerald-100' : 'bg-slate-100' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm font-medium">{{ $step['label'] }}</p>
                        @if ($loop->index === $currentIndex)
                            <p class="text-xs text-slate-500">{{ $returnExchange->updated_at->format('M d, Y') }}</p>
                        @endif
                    </div>
                    @if (!$loop->last)
                        <div class="flex-1 h-px {{ $loop->index < $currentIndex ? 'bg-emerald-400' : 'bg-slate-200' }} mx-4"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Details --}}
        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <h3 class="font-semibold text-slate-900 mb-4">Request Information</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Status</dt>
                        <dd>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                @if ($returnExchange->status === 'pending') bg-amber-100 text-amber-700
                                @elseif ($returnExchange->status === 'items_received') bg-blue-100 text-blue-700
                                @elseif ($returnExchange->status === 'approved') bg-emerald-100 text-emerald-700
                                @elseif ($returnExchange->status === 'rejected') bg-red-100 text-red-700 @endif">
                                {{ ucwords(str_replace('_', ' ', $returnExchange->status)) }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Reason</dt>
                        <dd class="font-medium text-slate-900">{{ \App\Models\ReturnExchange::REASONS[$returnExchange->reason] ?? $returnExchange->reason }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Requested</dt>
                        <dd class="font-medium text-slate-900">{{ $returnExchange->requested_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    @if ($returnExchange->admin_processed_at)
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Processed</dt>
                        <dd class="font-medium text-slate-900">{{ $returnExchange->admin_processed_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <h3 class="font-semibold text-slate-900 mb-4">Items</h3>
                <div class="space-y-3">
                    @foreach ($returnExchange->items as $item)
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $item->orderItem->product_name }}</p>
                                <p class="text-xs text-slate-500">Qty: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                            </div>
                            <p class="text-sm font-semibold text-slate-900">${{ number_format($item->total_price, 2) }}</p>
                        </div>
                    @endforeach
                    <div class="flex justify-between pt-2 border-t border-slate-200">
                        <span class="text-sm font-semibold text-slate-900">Total Refund</span>
                        <span class="text-sm font-semibold text-emerald-600">${{ number_format($returnExchange->refund_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details & Attachments --}}
        @if ($returnExchange->details)
        <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
            <h3 class="font-semibold text-slate-900 mb-2">Additional Details</h3>
            <p class="text-sm text-slate-600">{{ $returnExchange->details }}</p>
        </div>
        @endif

        @if ($returnExchange->attachments->isNotEmpty())
        <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
            <h3 class="font-semibold text-slate-900 mb-4">Attachments</h3>
            <div class="flex flex-wrap gap-3">
                @foreach ($returnExchange->attachments as $attachment)
                    <a href="{{ Storage::url($attachment->path) }}" target="_blank"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $attachment->original_name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Coupon --}}
        @if ($returnExchange->coupon)
        <div class="rounded-[2rem] bg-emerald-50 border border-emerald-200 p-6">
            <h3 class="font-semibold text-emerald-800 mb-2">Refund Coupon Issued</h3>
            <p class="text-sm text-emerald-700 mb-3">A refund coupon has been generated for your approved return. You can use it on your next purchase.</p>
            <div class="bg-white rounded-xl p-4 border border-emerald-200">
                <p class="text-xs text-slate-500 mb-1">Coupon Code</p>
                <p class="text-lg font-bold text-slate-900 tracking-wider">{{ $returnExchange->coupon->code }}</p>
                <p class="text-sm text-emerald-600 mt-1">${{ number_format($returnExchange->coupon->value, 2) }} off your next order</p>
                <p class="text-xs text-slate-500 mt-1">Expires: {{ $returnExchange->coupon->expires_at->format('M d, Y') }}</p>
            </div>
        </div>
        @endif

        {{-- Admin Notes --}}
        @if ($returnExchange->admin_notes)
        <div class="rounded-[2rem] bg-slate-50 border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900 mb-2">Admin Notes</h3>
            <p class="text-sm text-slate-600">{{ $returnExchange->admin_notes }}</p>
        </div>
        @endif

        <div class="flex justify-center">
            <a href="{{ route('return-exchanges.index') }}"
                class="inline-flex items-center rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to Returns
            </a>
        </div>
    </section>
</x-app-layout>