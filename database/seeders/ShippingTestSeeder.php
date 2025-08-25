<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShippingTestSeeder extends Seeder
{
    public function run()
    {
        // Get test users
        $customer = User::where('email', 'dwi.susanto487@gmail.com')->first();
        $admin = User::where('email', 'dwisusanto784@gmail.com')->first();

        if (!$customer || !$admin) {
            $this->command->error('Test users not found. Run UserSeeder first.');
            return;
        }

        // Get test products
        $testProducts = Product::where('name', 'LIKE', 'Test Product%')->get();
        
        if ($testProducts->isEmpty()) {
            $this->command->error('Test products not found. Run ProductSeeder first.');
            return;
        }

        // Create sample orders for different shipping scenarios
        $this->createShippingScenarioOrders($customer, $testProducts);
        
        // Create some notifications for testing
        $this->createTestNotifications($customer, $admin);
    }

    private function createShippingScenarioOrders($customer, $testProducts)
    {
        $scenarios = [
            [
                'products' => [['product' => $testProducts->where('price', 50000)->first(), 'qty' => 1]],
                'estimated_shipping' => 35000,
                'final_shipping' => 40000,
                'shipping_confirmed' => true,
                'status' => 'processing',
                'notes' => 'Alamat jauh dari Jakarta, ongkir disesuaikan'
            ],
            [
                'products' => [['product' => $testProducts->where('price', 150000)->first(), 'qty' => 1]],
                'estimated_shipping' => 25000,
                'final_shipping' => null,
                'shipping_confirmed' => false,
                'status' => 'pending',
                'notes' => null
            ],
            [
                'products' => [['product' => $testProducts->where('price', 300000)->first(), 'qty' => 1]],
                'estimated_shipping' => 15000,
                'final_shipping' => 0,
                'shipping_confirmed' => true,
                'status' => 'processing',
                'notes' => 'Gratis ongkir untuk customer setia'
            ],
            [
                'products' => [['product' => $testProducts->where('price', 600000)->first(), 'qty' => 1]],
                'estimated_shipping' => 0,
                'final_shipping' => 0,
                'shipping_confirmed' => true,
                'status' => 'completed',
                'notes' => 'Gratis ongkir otomatis'
            ],
        ];

        foreach ($scenarios as $index => $scenario) {
            $subtotal = 0;
            foreach ($scenario['products'] as $item) {
                $subtotal += $item['product']->price * $item['qty'];
            }

            $totalAmount = $subtotal;
            if ($scenario['shipping_confirmed'] && $scenario['final_shipping'] !== null) {
                $totalAmount += $scenario['final_shipping'];
            } else {
                $totalAmount += $scenario['estimated_shipping'];
            }

            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'TEST-' . strtoupper(Str::random(8)),
                'status' => $scenario['status'],
                'payment_status' => 'unpaid',
                'total_amount' => $totalAmount,
                'estimated_shipping_cost' => $scenario['estimated_shipping'],
                'final_shipping_cost' => $scenario['final_shipping'],
                'shipping_confirmed' => $scenario['shipping_confirmed'],
                'shipping_notes' => $scenario['notes'],
                'shipping_address' => 'Jl. Test Address No. ' . ($index + 1),
                'shipping_city' => $index < 2 ? 'Jakarta Selatan' : 'Bekasi',
                'shipping_state' => 'DKI Jakarta',
                'shipping_zipcode' => '12345',
                'shipping_phone' => '081234567890',
                'notes' => 'Test order untuk shipping scenario ' . ($index + 1),
                'created_at' => now()->subDays(rand(1, 7))
            ]);

            // Create order items
            foreach ($scenario['products'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['qty'],
                    'price' => $item['product']->price
                ]);
            }

            $this->command->info("Created test order: {$order->order_number} - {$scenario['status']}");
        }
    }

    private function createTestNotifications($customer, $admin)
    {
        // Notifications for customer
        $customerNotifications = [
            [
                'type' => 'shipping_confirmed',
                'title' => 'Ongkos Kirim Dikonfirmasi',
                'message' => 'Ongkos kirim untuk order #TEST-ABC123 telah dikonfirmasi sebesar Rp 40.000',
                'read_at' => null
            ],
            [
                'type' => 'shipping_confirmed', 
                'title' => 'Ongkos Kirim Dikonfirmasi',
                'message' => 'Ongkos kirim untuk order #TEST-DEF456 telah dikonfirmasi sebesar GRATIS',
                'read_at' => now()->subHours(2)
            ]
        ];

        foreach ($customerNotifications as $notif) {
            Notification::create([
                'user_id' => $customer->id,
                'type' => $notif['type'],
                'title' => $notif['title'],
                'message' => $notif['message'],
                'read_at' => $notif['read_at'],
                'created_at' => now()->subHours(rand(1, 24))
            ]);
        }

        // Notifications for admin
        $adminNotifications = [
            [
                'type' => 'new_order',
                'title' => 'Pesanan Baru',
                'message' => 'Pesanan baru #TEST-GHI789 dari Dwi Susanto Customer sebesar Rp 190.000',
                'read_at' => null
            ],
            [
                'type' => 'new_order',
                'title' => 'Pesanan Baru', 
                'message' => 'Pesanan baru #TEST-JKL012 dari Test Customer sebesar Rp 315.000',
                'read_at' => now()->subHours(1)
            ]
        ];

        foreach ($adminNotifications as $notif) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $notif['type'],
                'title' => $notif['title'],
                'message' => $notif['message'],
                'read_at' => $notif['read_at'],
                'created_at' => now()->subHours(rand(1, 12))
            ]);
        }

        $this->command->info('Created test notifications for admin and customer');
    }
}