<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin IDs
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

        // Get category IDs
        $categories = Category::all();

        // Electronics Products
        $electronics = $categories->where('name', 'Electronics')->first();
        $this->createProducts($electronics->id, $adminIds, [
            [
                'name' => 'Smartphone X',
                'description' => 'Latest smartphone with advanced features and high-resolution camera.',
                'price' => 5000000,
                'stock_quantity' => 50
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'Powerful laptop for professional use with high performance and long battery life.',
                'price' => 12000000,
                'stock_quantity' => 30
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'Premium sound quality with active noise cancellation.',
                'price' => 1500000,
                'stock_quantity' => 100
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Track your fitness and stay connected with smart notifications.',
                'price' => 2500000,
                'stock_quantity' => 45
            ],
        ]);

        // Clothing Products
        $clothing = $categories->where('name', 'Clothing')->first();
        $this->createProducts($clothing->id, $adminIds, [
            [
                'name' => 'Men\'s Casual Shirt',
                'description' => 'Comfortable cotton shirt for casual wear.',
                'price' => 250000,
                'stock_quantity' => 75
            ],
            [
                'name' => 'Women\'s Dress',
                'description' => 'Elegant dress for special occasions.',
                'price' => 450000,
                'stock_quantity' => 60
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic denim jeans with perfect fit.',
                'price' => 350000,
                'stock_quantity' => 80
            ],
            [
                'name' => 'Sports Shoes',
                'description' => 'Comfortable shoes for sports and casual wear.',
                'price' => 650000,
                'stock_quantity' => 40
            ],
        ]);

        // Home & Kitchen Products
        $homeKitchen = $categories->where('name', 'Home & Kitchen')->first();
        $this->createProducts($homeKitchen->id, $adminIds, [
            [
                'name' => 'Coffee Maker',
                'description' => 'Automatic coffee maker for perfect brew every time.',
                'price' => 800000,
                'stock_quantity' => 35
            ],
            [
                'name' => 'Bedding Set',
                'description' => 'Luxurious cotton bedding set for comfortable sleep.',
                'price' => 1200000,
                'stock_quantity' => 25
            ],
            [
                'name' => 'Kitchen Knife Set',
                'description' => 'Professional chef knife set for precision cutting.',
                'price' => 950000,
                'stock_quantity' => 30
            ],
            [
                'name' => 'Living Room Lamp',
                'description' => 'Modern design lamp to enhance your living room decor.',
                'price' => 450000,
                'stock_quantity' => 50
            ],
        ]);

        // Books Products
        $books = $categories->where('name', 'Books')->first();
        $this->createProducts($books->id, $adminIds, [
            [
                'name' => 'Novel: The Journey',
                'description' => 'Bestselling novel about an adventure journey.',
                'price' => 150000,
                'stock_quantity' => 100
            ],
            [
                'name' => 'Cookbook Collection',
                'description' => 'Collection of recipes from around the world.',
                'price' => 280000,
                'stock_quantity' => 65
            ],
            [
                'name' => 'Business Strategy Guide',
                'description' => 'Comprehensive guide for business development and strategy.',
                'price' => 320000,
                'stock_quantity' => 45
            ],
            [
                'name' => 'Programming Fundamentals',
                'description' => 'Learn the basics of programming and coding.',
                'price' => 275000,
                'stock_quantity' => 55
            ],
        ]);

        // Sports & Outdoors Products
        $sports = $categories->where('name', 'Sports & Outdoors')->first();
        $this->createProducts($sports->id, $adminIds, [
            [
                'name' => 'Yoga Mat',
                'description' => 'Non-slip yoga mat for comfortable practice.',
                'price' => 180000,
                'stock_quantity' => 70
            ],
            [
                'name' => 'Tennis Racket',
                'description' => 'Professional tennis racket for ultimate performance.',
                'price' => 750000,
                'stock_quantity' => 40
            ],
            [
                'name' => 'Camping Tent',
                'description' => 'Waterproof tent for outdoor camping adventures.',
                'price' => 1500000,
                'stock_quantity' => 30
            ],
            [
                'name' => 'Fitness Tracker',
                'description' => 'Track your fitness goals and monitor your progress.',
                'price' => 850000,
                'stock_quantity' => 55
            ],
        ]);
    }
    private function createProducts($categoryId, $adminIds, $products)
    {
        foreach ($products as $productData) {
            $adminId = $adminIds[array_rand($adminIds)];

            Product::create([
                'user_id' => $adminId,
                'category_id' => $categoryId,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock_quantity' => $productData['stock_quantity'],
                'status' => true
            ]);
        }
    }
}
