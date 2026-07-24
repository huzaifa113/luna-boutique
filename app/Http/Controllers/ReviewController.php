<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reviews = $user->reviews()->with('product.images')->latest()->get();

        // Get delivered products the user hasn't reviewed yet
        $reviewedProductIds = Review::where('user_id', $user->id)->pluck('product_id')->toArray();

        $purchasableProducts = Product::whereHas('orderItems.order', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'delivered');
        })->whereNotIn('id', $reviewedProductIds)
          ->with(['images', 'orderItems' => function ($query) use ($user) {
              $query->whereHas('order', function ($q) use ($user) {
                  $q->where('user_id', $user->id)->where('status', 'delivered');
              });
          }])
          ->get();

        return view('reviews.index', compact('reviews', 'purchasableProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        // Verify the user actually purchased this product in this order
        $order = $user->orders()->where('id', $request->order_id)->where('status', 'delivered')->first();
        if (!$order) {
            return back()->with('error', 'You can only review products from your delivered orders.');
        }

        $orderItem = $order->items()->where('product_id', $request->product_id)->first();
        if (!$orderItem) {
            return back()->with('error', 'This product is not in your order.');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return redirect()->route('reviews.index')->with('success', 'Your review has been submitted successfully.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }
}