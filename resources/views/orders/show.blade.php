<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-2">
            <span class="section-title">Order details</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Order {{ $order->order_number }}</h1>
            <p class="text-slate-600">Placed on {{ $order->created_at->format('M d, Y') }}</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.6fr_0.9fr]">
            <div class="space-y-6">
                <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-slate-900">Order details</h2>
                            <p class="text-slate-600">Status: {{ ucfirst($order->status) }}</p>
                        </div>
                        <span
                            class="inline-flex rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ ucfirst($order->payment_status) }}</span>
                    </div>

                    <div class="mt-8 overflow-hidden rounded-[1.75rem] border border-slate-200">
                        <div
                            class="grid grid-cols-[2fr_1fr_1fr] gap-4 bg-slate-50 px-6 py-4 text-sm font-semibold text-slate-700">
                            <div>Product</div>
                            <div class="text-right">Qty</div>
                            <div class="text-right">Total</div>
                        </div>
                        <div class="space-y-3 p-6">
                            @foreach ($order->items as $item)
                                <div class="grid grid-cols-[2fr_1fr_1fr] gap-4 text-sm text-slate-600">
                                    <div>{{ $item->product_name }}</div>
                                    <div class="text-right">{{ $item->quantity }}</div>
                                    <div class="text-right">${{ number_format($item->total_price, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-[2rem] bg-white p-6 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                        <h3 class="text-xl font-semibold text-slate-900">Shipping address</h3>
                        <div class="mt-4 space-y-1 text-slate-600">
                            <p>{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                            <p>{{ $order->shippingAddress->address_line1 }}</p>
                            @if ($order->shippingAddress->address_line2)
                                <p>{{ $order->shippingAddress->address_line2 }}</p>
                            @endif
                            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                                {{ $order->shippingAddress->postal_code }}</p>
                            <p>{{ $order->shippingAddress->country }}</p>
                        </div>
                    </div>
                    <div class="rounded-[2rem] bg-white p-6 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                        <h3 class="text-xl font-semibold text-slate-900">Billing address</h3>
                        <div class="mt-4 space-y-1 text-slate-600">
                            <p>{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</p>
                            <p>{{ $order->billingAddress->address_line1 }}</p>
                            @if ($order->billingAddress->address_line2)
                                <p>{{ $order->billingAddress->address_line2 }}</p>
                            @endif
                            <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}
                                {{ $order->billingAddress->postal_code }}</p>
                            <p>{{ $order->billingAddress->country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                    <h3 class="text-xl font-semibold text-slate-900">Summary</h3>
                    <div class="mt-6 space-y-4 text-slate-600">
                        <div class="flex items-center justify-between">
                            <span>Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Discount</span>
                            <span>-${{ number_format($order->discount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Shipping</span>
                            <span>${{ number_format($order->shipping, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Tax</span>
                            <span>${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-4 text-lg font-semibold text-slate-900">
                            <div class="flex items-center justify-between">
                                <span>Total</span>
                                <span>${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 space-y-3 text-slate-600">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Payment method</p>
                            <p class="font-semibold text-slate-900">
                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                        @if ($order->payment_method === \App\Models\Order::PAYMENT_METHOD_ONLINE)
                            @if ($order->payment_channel)
                                <div>
                                    <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Paid through</p>
                                    <p class="font-semibold text-slate-900">
                                        {{ ucwords(str_replace('_', ' ', $order->payment_channel)) }}</p>
                                </div>
                            @endif
                            @if ($order->transaction_id)
                                <div>
                                    <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Transaction ID</p>
                                    <p class="font-semibold text-slate-900">{{ $order->transaction_id }}</p>
                                </div>
                            @endif
                            @if ($order->payment_screenshot)
                                <div>
                                    <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Payment proof</p>
                                    <a href="{{ Storage::url($order->payment_screenshot) }}" target="_blank"
                                        class="mt-2 block overflow-hidden rounded-2xl border border-slate-200">
                                        <img src="{{ Storage::url($order->payment_screenshot) }}"
                                            alt="Payment screenshot" class="max-h-64 w-full object-cover">
                                    </a>
                                </div>
                            @endif
                            @if ($order->payment_status === \App\Models\Order::PAYMENT_STATUS_PENDING)
                                <p class="rounded-2xl bg-amber-50 p-4 text-sm text-amber-700">Your payment is being
                                    verified. We'll confirm your order once it's approved.</p>
                            @endif
                        @endif
                        @if ($order->coupon)
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Coupon</p>
                                <p class="font-semibold text-slate-900">{{ $order->coupon->code }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-app-layout>
