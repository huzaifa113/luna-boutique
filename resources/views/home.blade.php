<x-app-layout>
    <section
        class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-16 text-white shadow-[0_40px_120px_-35px_rgba(15,23,42,0.65)] sm:px-10 lg:px-14">
        <div class="site-container grid gap-12 lg:grid-cols-[1.1fr_0.9fr] items-center">
            <div class="space-y-6">
                <span
                    class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-semibold tracking-[0.2em] text-white/85">New
                    collection</span>
                <h1 class="max-w-3xl text-5xl font-black leading-tight tracking-[-0.04em] sm:text-6xl">Sleek essentials
                    for modern living.</h1>
                <p class="max-w-2xl text-lg leading-8 text-slate-200/90">Discover premium products designed for everyday
                    comfort, polished style, and effortless refinement across your wardrobe and home.</p>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="{{ route('shop.index') }}" class="button-primary bg-sky-500 hover:bg-sky-600">Shop
                        collection</a>
                    <a href="{{ route('about') }}"
                        class="button-secondary bg-white/10 text-white border-white/20 hover:bg-white/20">Our story</a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 sm:grid-rows-2">
                <div
                    class="rounded-[2rem] bg-white/10 p-6 backdrop-blur-xl shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <p class="text-sm uppercase tracking-[0.3em] text-sky-200">Featured edit</p>
                    <h2 class="mt-4 text-2xl font-semibold text-white">Refined wardrobe staples</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-200/90">Handpicked pieces for timeless outfits and
                        modern silhouettes.</p>
                </div>
                <div class="rounded-[2rem] overflow-hidden bg-slate-950 shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <img src="https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=1200&q=80"
                        alt="Boutique hero" class="h-full w-full object-cover">
                </div>
                <div class="rounded-[2rem] overflow-hidden bg-slate-950 shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80"
                        alt="Product styling" class="h-full w-full object-cover">
                </div>
                <div
                    class="rounded-[2rem] bg-white/10 p-6 backdrop-blur-xl shadow-[0_18px_45px_-18px_rgba(15,23,42,0.5)]">
                    <p class="text-sm uppercase tracking-[0.3em] text-sky-200">Rapid delivery</p>
                    <h2 class="mt-4 text-2xl font-semibold text-white">Fast, premium shipping</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-200/90">Enjoy quick delivery and elevated packaging for
                        every order.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-10 py-16">
        <div class="space-y-3">
            <span class="section-title">Featured products</span>
            <h2 class="text-4xl font-semibold tracking-tight text-slate-900">Discover this season's best sellers.</h2>
            <p class="max-w-2xl text-base text-slate-600">Shop the latest curated collection of wardrobe, accessories,
                and home picks.</p>
        </div>

        <div class="card-grid">
            @forelse($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <div
                    class="col-span-full rounded-[2rem] bg-white p-10 text-center text-slate-500 shadow-[0_20px_50px_-25px_rgba(15,23,42,0.15)]">
                    No featured products yet.
                </div>
            @endforelse
        </div>
    </section>

    <section class="space-y-10 py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="section-title">Collections</span>
                <h2 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900">Shop by category</h2>
                <p class="mt-3 max-w-2xl text-base text-slate-600">Browse collections designed for every mood and room.
                </p>
            </div>
            <a href="{{ route('shop.index') }}"
                class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Browse all categories
                →</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($categories as $category)
                <x-category-card :category="$category" />
            @empty
                <div
                    class="col-span-full rounded-[2rem] bg-white p-10 text-center text-slate-500 shadow-[0_20px_50px_-25px_rgba(15,23,42,0.15)]">
                    Categories will be available soon.
                </div>
            @endforelse
        </div>
    </section>

    <section
        class="rounded-[2rem] bg-slate-900 px-6 py-12 text-white shadow-[0_28px_80px_-40px_rgba(15,23,42,0.35)] sm:px-10">
        <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
            <div>
                <span class="section-title bg-white/10 text-white">Why shop with us</span>
                <h2 class="mt-4 text-4xl font-semibold tracking-tight text-white">A refined shopping experience from
                    browse to delivery.</h2>
                <p class="mt-4 max-w-xl text-slate-200/85">Enjoy premium packaging, seamless checkout, and dedicated
                    support for every order.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.75rem] bg-white/10 p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-semibold text-white">Fast shipping</h3>
                    <p class="mt-3 text-slate-300">Quick delivery with premium packaging for every purchase.</p>
                </div>
                <div class="rounded-[1.75rem] bg-white/10 p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-semibold text-white">Curated quality</h3>
                    <p class="mt-3 text-slate-300">Handpicked products chosen for lasting style and comfort.</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
