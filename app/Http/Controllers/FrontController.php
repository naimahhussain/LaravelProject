<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class FrontController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $products   = Product::with('category')->where('is_active', true)->latest()->get();

        return view('home', compact('categories', 'products'));
    }
}
