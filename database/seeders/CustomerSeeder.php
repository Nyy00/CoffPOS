<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567801',
                'email' => 'budi@example.com',
                'address' => 'Jl. Merdeka No. 123, Bandung',
                'points' => 150,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'phone' => '081234567802',
                'email' => 'siti@example.com',
                'address' => 'Jl. Sudirman No. 45, Bandung',
                'points' => 200,
            ],
            [
                'name' => 'Ahmad Rizki',
                'phone' => '081234567803',
                'email' => 'ahmad@example.com',
                'address' => 'Jl. Asia Afrika No. 78, Bandung',
                'points' => 100,
            ],
        ];

        foreach ($customers as $customer) {
            \App\Models\Customer::firstOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}
