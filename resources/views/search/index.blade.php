<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Search</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Search results</h1>
            <p class="max-w-2xl text-base text-slate-600">Showing results for "{{ $queryString ?? request('q') }}".</p>
        </div>

        @if ($products->isEmpty())
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <p class="text-slate-600">No results found for your search. Try different keywords.</p>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </section>
</x-app-layout>
