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
        \App\Models\User::create([
            'name' => 'Admin CoffPOS',
            'email' => 'admin@coffpos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        \App\Models\User::create([
            'name' => 'Manager CoffPOS',
            'email' => 'manager@coffpos.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'phone' => '081234567891',
        ]);

        \App\Models\User::create([
            'name' => 'Kasir 1',
            'email' => 'cashier@coffpos.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
            'phone' => '081234567892',
        ]);
    }
}
