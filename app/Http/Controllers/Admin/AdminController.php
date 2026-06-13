<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'totalProducts'    => Product::count(),
            'totalCategories'  => Category::count(),
            'totalOrders'      => Order::count(),
            'totalRevenue'     => Order::where('status', '!=', 'cancelled')->sum('total'),
            'recentOrders'     => Order::latest()->take(10)->get(),
        ]);
    }
}
