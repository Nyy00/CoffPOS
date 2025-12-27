<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@coffpos.com'],
            [
                'name' => 'Admin CoffPOS',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        // Create manager user if not exists
        \App\Models\User::firstOrCreate(
            ['email' => 'manager@coffpos.com'],
            [
                'name' => 'Manager CoffPOS',
                'password' => bcrypt('password'),
                'role' => 'manager',
                'phone' => '081234567891',
            ]
        );

        // Create cashier user if not exists
        \App\Models\User::firstOrCreate(
            ['email' => 'cashier@coffpos.com'],
            [
                'name' => 'Kasir 1',
                'password' => bcrypt('password'),
                'role' => 'cashier',
                'phone' => '081234567892',
            ]
        );
    }
}
