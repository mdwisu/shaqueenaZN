<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Mail\ShippingConfirmedNotification;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment status if provided
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order number if provided
        if ($request->has('order_number') && $request->order_number) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        // Filter by customer name if provided
        if ($request->has('customer') && $request->customer) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%')
                    ->orWhere('email', 'like', '%' . $request->customer . '%');
            });
        }

        // Show latest orders first
        $orders = $query->latest()->paginate(10);

        // Get order stats for dashboard cards
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'declined' => Order::where('status', 'declined')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Load order with all necessary relationships
        $order->load('orderItems.product', 'user', 'paymentProof');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,declined',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Update order status
        $order->status = $newStatus;
        $order->save();

        // If order is declined, return products to inventory
        if ($newStatus === 'declined' && $oldStatus !== 'declined') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                $product->stock_quantity += $item->quantity;
                $product->save();
            }
        }

        // If order was declined but now is not, deduct products from inventory again
        if ($oldStatus === 'declined' && $newStatus !== 'declined') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                $product->stock_quantity = max(0, $product->stock_quantity - $item->quantity);
                $product->save();
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Download invoice for the specified order.
     */
    public function downloadInvoice(Order $order)
    {
        // Load order with all necessary relationships
        $order->load('orderItems.product', 'user');

        // You would typically use a PDF library like DOMPDF here
        // For simplicity, we'll just return a view for now
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Delete the specified order (soft delete).
     */
    public function destroy(Order $order)
    {
        // If you're using soft deletes in your Order model
        $order->delete();

        // If order is not delivered/completed yet, return products to inventory
        if ($order->status !== 'completed') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                $product->stock_quantity += $item->quantity;
                $product->save();
            }
        }

        return redirect()->route('admin.orders')
            ->with('success', 'Order removed successfully.');
    }

    /**
     * Update payment status of the specified order.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,pending,paid,failed',
        ]);

        // Update payment status
        $order->payment_status = $request->payment_status;

        // If payment is marked as paid and there's no payment proof yet,
        // we'll consider this as manual payment confirmation by admin
        if ($request->payment_status === 'paid' && !$order->paymentProof) {
            // You might want to create a payment proof record here
            // with a note that it was manually verified by admin
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Payment status updated successfully.');
    }

    /**
     * Confirm shipping cost for the specified order.
     */
    public function confirmShipping(Request $request, Order $order)
    {
        $request->validate([
            'final_shipping_cost' => 'required|numeric|min:0',
            'shipping_notes' => 'nullable|string|max:500',
        ]);

        // Update shipping information
        $order->final_shipping_cost = $request->final_shipping_cost;
        $order->shipping_confirmed = true;
        $order->shipping_notes = $request->shipping_notes;
        
        // Update total amount with final shipping cost
        $order->total_amount = $order->orderItems->sum(fn($item) => $item->price * $item->quantity) + $request->final_shipping_cost;
        
        $order->save();

        // Send notification to customer about shipping confirmation
        NotificationController::createNotification(
            $order->user_id,
            'shipping_confirmed',
            'Ongkos Kirim Dikonfirmasi',
            "Ongkos kirim untuk order #{$order->order_number} telah dikonfirmasi sebesar " . 
            ($request->final_shipping_cost == 0 ? 'GRATIS' : 'Rp ' . number_format($request->final_shipping_cost, 0, ',', '.')),
            ['order_id' => $order->id]
        );

        // Send email notification to customer
        Mail::to($order->user->email)->send(new ShippingConfirmedNotification($order));

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Shipping cost confirmed successfully.');
    }

    /**
     * Filter orders by date range.
     */
    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Order::with('user');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Apply other filters (status, payment status, etc.)
        // ...

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }
}
