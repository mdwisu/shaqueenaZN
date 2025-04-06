<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::where('user_id', Auth::id())->count();

        $pendingOrders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $completedOrders = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->count();

        $recentOrders = Order::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.customer', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'recentOrders'
        ));
    }
}
