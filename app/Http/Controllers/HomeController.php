<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('status', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $latestProducts = Product::where('status', true)
            ->latest()
            ->take(4)
            ->get();

        $featuredCategories = Category::take(3)->get();

        return view('home', compact('featuredProducts', 'latestProducts', 'featuredCategories'));
    }
}
