<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalOrders = Order::count();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.owner', compact(
            'totalProducts',
            'totalUsers',
            'totalOrders',
            'recentOrders'
        ));
    }
}
