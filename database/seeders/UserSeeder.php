<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Seller
        User::create([
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        // Extra Sellers
        User::create([
            'name' => 'Second Seller',
            'email' => 'seller2@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        // Customer
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Extra Customers
        User::create([
            'name' => 'Second Customer',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
