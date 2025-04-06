<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentProof;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customer IDs
        $customerIds = User::where('role', 'customer')->pluck('id')->toArray();

        // Get products
        $products = Product::all();

        // Order status options
        $statuses = ['pending', 'processing', 'completed'];
        $paymentStatuses = ['unpaid', 'pending', 'paid'];

        // Create 10 random orders
        for ($i = 0; $i < 10; $i++) {
            $customerId = $customerIds[array_rand($customerIds)];
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            // Create order
            $order = Order::create([
                'user_id' => $customerId,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => $status,
                'payment_status' => $paymentStatus,
                'total_amount' => 0, // Will be calculated after adding items
                'shipping_address' => 'Jl. Sample Address No. ' . rand(1, 100),
                'shipping_city' => 'Jakarta',
                'shipping_state' => 'DKI Jakarta',
                'shipping_zipcode' => '12345',
                'shipping_phone' => '08' . rand(100000000, 999999999),
                'notes' => $i % 3 == 0 ? 'Please deliver in the morning' : null,
            ]);

            // Get random 1-3 products for this order
            $orderProducts = $products->random(rand(1, 3));
            $totalAmount = 0;

            // Create order items
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);

            // Create payment proof for some orders
            if ($paymentStatus === 'pending' || $paymentStatus === 'paid') {
                $status = $paymentStatus === 'paid' ? 'verified' : 'pending';

                PaymentProof::create([
                    'order_id' => $order->id,
                    'image_path' => 'payment_proofs/sample-payment.jpg', // This file needs to exist in storage
                    'notes' => 'Payment via Bank Transfer',
                    'status' => $status,
                    'verified_at' => $status === 'verified' ? now() : null,
                    'verified_by' => $status === 'verified' ? User::where('role', 'admin')->first()->id : null,
                ]);
            }
        }
    }
}
