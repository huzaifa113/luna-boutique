<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">My Reviews</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Product Reviews</h1>
            <p class="max-w-2xl text-base text-slate-600">Rate and review products you've purchased.</p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($purchasableProducts->count() > 0)
            <div class="space-y-3">
                <h2 class="text-2xl font-semibold text-slate-900">Products Available for Review</h2>
                <p class="text-sm text-slate-500 mb-4">Click on a product to write a review.</p>

                <div class="space-y-2">
                    @foreach ($purchasableProducts as $product)
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <button type="button" onclick="toggleReview('review-{{ $product->id }}')" class="flex w-full items-center gap-4 p-4 text-left transition hover:bg-slate-50">
                                <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                    @if ($product->images->first())
                                        <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-slate-900">{{ $product->name }}</div>
                                    <div class="text-sm text-slate-500">Order: {{ $product->orderItems->first()->order->order_number ?? 'N/A' }}</div>
                                </div>
                                <svg class="h-5 w-5 flex-shrink-0 text-slate-400 transition-transform duration-200" id="chevron-{{ $product->id }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div id="review-{{ $product->id }}" class="hidden border-t border-slate-200">
                                <div class="p-4 sm:p-6">
                                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="order_id" value="{{ $product->orderItems->first()->order_id ?? '' }}">

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-2">Rating</label>
                                            <div class="star-rating flex gap-1" data-rating="0">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <label class="cursor-pointer star-label" data-value="{{ $i }}">
                                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                                        <svg class="h-8 w-8 text-slate-300 star-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                        </svg>
                                                    </label>
                                                @endfor
                                            </div>
                                            @error('rating')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="title_{{ $product->id }}" class="block text-sm font-medium text-slate-700 mb-1.5">Review Title (Optional)</label>
                                            <input type="text" name="title" id="title_{{ $product->id }}" class="w-full rounded-lg border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100" placeholder="Summarize your experience">
                                        </div>

                                        <div>
                                            <label for="comment_{{ $product->id }}" class="block text-sm font-medium text-slate-700 mb-1.5">Your Review</label>
                                            <textarea name="comment" id="comment_{{ $product->id }}" rows="3" class="w-full rounded-lg border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100" placeholder="Share your thoughts about this product..." required></textarea>
                                            @error('comment')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end gap-3">
                                            <button type="button" onclick="toggleReview('review-{{ $product->id }}')" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</button>
                                            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                                Submit Review
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-[2rem] bg-gradient-to-br from-slate-900 to-slate-800 p-8 text-white shadow-[0_28px_80px_-40px_rgba(15,23,42,0.35)]">
                <h2 class="text-2xl font-semibold">No products available for review</h2>
                <p class="mt-2 text-slate-200/85">You've reviewed all your purchased products or there are no delivered orders yet.</p>
                <a href="{{ route('orders.index') }}" class="button-primary mt-6 inline-flex bg-sky-500 hover:bg-sky-600">View your orders</a>
            </div>
        @endif

        @if ($reviews->count() > 0)
            <div class="space-y-3 pt-6">
                <h2 class="text-2xl font-semibold text-slate-900">Your Reviews</h2>

                <div class="space-y-2">
                    @foreach ($reviews as $review)
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                        @if ($review->product->images->first())
                                            <img src="{{ $review->product->images->first()->url }}" alt="{{ $review->product->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 truncate">{{ $review->product->name }}</div>
                                        <div class="mt-0.5 flex items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="h-3.5 w-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        @if ($review->title)
                                            <div class="mt-1 text-sm font-medium text-slate-700">{{ $review->title }}</div>
                                        @endif
                                        <p class="mt-0.5 text-sm text-slate-600 line-clamp-2">{{ $review->comment }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    <p class="text-xs text-slate-400 whitespace-nowrap">{{ $review->created_at->format('M d, Y') }}</p>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <script>
        function toggleReview(id) {
            const el = document.getElementById(id);
            const chevron = document.getElementById('chevron-' + id.replace('review-', ''));
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                el.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        document.querySelectorAll('.star-rating').forEach(rating => {
            const labels = rating.querySelectorAll('label');
            const svgs = rating.querySelectorAll('.star-svg');

            labels.forEach((label, idx) => {
                label.addEventListener('mouseenter', () => {
                    svgs.forEach((svg, i) => {
                        if (i <= idx) {
                            svg.classList.add('text-amber-400');
                            svg.classList.remove('text-slate-300');
                        }
                    });
                });
                label.addEventListener('mouseleave', () => {
                    svgs.forEach((svg) => {
                        if (!svg.closest('label')?.querySelector('input')?.checked) {
                            svg.classList.remove('text-amber-400');
                            svg.classList.add('text-slate-300');
                        }
                    });
                    // Re-apply checked state
                    const checked = rating.querySelector('input:checked');
                    if (checked) {
                        const checkedIdx = Array.from(labels).findIndex(l => l.querySelector('input') === checked);
                        svgs.forEach((svg, i) => {
                            if (i <= checkedIdx) {
                                svg.classList.add('text-amber-400');
                                svg.classList.remove('text-slate-300');
                            } else {
                                svg.classList.remove('text-amber-400');
                                svg.classList.add('text-slate-300');
                            }
                        });
                    }
                });
                label.querySelector('input').addEventListener('change', function() {
                    const checkedIdx = Array.from(labels).findIndex(l => l.querySelector('input') === this);
                    svgs.forEach((svg, i) => {
                        if (i <= checkedIdx) {
                            svg.classList.add('text-amber-400');
                            svg.classList.remove('text-slate-300');
                        } else {
                            svg.classList.remove('text-amber-400');
                            svg.classList.add('text-slate-300');
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .star-rating .star-svg {
            transition: color 0.15s ease, fill 0.15s ease;
        }
        #chevron-rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</x-app-layout>