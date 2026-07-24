<x-app-layout>
    <section class="space-y-8 py-12">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <span class="section-title">Shop</span>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900">Browse curated apparel,
                    accessories, and home essentials.</h1>
                <p class="mt-3 max-w-2xl text-slate-600">Filter by category, brand, or keyword to find the perfect pieces
                    for your collection.</p>
            </div>

            <form method="GET" action="{{ route('shop.index') }}" class="flex w-full max-w-md gap-3">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products"
                    class="input-field" />
                <button type="submit" class="button-primary">Search</button>
            </form>
        </div>

        <div class="grid gap-8 lg:grid-cols-[300px_1fr]">
            <aside class="rounded-[2rem] bg-white p-6 shadow-[0_24px_50px_-30px_rgba(15,23,42,0.12)]">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Filters</p>
                <form method="GET" action="{{ route('shop.index') }}" class="mt-6 space-y-5">
                    <input type="hidden" name="q" value="{{ request('q') }}">

                    <div class="space-y-3">
                        <label class="form-label">Category</label>
                        <select name="category" onchange="this.form.submit()" class="input-field">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}"
                                    {{ request('category') === $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="form-label">Brand</label>
                        <select name="brand" onchange="this.form.submit()" class="input-field">
                            <option value="">All brands</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->slug }}"
                                    {{ request('brand') === $brand->slug ? 'selected' : '' }}>{{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </aside>

            <div class="space-y-6">
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <div
                            class="col-span-full rounded-[2rem] bg-white p-10 text-center text-slate-500 shadow-[0_20px_50px_-25px_rgba(15,23,42,0.15)]">
                            No products match your filters. Try adjusting your search.
                        </div>
                    @endforelse
                </div>

                <div class="flex justify-center">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
