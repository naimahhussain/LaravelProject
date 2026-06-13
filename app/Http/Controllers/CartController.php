<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart     = session()->get('cart', []);
        $products = [];
        $total    = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $products[] = ['product' => $product, 'quantity' => $quantity];
                $total += $product->price * $quantity;
            }
        }

        return view('cart', compact('products', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + 1;
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'count'   => array_sum($cart),
            'message' => "{$product->name} added to cart!",
        ]);
    }

    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);
        if ($request->quantity < 1) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $request->quantity;
        }
        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Cart updated.');
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Item removed.');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:20',
            'address'        => 'required|string',
        ]);

        $total = 0;
        $items = [];
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $total += $product->price * $quantity;
                $items[] = ['product' => $product, 'quantity' => $quantity];
            }
        }

        $order = Order::create([
            'user_id'        => Auth::id(),
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'address'        => $request->address,
            'total'          => $total,
            'status'         => 'pending',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product']->id,
                'quantity'   => $item['quantity'],
                'price'      => $item['product']->price,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('home')->with('success', "Order #{$order->id} placed successfully! Thank you.");
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => array_sum($cart)]);
    }
}
