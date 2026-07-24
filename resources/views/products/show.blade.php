<x-app-layout>
    <section class="space-y-12 py-10">
        @if(session('success'))
            <div class="rounded-[1.75rem] border border-emerald-200/70 bg-emerald-50 px-6 py-4 text-emerald-900 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                @if($product->images->isNotEmpty())
                    <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="w-full rounded-[2rem] object-cover" />
                @else
                    <div class="flex h-96 items-center justify-center rounded-[2rem] bg-slate-100 text-slate-500">No product image available</div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex rounded-full bg-indigo-100 px-4 py-1.5 text-sm font-semibold uppercase tracking-[0.25em] text-indigo-700">{{ $product->category->name }}</span>
                            @if($product->brand)
                                <span class="text-slate-500">by {{ $product->brand->name }}</span>
                            @endif
                        </div>
                        <h1 class="text-4xl font-semibold tracking-tight text-slate-900">{{ $product->name }}</h1>
                        <p class="max-w-2xl text-lg leading-8 text-slate-600">{{ $product->short_description }}</p>
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="space-y-1">
                                <p class="text-3xl font-semibold text-slate-900">${{ number_format($product->price, 2) }}</p>
                                @if($product->compare_price && $product->price < $product->compare_price)
                                    <p class="text-sm text-slate-400 line-through">${{ number_format($product->compare_price, 2) }}</p>
                                @endif
                            </div>
                            <div class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                                {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Out of stock' }}
                            </div>
                        </div>
                        <p class="text-slate-600">{{ $product->description }}</p>

                        @auth
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <form action="{{ route('cart.store') }}" method="POST" class="flex w-full items-center gap-3" data-ajax-cart>
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="number" name="quantity" value="1" min="1" class="input-field w-20 text-center" />
                                    <button type="submit" class="button-primary w-full whitespace-nowrap">Add to Cart</button>
                                </form>
                                <form action="{{ route('wishlist.store', $product) }}" method="POST" class="w-full">
                                    @csrf
                                    @php
                                        $inWishlist = auth()->user()->wishlist()->where('product_id', $product->id)->exists();
                                    @endphp
                                    @if($inWishlist)
                                        <button type="submit" formaction="{{ route('wishlist.destroy', $product) }}" formmethod="POST" class="button-secondary w-full text-center">
                                            @csrf
                                            @method('DELETE')
                                            ❤️ Remove from Wishlist
                                        </button>
                                    @else
                                        <button type="submit" class="button-secondary w-full text-center">♡ Add to Wishlist</button>
                                    @endif
                                </form>
                            </div>
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('checkout.index') }}" class="button-primary w-full text-center">Proceed to Checkout</a>
                                <a href="{{ route('shop.index') }}" class="button-secondary w-full text-center">Continue Shopping</a>
                            </div>
                        @else
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('login') }}" class="button-primary w-full text-center">Login to Purchase</a>
                                <a href="{{ route('shop.index') }}" class="button-secondary w-full text-center">Continue Shopping</a>
                            </div>
                        @endauth
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-[1.75rem] bg-white p-6 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                        <h2 class="text-xl font-semibold text-slate-900">Product details</h2>
                        <p class="mt-4 text-slate-600">{{ $product->description }}</p>
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
                                <dd>{{ $product->sku }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-slate-900">Category</dt>
                                <dd>{{ $product->category->name }}</dd>
                            </div>
                            @if($product->brand)
                                <div class="flex justify-between">
                                    <dt class="font-medium text-slate-900">Brand</dt>
                                    <dd>{{ $product->brand->name }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        @if($similarProducts->isNotEmpty())
            <section class="space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">You May Also Like</h2>
                    <p class="mt-1 text-slate-600">Discover more products in this collection.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($similarProducts->take(4) as $similar)
                        <x-product-card :product="$similar" />
                    @endforeach
                </div>
            </section>
        @endif

        <section class="space-y-6" x-data="{ visibleCount: 2 }">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-slate-900">Customer reviews</h2>
                @if($product->reviews->count() > 0)
                    <span class="rounded-full bg-slate-100 px-4 py-1.5 text-sm font-medium text-slate-600">{{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}</span>
                @endif
            </div>
            @forelse($product->reviews as $index => $review)
                <article x-show="visibleCount > {{ $index }}"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="rounded-[2rem] bg-white p-6 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $review->user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-amber-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                    </div>
                    <p class="mt-4 text-slate-600">{{ $review->comment }}</p>
                </article>
            @empty
                <div class="rounded-[2rem] bg-white p-6 text-slate-600 shadow-[0_20px_45px_-20px_rgba(15,23,42,0.1)]">
                    No reviews yet. Be the first to share your thoughts.
                </div>
            @endforelse

            @if($product->reviews->count() > 2)
                <div class="text-center" x-show="visibleCount < {{ $product->reviews->count() }}">
                    <button @click="visibleCount = Math.min(visibleCount + 3, {{ $product->reviews->count() }})" type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        See more <span aria-hidden="true">↓</span>
                    </button>
                </div>
            @endif
        </section>
    </section>
</x-app-layout>