<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('images', 'brand')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('home', compact('featuredProducts', 'categories', 'brands'));
    }
}
