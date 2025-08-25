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
        // Admin for testing shipping notification
        User::create([
            'name' => 'Dwi Susanto Admin',
            'email' => 'dwisusanto784@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Customer for testing checkout flow
        User::create([
            'name' => 'Dwi Susanto Customer', 
            'email' => 'dwi.susanto487@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        // Extra admin for multiple admin notifications
        User::create([
            'name' => 'Second Admin',
            'email' => 'admin2@shaqueenazn.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Extra customer for testing
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@shaqueenazn.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        // Owner account
        User::create([
            'name' => 'Owner ShaqeenaZN',
            'email' => 'owner@shaqueenazn.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);
    }
}
