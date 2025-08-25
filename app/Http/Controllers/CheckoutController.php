<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Controllers\NotificationController;
use App\Mail\NewOrderNotification;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('cartItems.product')
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $subtotal = $cart->cartItems->sum(fn($item) => $item->product->final_price * $item->quantity);
        $estimatedShippingCost = $this->calculateEstimatedShippingCost($subtotal);

        return view('checkout.index', compact('cart', 'subtotal', 'estimatedShippingCost'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_zipcode' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'terms' => 'required'
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->with('cartItems.product')
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Check stock before processing
        foreach ($cart->cartItems as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                return redirect()->route('cart.index')->with('error', 'Some products in your cart are no longer available in the requested quantity');
            }
        }

        // Start DB transaction
        DB::beginTransaction();

        try {
            $subtotal = $cart->cartItems->sum(fn($item) => $item->product->final_price * $item->quantity);
            $estimatedShippingCost = $this->calculateEstimatedShippingCost($subtotal);

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'total_amount' => $subtotal,
                'estimated_shipping_cost' => $estimatedShippingCost,
                'shipping_confirmed' => false,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zipcode' => $request->shipping_zipcode,
                'shipping_phone' => $request->shipping_phone,
                'notes' => $request->notes
            ]);

            // Create order items
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->final_price
                ]);

                // Update product stock
                $product = $item->product;
                $product->stock_quantity -= $item->quantity;
                $product->save();
            }

            // Clear cart
            $cart->cartItems()->delete();

            // Send notification to all admins about new order
            $adminUsers = User::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                NotificationController::createNotification(
                    $admin->id,
                    'new_order',
                    'Pesanan Baru',
                    "Pesanan baru #{$order->order_number} dari {$order->user->name} sebesar Rp " . number_format($order->total_amount, 0, ',', '.'),
                    ['order_id' => $order->id, 'customer_name' => $order->user->name]
                );
                
                // Send email notification to admin
                Mail::to($admin->email)->send(new NewOrderNotification($order));
            }

            DB::commit();

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully. Please complete the payment.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function calculateEstimatedShippingCost($subtotal)
    {
        // Jakarta/sekitar zone logic
        if ($subtotal >= 500000) {
            return 0;      // Free shipping
        } elseif ($subtotal >= 200000) {
            return 15000;  // Rp 15k
        } elseif ($subtotal >= 100000) {
            return 25000;  // Rp 25k
        } else {
            return 35000;  // Rp 35k
        }
        
        // TODO: Add logic for other zones based on shipping_city
    }
}
