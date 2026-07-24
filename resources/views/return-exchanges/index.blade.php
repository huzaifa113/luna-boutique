<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="flex items-center justify-between">
            <div class="space-y-3">
                <span class="section-title">Returns</span>
                <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Your returns</h1>
                <p class="max-w-2xl text-base text-slate-600">Track your return requests and refund coupons.</p>
            </div>
            <a href="{{ route('return-exchanges.create') }}"
                class="inline-flex items-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                New Return
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-[2rem] bg-emerald-50 border border-emerald-200 p-6 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($returnExchanges->isEmpty())
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <p class="text-slate-600">You haven't submitted any return requests yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($returnExchanges as $returnExchange)
                    <a href="{{ route('return-exchanges.show', $returnExchange) }}"
                        class="group block overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5 hover:shadow-[0_30px_70px_-30px_rgba(15,23,42,0.12)]">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-lg font-semibold text-slate-900">{{ $returnExchange->order->order_number }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ \App\Models\ReturnExchange::REASONS[$returnExchange->reason] ?? $returnExchange->reason }}
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-sm font-semibold
                                    @if ($returnExchange->status === 'pending') bg-amber-100 text-amber-700
                                    @elseif ($returnExchange->status === 'items_received') bg-blue-100 text-blue-700
                                    @elseif ($returnExchange->status === 'approved') bg-emerald-100 text-emerald-700
                                    @elseif ($returnExchange->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ ucwords(str_replace('_', ' ', $returnExchange->status)) }}
                                </span>
                                @if ($returnExchange->coupon)
                                    <span class="text-sm font-semibold text-emerald-600">Refund coupon issued</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $returnExchanges->links() }}
            </div>
        @endif
    </section>
</x-app-layout>