<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\CartItem;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $items = CartItem::with('product.images')
            ->where('user_id', auth()->id())
            ->get();

        $subtotal = $items->reduce(function ($carry, CartItem $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        $shipping = $subtotal >= 100 ? 0 : 20;
        $total = $subtotal + $shipping;

        $requiresAdvancePayment = $items->contains(fn (CartItem $item) => $item->product->adv_payment);

        return view('checkout.index', compact('items', 'subtotal', 'shipping', 'total', 'requiresAdvancePayment'));
    }

    public function store(CheckoutRequest $request, CheckoutService $checkoutService): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('payment_screenshot')) {
            $data['payment_screenshot'] = $request->file('payment_screenshot')->store('payment_proofs', 'public');
        }

        $order = $checkoutService->createOrder(auth()->user(), $data);

        return redirect()->route('orders.show', $order)->with('success', 'Your order has been placed successfully.');
    }

    public function applyCoupon(Request $request, CheckoutService $checkoutService): JsonResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $items = CartItem::with('product')->where('user_id', auth()->id())->get();

        $subtotal = $items->reduce(function ($carry, CartItem $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        $coupon = $checkoutService->resolveCoupon($request->input('coupon_code'), (float) $subtotal);

        $discount = $coupon->calculateDiscount((float) $subtotal);
        $shipping = $subtotal >= 100 ? 0 : 20;
        $total = max(0, $subtotal - $discount + $shipping);

        return response()->json([
            'coupon_code' => $coupon->code,
            'discount' => round($discount, 2),
            'shipping' => $shipping,
            'total' => round($total, 2),
        ]);
    }
}
