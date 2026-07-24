@props(['product'])

@php
    $inWishlist = auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists();
@endphp

<div class="product-card group relative">
    @if($product->images->isNotEmpty())
        @if($product->compare_price && $product->price < $product->compare_price)
            <div class="absolute left-5 top-5 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-md">Sale</div>
        @endif
        <a href="{{ route('products.show', $product) }}" class="block overflow-hidden">
            <div class="aspect-[4/5] w-full">
                <img src="{{ $product->images->first()->url }}" alt="{{ $product->images->first()->alt_text ?? $product->name }}" class="h-full w-full object-cover">
            </div>
        </a>
    @else
        <div class="flex h-72 items-center justify-center bg-slate-100 text-slate-500">No image available</div>
    @endif

    <div class="space-y-4 p-6">
        <a href="{{ route('products.show', $product) }}" class="block text-lg font-semibold text-slate-900 transition hover:text-indigo-600">{{ $product->name }}</a>
        <p class="text-sm text-slate-500">{{ $product->brand?->name ?? 'Brand' }}</p>
        <div class="flex items-center justify-between gap-4">
            <div class="text-xl font-semibold text-slate-900">${{ number_format($product->price, 2) }}</div>
            @if($product->compare_price && $product->price < $product->compare_price)
                <div class="text-sm text-slate-400 line-through">${{ number_format($product->compare_price, 2) }}</div>
            @endif
        </div>

        @auth
            <div class="flex flex-col gap-2">
                <form action="{{ route('cart.store') }}" method="POST" data-ajax-cart>
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="button-primary w-full text-center text-sm">Add to Cart</button>
                </form>
                <form action="{{ route($inWishlist ? 'wishlist.destroy' : 'wishlist.store', $product) }}" method="POST">
                    @csrf
                    @if($inWishlist)
                        @method('DELETE')
                    @endif
                    <button type="submit" class="button-secondary w-full text-center text-sm">
                        {{ $inWishlist ? '❤️ Saved' : '♡ Wishlist' }}
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="button-primary w-full text-center text-sm">Login to Purchase</a>
        @endauth
    </div>
</div>