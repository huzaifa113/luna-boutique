<nav x-data="{ open: false }"
    class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur-xl shadow-sm">
    <div class="site-container flex items-center justify-between gap-6 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div
                class="flex h-12 w-12 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-600 to-sky-500 text-xl font-black text-white">
                L</div>
            <div class="hidden sm:block">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Lifestyle boutique</p>
                <h1 class="text-lg font-semibold text-slate-900">{{ config('app.name', 'Luna Boutique') }}</h1>
            </div>
        </a>

        <div class="hidden items-center gap-8 lg:flex">
            <a href="{{ route('home') }}"
                class="text-sm font-medium text-slate-600 transition hover:text-slate-900 {{ request()->routeIs('home') ? 'text-slate-900' : '' }}">Home</a>
            <a href="{{ route('shop.index') }}"
                class="text-sm font-medium text-slate-600 transition hover:text-slate-900 {{ request()->routeIs('shop.index') ? 'text-slate-900' : '' }}">Shop</a>
            <a href="{{ route('about') }}"
                class="text-sm font-medium text-slate-600 transition hover:text-slate-900 {{ request()->routeIs('about') ? 'text-slate-900' : '' }}">About</a>
            <a href="{{ route('contact') }}"
                class="text-sm font-medium text-slate-600 transition hover:text-slate-900 {{ request()->routeIs('contact') ? 'text-slate-900' : '' }}">Contact</a>
        </div>

        <div class="hidden items-center gap-3 lg:flex">
            @guest
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="button-primary">Register</a>
                @endif
            @else
                @php
                    $wishlistItems = Auth::user()->wishlist()->with('product.images')->latest()->take(3)->get();
                    $cartItems = Auth::user()->cartItems()->with('product.images')->latest()->take(3)->get();
                    $wishlistCount = Auth::user()->wishlist()->count();
                    $cartCount = Auth::user()->cartItems()->count();
                @endphp

                <div class="relative" x-data="{ openWishlist: false, wTimeout: null }"
                    @mouseenter="clearTimeout(wTimeout); openWishlist = true"
                    @mouseleave="wTimeout = setTimeout(() => { openWishlist = false }, 200)">
                    <a href="{{ route('wishlist.index') }}"
                        class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:-translate-y-0.5 hover:bg-slate-100">Wishlist</a>
                    <div x-show="openWishlist"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        x-cloak
                        class="absolute right-0 z-50 mt-2 w-80">
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-xl">
                            @if ($wishlistItems->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($wishlistItems as $item)
                                        <a href="{{ route('products.show', $item->product) }}" class="flex items-center gap-3 rounded-2xl p-2 transition hover:bg-slate-50">
                                            <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                @if ($item->product->images->first())
                                                    <img src="{{ $item->product->images->first()->url }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-xs text-slate-400">No img</div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-slate-900">{{ $item->product->name }}</p>
                                                <p class="text-sm text-slate-500">${{ number_format($item->product->price, 2) }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-3 border-t border-slate-100 pt-3">
                                    <a href="{{ route('wishlist.index') }}"
                                        class="block rounded-2xl bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-indigo-700">View All ({{ $wishlistCount }})</a>
                                </div>
                            @else
                                <div class="py-4 text-center">
                                    <p class="text-sm text-slate-500">Your wishlist is empty</p>
                                    <a href="{{ route('shop.index') }}"
                                        class="mt-3 inline-block rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">Browse Shop</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ openCart: false, cTimeout: null }"
                    @mouseenter="clearTimeout(cTimeout); openCart = true"
                    @mouseleave="cTimeout = setTimeout(() => { openCart = false }, 200)">
                    <a href="{{ route('cart.index') }}" class="button-primary inline-flex">Cart</a>
                    <div x-show="openCart"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        x-cloak
                        class="absolute left-1/2 z-50 mt-2 w-80 -translate-x-1/2">
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-xl">
                            @if ($cartItems->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($cartItems as $item)
                                        <a href="{{ route('products.show', $item->product) }}" class="flex items-center gap-3 rounded-2xl p-2 transition hover:bg-slate-50">
                                            <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                @if ($item->product->images->first())
                                                    <img src="{{ $item->product->images->first()->url }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-xs text-slate-400">No img</div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-slate-900">{{ $item->product->name }}</p>
                                                <p class="text-sm text-slate-500">{{ $item->quantity }} × ${{ number_format($item->product->price, 2) }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-3 border-t border-slate-100 pt-3">
                                    <a href="{{ route('cart.index') }}"
                                        class="block rounded-2xl bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-indigo-700">View Cart ({{ $cartCount }})</a>
                                </div>
                            @else
                                <div class="py-4 text-center">
                                    <p class="text-sm text-slate-500">Your cart is empty</p>
                                    <a href="{{ route('shop.index') }}"
                                        class="mt-3 inline-block rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">Browse Shop</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ openProfile: false, pTimeout: null }"
                    @mouseenter="clearTimeout(pTimeout); openProfile = true"
                    @mouseleave="pTimeout = setTimeout(() => { openProfile = false }, 200)">
                    <button type="button"
                        class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:-translate-y-0.5">{{ Auth::user()->name }}</button>
                    <div x-show="openProfile"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        x-cloak
                        class="absolute right-0 z-50 mt-2 w-60">
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Dashboard</a>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                            <div class="border-t border-slate-200"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-3 text-left text-sm text-slate-700 hover:bg-slate-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>

        <button @click="open = !open"
            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-3 text-slate-700 shadow-sm transition hover:bg-slate-100 lg:hidden"
            aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M3 5h14a1 1 0 010 2H3a1 1 0 110-2zm0 4h14a1 1 0 010 2H3a1 1 0 110-2zm0 4h14a1 1 0 010 2H3a1 1 0 110-2z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-cloak class="border-t border-slate-200 bg-white px-4 py-4 lg:hidden">
        <div class="space-y-3">
            <a href="{{ route('home') }}"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100">Home</a>
            <a href="{{ route('shop.index') }}"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100">Shop</a>
            <a href="{{ route('about') }}"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100">About</a>
            <a href="{{ route('contact') }}"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100">Contact</a>
        </div>
        <div class="mt-4 border-t border-slate-200 pt-4">
            @guest
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="block rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="block rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Register</a>
                @endif
            @else
                <a href="{{ route('wishlist.index') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100">Wishlist</a>
                <a href="{{ route('cart.index') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-semibold text-indigo-600 hover:bg-slate-100">Cart</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-slate-200">Log
                        Out</button>
                </form>
            @endguest
        </div>
    </div>
</nav>