<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ReturnExchange;
use App\Models\ReturnExchangeItem;
use App\Models\ReturnExchangeAttachment;
use Illuminate\Http\Request;

class ReturnExchangeController extends Controller
{
    public function index()
    {
        $returnExchanges = auth()->user()->returnExchanges()
            ->with('order', 'items', 'coupon')
            ->latest()
            ->paginate(10);

        return view('return-exchanges.index', compact('returnExchanges'));
    }

    public function create()
    {
        $orders = auth()->user()->orders()
            ->where('status', Order::STATUS_DELIVERED)
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->whereDoesntHave('returnExchanges', function ($q) {
                $q->whereIn('status', [
                    ReturnExchange::STATUS_PENDING,
                    ReturnExchange::STATUS_ITEMS_RECEIVED,
                    ReturnExchange::STATUS_APPROVED,
                ]);
            })
            ->with('items')
            ->latest()
            ->get();

        return view('return-exchanges.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'reason' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'exists:order_items,id'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        $order = Order::findOrFail($validated['order_id']);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== Order::STATUS_DELIVERED || $order->payment_status !== Order::PAYMENT_STATUS_PAID) {
            return back()->withErrors(['order_id' => 'This order is not eligible for return.']);
        }

        $selectedItems = $order->items()->whereIn('id', $validated['items'])->get();
        $refundAmount = $selectedItems->sum('total_price');

        $returnExchange = ReturnExchange::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'status' => ReturnExchange::STATUS_PENDING,
            'reason' => $validated['reason'],
            'details' => $validated['details'],
            'refund_amount' => $refundAmount,
            'requested_at' => now(),
        ]);

        foreach ($selectedItems as $item) {
            ReturnExchangeItem::create([
                'return_exchange_id' => $returnExchange->id,
                'order_item_id' => $item->id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ]);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('return-exchanges/' . $returnExchange->id, 'public');

                ReturnExchangeAttachment::create([
                    'return_exchange_id' => $returnExchange->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('return-exchanges.index')
            ->with('success', 'Your return request has been submitted. Please dispatch the item(s) back to us today.');
    }

    public function show(ReturnExchange $returnExchange)
    {
        if ($returnExchange->user_id !== auth()->id()) {
            abort(403);
        }

        $returnExchange->load('order.items', 'items.orderItem', 'attachments', 'coupon');

        return view('return-exchanges.show', compact('returnExchange'));
    }
}