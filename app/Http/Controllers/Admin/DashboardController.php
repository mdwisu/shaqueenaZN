<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::where('user_id', Auth::id())->count();

        $productIds = Product::where('user_id', Auth::id())->pluck('id');

        $totalSales = OrderItem::whereIn('product_id', $productIds)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.quantity');

        $revenue = OrderItem::whereIn('product_id', $productIds)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->select(DB::raw('SUM(order_items.quantity * order_items.price) as total'))
            ->first()
            ->total ?? 0;

        $profit = OrderItem::whereIn('product_id', $productIds)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed') // Assuming 'completed' is the status for finished orders
            ->select(DB::raw('SUM(order_items.quantity * (order_items.price - products.cost_price)) as total_profit'))
            ->first()
            ->total_profit ?? 0;

        $recentOrders = Order::whereHas('orderItems', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalProducts',
            'totalSales',
            'revenue',
            'profit',
            'recentOrders'
        ));
    }
}
