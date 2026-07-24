<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CartController extends Controller
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

        return view('cart.index', compact('items', 'subtotal', 'shipping', 'total'));
    }

    public function store(AddToCartRequest $request): JsonResponse|RedirectResponse
    {
        $product = Product::findOrFail($request->input('product_id'));

        $cartItem = CartItem::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $cartItem->quantity = $cartItem->exists
            ? $cartItem->quantity + $request->input('quantity')
            : $request->input('quantity');

        $cartItem->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => CartItem::where('user_id', auth()->id())->count(),
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): JsonResponse|RedirectResponse
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);

        $cartItem->update(['quantity' => $request->input('quantity')]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully.',
            ]);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartItem $cartItem): JsonResponse|RedirectResponse
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);

        $cartItem->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart.',
            ]);
        }

        return back()->with('success', 'Product removed from cart.');
    }
}