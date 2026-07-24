<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $products = $category->products()
            ->with('images', 'brand')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}
