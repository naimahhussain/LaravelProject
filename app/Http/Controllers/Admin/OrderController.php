<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Order $order, string $status)
    {
        $order->update(['status' => $status]);
        return back()->with('success', 'Order status updated.');
    }

    public function stats()
    {
        return response()->json([
            'totalProducts'   => \App\Models\Product::count(),
            'totalCategories' => \App\Models\Category::count(),
            'totalOrders'     => Order::count(),
            'totalRevenue'    => Order::where('status', '!=', 'cancelled')->sum('total'),
            'recentOrders'    => Order::latest()->take(10)->get(),
        ]);
    }
}
