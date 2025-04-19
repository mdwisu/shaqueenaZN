<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id());

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment status if provided
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Check if order belongs to the customer
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('orderItems.product', 'paymentProof');

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order)
    {
        // Check if order belongs to the customer
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow cancellation for pending orders with unpaid status
        if ($order->status !== 'pending' || $order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        // Update order status
        $order->status = 'declined';
        $order->save();

        // Return the stock to inventory
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $product->stock_quantity += $item->quantity;
            $product->save();
        }

        return redirect()->route('customer.orders')->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Track the specified order.
     */
    public function track(Order $order)
    {
        // Check if order belongs to the customer
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load order with all necessary relationships
        $order->load('orderItems.product', 'paymentProof');

        // Create tracking information based on order status
        $trackingInfo = $this->getTrackingInfo($order);

        return view('customer.orders.track', compact('order', 'trackingInfo'));
    }

    /**
     * Get tracking information based on order status.
     */
    private function getTrackingInfo(Order $order)
    {
        $tracking = [
            [
                'title' => 'Order Placed',
                'description' => 'Your order has been placed.',
                'date' => $order->created_at->format('d M Y H:i'),
                'completed' => true,
            ],
            [
                'title' => 'Payment Confirmation',
                'description' => $order->payment_status === 'paid' ? 'Payment has been confirmed.' : 'Waiting for payment.',
                'date' => $order->payment_status === 'paid' && $order->paymentProof ? $order->paymentProof->verified_at->format('d M Y H:i') : null,
                'completed' => in_array($order->payment_status, ['paid']),
                'current' => $order->payment_status === 'pending',
            ],
            [
                'title' => 'Processing',
                'description' => 'Your order is being processed.',
                'date' => $order->status === 'processing' ? $order->updated_at->format('d M Y H:i') : null,
                'completed' => in_array($order->status, ['processing', 'completed']),
                'current' => $order->status === 'processing' && $order->payment_status === 'paid',
            ],
            [
                'title' => 'Completed',
                'description' => 'Your order has been completed.',
                'date' => $order->status === 'completed' ? $order->updated_at->format('d M Y H:i') : null,
                'completed' => $order->status === 'completed',
                'current' => $order->status === 'completed',
            ],
        ];

        // If order is declined, add a declined step
        if ($order->status === 'declined') {
            $tracking[] = [
                'title' => 'Cancelled',
                'description' => 'Your order has been cancelled.',
                'date' => $order->updated_at->format('d M Y H:i'),
                'completed' => true,
                'current' => true,
                'declined' => true,
            ];
        }

        return $tracking;
    }

    /**
     * Mark an order as received by the customer.
     */
    public function markAsReceived(Order $order)
    {
        // Check if order belongs to the customer
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if order is in processing status and paid
        if ($order->status !== 'processing' || $order->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'This order cannot be marked as received.');
        }

        // Update order status to completed
        $order->status = 'completed';
        $order->save();

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Order has been marked as received. Thank you for shopping with us!');
    }
}
