<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Coffee
            ['category_id' => 1, 'code' => 'PROD001', 'name' => 'Espresso', 'description' => 'Kopi espresso klasik', 'price' => 15000, 'cost' => 5000, 'stock' => 100, 'image' => 'products/espresso.jpg', 'is_available' => true],
            ['category_id' => 1, 'code' => 'PROD002', 'name' => 'Americano', 'description' => 'Espresso dengan air panas', 'price' => 18000, 'cost' => 6000, 'stock' => 100, 'image' => 'products/americano.jpg', 'is_available' => true],
            ['category_id' => 1, 'code' => 'PROD003', 'name' => 'Cappuccino', 'description' => 'Espresso dengan susu foam', 'price' => 25000, 'cost' => 8000, 'stock' => 100, 'image' => 'products/cappuccino.jpg', 'is_available' => true],
            ['category_id' => 1, 'code' => 'PROD004', 'name' => 'Latte', 'description' => 'Espresso dengan susu steamed', 'price' => 28000, 'cost' => 9000, 'stock' => 100, 'image' => 'products/latte.jpg', 'is_available' => true],
            ['category_id' => 1, 'code' => 'PROD005', 'name' => 'Mocha', 'description' => 'Latte dengan cokelat', 'price' => 30000, 'cost' => 10000, 'stock' => 100, 'image' => 'products/mocha.jpg', 'is_available' => true],
            
            // Non Coffee
            ['category_id' => 2, 'code' => 'PROD006', 'name' => 'Green Tea Latte', 'description' => 'Teh hijau dengan susu', 'price' => 22000, 'cost' => 7000, 'stock' => 100, 'image' => 'products/green-tea.jpg', 'is_available' => true],
            ['category_id' => 2, 'code' => 'PROD007', 'name' => 'Chocolate', 'description' => 'Cokelat panas premium', 'price' => 20000, 'cost' => 6500, 'stock' => 100, 'image' => 'products/chocolate.jpg', 'is_available' => true],
            ['category_id' => 2, 'code' => 'PROD008', 'name' => 'Lemon Tea', 'description' => 'Teh lemon segar', 'price' => 15000, 'cost' => 5000, 'stock' => 100, 'image' => 'products/lemon-tea.jpg', 'is_available' => true],
            
            // Food
            ['category_id' => 3, 'code' => 'PROD009', 'name' => 'Croissant', 'description' => 'Croissant butter', 'price' => 18000, 'cost' => 7000, 'stock' => 50, 'image' => 'products/croissants.jpg', 'is_available' => true],
            ['category_id' => 3, 'code' => 'PROD010', 'name' => 'Sandwich', 'description' => 'Sandwich ayam', 'price' => 25000, 'cost' => 10000, 'stock' => 50, 'image' => 'products/sandwich.jpg', 'is_available' => true],
            
            // Dessert
            ['category_id' => 4, 'code' => 'PROD011', 'name' => 'Tiramisu', 'description' => 'Tiramisu klasik', 'price' => 35000, 'cost' => 15000, 'stock' => 30, 'image' => 'products/tiramisu.jpg', 'is_available' => true],
            ['category_id' => 4, 'code' => 'PROD012', 'name' => 'Cheesecake', 'description' => 'Cheesecake original', 'price' => 32000, 'cost' => 13000, 'stock' => 30, 'image' => 'products/cheesecake.jpg', 'is_available' => true],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
