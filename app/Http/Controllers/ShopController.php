<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images', 'brand', 'category')
            ->where('is_active', true);

        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $category = Category::where('slug', $categorySlug)->first();

            if ($category) {
                // Include products from the selected category and all its children
                $categoryIds = collect([$category->id]);

                // Fetch all descendant category IDs recursively
                $childIds = $category->children()->pluck('id');
                while ($childIds->isNotEmpty()) {
                    $categoryIds = $categoryIds->merge($childIds);
                    $childIds = Category::whereIn('parent_id', $childIds)->pluck('id');
                }

                $query->whereIn('category_id', $categoryIds);
            } else {
                // Fallback: try direct slug match
                $query->whereHas('category', function ($builder) use ($categorySlug) {
                    $builder->where('slug', $categorySlug);
                });
            }
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($builder) use ($request) {
                $builder->where('slug', $request->input('brand'));
            });
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('shop.index', compact('products', 'categories', 'brands'));
    }
}
