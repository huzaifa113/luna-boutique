<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Dashboard</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="max-w-2xl text-base text-slate-600">You're logged in and ready to manage your boutique experience.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <h2 class="mt-6 text-xl font-semibold text-slate-900">Orders</h2>
                <p class="mt-2 text-slate-600">View and manage your order history.</p>
                <a href="{{ route('orders.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">View orders <span aria-hidden="true">→</span></a>
            </div>

            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h2 class="mt-6 text-xl font-semibold text-slate-900">Wishlist</h2>
                <p class="mt-2 text-slate-600">Browse your saved favorite items.</p>
                <a href="{{ route('wishlist.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">View wishlist <span aria-hidden="true">→</span></a>
            </div>

            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h2 class="mt-6 text-xl font-semibold text-slate-900">Profile</h2>
                <p class="mt-2 text-slate-600">Update your account information.</p>
                <a href="{{ route('profile.edit') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Edit profile <span aria-hidden="true">→</span></a>
            </div>

            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 4H3v16h18V4zM1 2h22v20H1V2z"/><path d="M12 10l3-3M12 10l-2 2M12 10v8"/></svg>
                </div>
                <h2 class="mt-6 text-xl font-semibold text-slate-900">Returns & Exchanges</h2>
                <p class="mt-2 text-slate-600">Initiate and track returns or exchanges.</p>
                <a href="{{ route('return-exchanges.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Manage returns <span aria-hidden="true">→</span></a>
            </div>

            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 text-violet-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h2 class="mt-6 text-xl font-semibold text-slate-900">My Reviews</h2>
                <p class="mt-2 text-slate-600">Rate and review products you've purchased.</p>
                <a href="{{ route('reviews.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">Manage reviews <span aria-hidden="true">→</span></a>
            </div>
        </div>

        <div class="rounded-[2rem] bg-gradient-to-br from-slate-900 to-slate-800 p-8 text-white shadow-[0_28px_80px_-40px_rgba(15,23,42,0.35)]">
            <h2 class="text-2xl font-semibold">Start shopping</h2>
            <p class="mt-2 text-slate-200/85">Browse our curated collection of premium products.</p>
            <a href="{{ route('shop.index') }}" class="button-primary mt-6 inline-flex bg-sky-500 hover:bg-sky-600">Browse shop</a>
        </div>
    </section>
</x-app-layout>