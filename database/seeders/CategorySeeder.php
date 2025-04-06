<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets'
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion apparel and accessories'
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Home decor and kitchen appliances'
            ],
            [
                'name' => 'Books',
                'description' => 'Books, e-books, and publications'
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment and outdoor gear'
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description']
            ]);
        }
    }
}
