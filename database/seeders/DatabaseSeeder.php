<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $categories = [
            ['name' => 'Phones',      'slug' => 'phones'],
            ['name' => 'Laptops',     'slug' => 'laptops'],
            ['name' => 'TVs',         'slug' => 'tvs'],
            ['name' => 'Tablets',     'slug' => 'tablets'],
            ['name' => 'Cameras',     'slug' => 'cameras'],
            ['name' => 'Audio',       'slug' => 'audio'],
            ['name' => 'Gaming',      'slug' => 'gaming'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $products = [
            ['category' => 'Phones',      'name' => 'iPhone 15 Pro',         'price' => 999.99,  'stock' => 50,  'description' => 'Apple iPhone 15 Pro with A17 Pro chip, titanium design, and advanced camera system.'],
            ['category' => 'Phones',      'name' => 'Samsung Galaxy S24',    'price' => 849.99,  'stock' => 40,  'description' => 'Samsung flagship with Snapdragon 8 Gen 3, 200MP camera, and AI features.'],
            ['category' => 'Phones',      'name' => 'Google Pixel 8',        'price' => 699.99,  'stock' => 30,  'description' => 'Google Pixel 8 with Tensor G3 chip and the best computational photography.'],
            ['category' => 'Laptops',     'name' => 'MacBook Pro 14"',       'price' => 1999.99, 'stock' => 20,  'description' => 'Apple MacBook Pro with M3 Pro chip, 18GB RAM, and stunning Liquid Retina display.'],
            ['category' => 'Laptops',     'name' => 'Dell XPS 15',           'price' => 1599.99, 'stock' => 15,  'description' => 'Dell XPS 15 with Intel Core i9, NVIDIA RTX 4060, and 4K OLED display.'],
            ['category' => 'Laptops',     'name' => 'Lenovo ThinkPad X1',    'price' => 1399.99, 'stock' => 18,  'description' => 'Business ultrabook with military-grade durability and 28-hour battery life.'],
            ['category' => 'TVs',         'name' => 'Samsung 65" QLED 4K',  'price' => 1299.99, 'stock' => 10,  'description' => 'Stunning 65-inch QLED 4K TV with Quantum HDR and smart features.'],
            ['category' => 'TVs',         'name' => 'LG OLED C3 55"',       'price' => 1499.99, 'stock' => 8,   'description' => 'LG OLED with perfect blacks, Dolby Vision, and webOS smart platform.'],
            ['category' => 'Tablets',     'name' => 'iPad Pro 12.9"',        'price' => 1099.99, 'stock' => 25,  'description' => 'Apple iPad Pro with M2 chip, Liquid Retina XDR display, and Apple Pencil support.'],
            ['category' => 'Tablets',     'name' => 'Samsung Galaxy Tab S9', 'price' => 799.99,  'stock' => 20,  'description' => 'Samsung Galaxy Tab S9 with Snapdragon 8 Gen 2 and DeX desktop mode.'],
            ['category' => 'Cameras',     'name' => 'Sony A7 IV',            'price' => 2499.99, 'stock' => 12,  'description' => 'Full-frame mirrorless camera with 33MP sensor and 4K video recording.'],
            ['category' => 'Cameras',     'name' => 'Canon EOS R6 Mark II',  'price' => 2299.99, 'stock' => 10,  'description' => 'Canon mirrorless with 40fps burst shooting and advanced subject tracking.'],
            ['category' => 'Audio',       'name' => 'Sony WH-1000XM5',       'price' => 349.99,  'stock' => 60,  'description' => 'Industry-leading noise canceling headphones with 30-hour battery.'],
            ['category' => 'Audio',       'name' => 'AirPods Pro 2nd Gen',   'price' => 249.99,  'stock' => 75,  'description' => 'Apple AirPods Pro with Adaptive Transparency and USB-C charging.'],
            ['category' => 'Gaming',      'name' => 'PlayStation 5',         'price' => 499.99,  'stock' => 15,  'description' => 'Sony PS5 with ultra-high-speed SSD, 4K gaming, and DualSense controller.'],
            ['category' => 'Gaming',      'name' => 'Xbox Series X',         'price' => 499.99,  'stock' => 15,  'description' => 'Microsoft Xbox Series X — the most powerful Xbox ever with 12 teraflops.'],
            ['category' => 'Accessories', 'name' => 'Logitech MX Master 3',  'price' => 99.99,   'stock' => 100, 'description' => 'Advanced wireless mouse with MagSpeed scroll wheel and ergonomic design.'],
            ['category' => 'Accessories', 'name' => 'Samsung 65W Charger',   'price' => 49.99,   'stock' => 150, 'description' => 'Super-fast 65W USB-C charger compatible with laptops, phones, and tablets.'],
        ];

        foreach ($products as $p) {
            $category = Category::where('name', $p['category'])->first();
            Product::create([
                'category_id' => $category->id,
                'name'        => $p['name'],
                'slug'        => Str::slug($p['name']),
                'description' => $p['description'],
                'price'       => $p['price'],
                'stock'       => $p['stock'],
                'is_active'   => true,
            ]);
        }

        // Sample orders
        $allProducts = Product::all();
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $customers = [
            ['name' => 'Ahmed Ali',    'email' => 'ahmed@example.com',    'phone' => '+966501234567'],
            ['name' => 'Sara Mohamed', 'email' => 'sara@example.com',     'phone' => '+966507654321'],
            ['name' => 'Khalid Hassan','email' => 'khalid@example.com',   'phone' => '+966509876543'],
            ['name' => 'Naimah Fares', 'email' => 'naimah@example.com',  'phone' => '+966503456789'],
        ];

        foreach ($customers as $customer) {
            $items = $allProducts->random(rand(1, 3));
            $total = $items->sum('price');
            $order = Order::create([
                'customer_name'  => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'address'        => 'Riyadh, Saudi Arabia',
                'total'          => $total,
                'status'         => $statuses[array_rand($statuses)],
            ]);
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->id,
                    'quantity'   => 1,
                    'price'      => $item->price,
                ]);
            }
        }
    }
}
