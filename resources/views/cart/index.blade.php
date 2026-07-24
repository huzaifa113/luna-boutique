<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Cart</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Your selections are ready to checkout.</h1>
            <p class="max-w-2xl text-base text-slate-600">Review items, update quantities, and continue to a seamless
                checkout experience.</p>
        </div>

        @if (session('success'))
            <div
                class="rounded-[1.75rem] border border-emerald-200/70 bg-emerald-50 px-6 py-4 text-emerald-900 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.08)]">
                <h2 class="text-2xl font-semibold text-slate-900">Your cart is empty</h2>
                <p class="mt-3 text-slate-600">Browse our collection and add pieces you love to complete your order.</p>
                <a href="{{ route('shop.index') }}" class="button-primary mt-6 inline-flex">Continue shopping</a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-[1.6fr_0.9fr]">
                <div class="space-y-6">
                    @foreach ($items as $item)
                        <article class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                                <div class="overflow-hidden rounded-[1.75rem] bg-slate-100 lg:w-52">
                                    @if ($item->product->images->isNotEmpty())
                                        <img src="{{ $item->product->images->first()?->url }}"
                                            alt="{{ $item->product->name }}" class="h-44 w-full object-cover sm:h-52">
                                    @else
                                        <div
                                            class="flex h-44 items-center justify-center bg-slate-200 text-slate-500 sm:h-52">
                                            No image</div>
                                    @endif
                                </div>

                                <div class="flex-1 space-y-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <a href="{{ route('products.show', $item->product) }}"
                                                class="text-xl font-semibold text-slate-900 transition hover:text-indigo-600">{{ $item->product->name }}</a>
                                            <p class="mt-2 text-sm text-slate-500">
                                                {{ $item->product->brand?->name ?? 'Brand' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-slate-900">
                                                ${{ number_format($item->product->price, 2) }}</p>
                                            <p class="text-sm text-slate-500">each</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <form action="{{ route('cart.update', $item) }}" method="POST"
                                            class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                            @csrf
                                            @method('PUT')
                                            <label for="quantity-{{ $item->id }}" class="sr-only">Quantity</label>
                                            <input id="quantity-{{ $item->id }}" type="number" name="quantity"
                                                value="{{ $item->quantity }}" min="1"
                                                class="input-field w-full max-w-[120px]" />
                                            <button type="submit" class="button-secondary">Update</button>
                                        </form>

                                        <form action="{{ route('cart.destroy', $item) }}" method="POST"
                                            class="w-full sm:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button-secondary w-full">Remove</button>
                                        </form>
                                    </div>

                                    <p class="text-sm text-slate-500">Subtotal: <span
                                            class="font-semibold text-slate-900">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                        <h2 class="text-xl font-semibold text-slate-900">Order summary</h2>
                        <div class="mt-6 space-y-4 text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping</span>
                                <span>
                                    @if($shipping > 0)
                                        ${{ number_format($shipping, 2) }}
                                        <span class="ml-2 text-xs text-slate-400">(Free on orders over $100)</span>
                                    @else
                                        <span class="text-emerald-600">Free</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-slate-200 pt-4 text-lg font-semibold text-slate-900">
                            <div class="flex items-center justify-between">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('checkout.index') }}"
                            class="button-primary mt-6 inline-flex w-full justify-center">Proceed to checkout</a>
                    </div>
                </aside>
            </div>
        @endif
    </section>
</x-app-layout>
