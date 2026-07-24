<footer class="mt-20 border-t border-slate-200/70 bg-slate-50 py-12">
    <div class="site-container space-y-12">
        <div class="grid gap-10 md:grid-cols-[1.25fr_0.75fr_0.75fr_1.1fr]">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-indigo-700">Luna Boutique</p>
                <h2 class="mt-4 text-3xl font-semibold text-slate-900">A refined storefront for elevated everyday style.
                </h2>
                <p class="mt-4 max-w-xl text-slate-500">Curated essentials, thoughtful details, and a polished shopping
                    experience that works beautifully across desktop and mobile.</p>
            </div>

            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Shop</p>
                <ul class="mt-6 space-y-3 text-slate-700">
                    <li><a href="{{ route('shop.index') }}" class="transition hover:text-indigo-600">All Products</a></li>
                    <li><a href="{{ route('categories.show', ['category' => 'women']) }}"
                            class="transition hover:text-indigo-600">Women</a></li>
                    <li><a href="{{ route('categories.show', ['category' => 'men']) }}"
                            class="transition hover:text-indigo-600">Men</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Company</p>
                <ul class="mt-6 space-y-3 text-slate-700">
                    <li><a href="{{ route('about') }}" class="transition hover:text-indigo-600">About</a></li>
                    <li><a href="{{ route('contact') }}" class="transition hover:text-indigo-600">Contact</a></li>
                    <li><a href="{{ route('home') }}" class="transition hover:text-indigo-600">Home</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Stay connected</p>
                <p class="mt-6 text-slate-500">Subscribe for product launches, curated stories, and exclusive updates.
                </p>
                <form action="{{ route('subscribe.store') }}" method="POST" class="mt-6 flex flex-col gap-3 sm:flex-row">
                    @csrf
                    <input type="email" name="email" placeholder="Email address" aria-label="Email address" class="input-field" required>
                    <button type="submit" class="button-primary w-full sm:w-auto">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="border-t border-slate-200/70 pt-6 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'Luna Boutique') }}. All rights reserved.
        </div>
    </div>
</footer>
