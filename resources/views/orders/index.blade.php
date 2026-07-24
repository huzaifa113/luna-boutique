<x-app-layout>
    <section class="space-y-8 py-10">
        <div class="space-y-3">
            <span class="section-title">Orders</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">Order history</h1>
            <p class="max-w-2xl text-base text-slate-600">Review your past purchases and track the details of each order.
            </p>
        </div>

        @if ($orders->isEmpty())
            <div class="rounded-[2rem] bg-white p-8 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.12)]">
                <p class="text-slate-600">You haven't placed any orders yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}"
                        class="group block overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_24px_60px_-30px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5 hover:shadow-[0_30px_70px_-30px_rgba(15,23,42,0.12)]">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-lg font-semibold text-slate-900">{{ $order->order_number }}</p>
                                <p class="mt-1 text-sm text-slate-500">Placed on
                                    {{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="space-y-2 text-right">
                                <p class="text-lg font-semibold text-slate-900">${{ number_format($order->total, 2) }}
                                </p>
                                <span
                                    class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-700">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    </section>
</x-app-layout>
