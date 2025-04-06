<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        return view('checkout.index', compact('cart'));
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
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'total_amount' => $cart->total,
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
                    'price' => $item->product->price
                ]);

                // Update product stock
                $product = $item->product;
                $product->stock_quantity -= $item->quantity;
                $product->save();
            }

            // Clear cart
            $cart->cartItems()->delete();

            DB::commit();

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully. Please complete the payment.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
