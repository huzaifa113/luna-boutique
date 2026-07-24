<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Wishlist</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Your saved favorites await.</h1>
            <p class="max-w-2xl text-base text-slate-600">Keep track of items you love and move them to checkout when
                you're ready.</p>
        </div>

        @if (session('success'))
            <div
                class="rounded-[1.75rem] border border-emerald-200/70 bg-emerald-50 px-6 py-4 text-emerald-900 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.08)]">
                <h2 class="text-2xl font-semibold text-slate-900">Your wishlist is empty</h2>
                <p class="mt-3 text-slate-600">Add products to your wishlist while you browse our collections.</p>
                <a href="{{ route('shop.index') }}" class="button-primary mt-6 inline-flex">Browse products</a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($items as $item)
                    <article class="rounded-[2rem] bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                        @if ($item->product->images->isNotEmpty())
                            <a href="{{ route('products.show', $item->product) }}" class="block overflow-hidden">
                                <img src="{{ $item->product->images->first()?->url }}" alt="{{ $item->product->name }}"
                                    class="mb-5 h-72 w-full rounded-[1.5rem] object-cover" />
                            </a>
                        @endif

                        <div class="space-y-4">
                            <a href="{{ route('products.show', $item->product) }}"
                                class="block text-xl font-semibold text-slate-900 transition hover:text-indigo-600">{{ $item->product->name }}</a>
                            <p class="text-sm text-slate-500">${{ number_format($item->product->price, 2) }}</p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <form action="{{ route('cart.store') }}" method="POST" class="w-full" data-ajax-cart>
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="button-primary w-full">Add to cart</button>
                                </form>
                                <form action="{{ route('wishlist.destroy', $item->product) }}" method="POST"
                                    class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-secondary w-full">Remove</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-app-layout>
