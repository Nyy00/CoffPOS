<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;

class POSDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if POS data already exists
        if (Category::where('name', 'Coffee')->exists()) {
            $this->command->info('POS data already exists, skipping...');
            return;
        }

        // Create Categories
        $categories = [
            [
                'name' => 'Coffee',
                'description' => 'Hot and cold coffee beverages',
            ],
            [
                'name' => 'Tea',
                'description' => 'Various tea selections',
            ],
            [
                'name' => 'Pastries',
                'description' => 'Fresh baked goods and pastries',
            ],
            [
                'name' => 'Sandwiches',
                'description' => 'Sandwiches and light meals',
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats and desserts',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create products for each category
            $this->createProductsForCategory($category);
        }

        // Create sample customers
        $this->createSampleCustomers();
    }

    private function createProductsForCategory($category)
    {
        $products = [];

        switch ($category->name) {
            case 'Coffee':
                $products = [
                    ['name' => 'Espresso', 'price' => 15000, 'cost' => 8000, 'stock' => 100, 'image' => 'products/espresso.jpg'],
                    ['name' => 'Americano', 'price' => 18000, 'cost' => 10000, 'stock' => 100, 'image' => 'products/americano.jpg'],
                    ['name' => 'Cappuccino', 'price' => 22000, 'cost' => 12000, 'stock' => 100, 'image' => 'products/cappuccino.jpg'],
                    ['name' => 'Latte', 'price' => 25000, 'cost' => 14000, 'stock' => 100, 'image' => 'products/latte.jpg'],
                    ['name' => 'Mocha', 'price' => 28000, 'cost' => 16000, 'stock' => 100, 'image' => 'products/mocha.jpg'],
                    ['name' => 'Macchiato', 'price' => 26000, 'cost' => 15000, 'stock' => 100, 'image' => 'products/espresso.jpg'],
                    ['name' => 'Flat White', 'price' => 24000, 'cost' => 13000, 'stock' => 100, 'image' => 'products/latte.jpg'],
                    ['name' => 'Cold Brew', 'price' => 20000, 'cost' => 11000, 'stock' => 50, 'image' => 'products/americano.jpg'],
                ];
                break;

            case 'Tea':
                $products = [
                    ['name' => 'Earl Grey', 'price' => 16000, 'cost' => 8000, 'stock' => 80, 'image' => 'products/green-tea.jpg'],
                    ['name' => 'Green Tea', 'price' => 15000, 'cost' => 7000, 'stock' => 80, 'image' => 'products/green-tea.jpg'],
                    ['name' => 'Chamomile', 'price' => 17000, 'cost' => 8500, 'stock' => 60, 'image' => 'products/lemon-tea.jpg'],
                    ['name' => 'Jasmine Tea', 'price' => 18000, 'cost' => 9000, 'stock' => 70, 'image' => 'products/green-tea.jpg'],
                    ['name' => 'Iced Tea', 'price' => 14000, 'cost' => 6000, 'stock' => 90, 'image' => 'products/lemon-tea.jpg'],
                ];
                break;

            case 'Pastries':
                $products = [
                    ['name' => 'Croissant', 'price' => 12000, 'cost' => 6000, 'stock' => 30, 'image' => 'products/croissants.jpg'],
                    ['name' => 'Danish Pastry', 'price' => 15000, 'cost' => 8000, 'stock' => 25, 'image' => 'products/croissants.jpg'],
                    ['name' => 'Muffin Blueberry', 'price' => 18000, 'cost' => 9000, 'stock' => 20, 'image' => 'products/croissants.jpg'],
                    ['name' => 'Scone', 'price' => 16000, 'cost' => 8000, 'stock' => 15, 'image' => 'products/croissants.jpg'],
                    ['name' => 'Bagel', 'price' => 14000, 'cost' => 7000, 'stock' => 25, 'image' => 'products/croissants.jpg'],
                ];
                break;

            case 'Sandwiches':
                $products = [
                    ['name' => 'Club Sandwich', 'price' => 35000, 'cost' => 20000, 'stock' => 20, 'image' => 'products/sandwich.jpg'],
                    ['name' => 'Tuna Sandwich', 'price' => 28000, 'cost' => 16000, 'stock' => 25, 'image' => 'products/sandwich.jpg'],
                    ['name' => 'Chicken Sandwich', 'price' => 32000, 'cost' => 18000, 'stock' => 20, 'image' => 'products/sandwich.jpg'],
                    ['name' => 'Veggie Sandwich', 'price' => 25000, 'cost' => 14000, 'stock' => 15, 'image' => 'products/sandwich.jpg'],
                    ['name' => 'BLT Sandwich', 'price' => 30000, 'cost' => 17000, 'stock' => 18, 'image' => 'products/sandwich.jpg'],
                ];
                break;

            case 'Desserts':
                $products = [
                    ['name' => 'Cheesecake', 'price' => 25000, 'cost' => 12000, 'stock' => 12, 'image' => 'products/cheesecake.jpg'],
                    ['name' => 'Chocolate Cake', 'price' => 22000, 'cost' => 11000, 'stock' => 15, 'image' => 'products/chocolate.jpg'],
                    ['name' => 'Tiramisu', 'price' => 28000, 'cost' => 15000, 'stock' => 10, 'image' => 'products/tiramisu.jpg'],
                    ['name' => 'Apple Pie', 'price' => 20000, 'cost' => 10000, 'stock' => 8, 'image' => 'products/tiramisu.jpg'],
                    ['name' => 'Ice Cream', 'price' => 15000, 'cost' => 7000, 'stock' => 30, 'image' => 'products/chocolate.jpg'],
                ];
                break;
        }

        foreach ($products as $productData) {
            Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'description' => 'Delicious ' . strtolower($productData['name']),
                'price' => $productData['price'],
                'cost' => $productData['cost'],
                'stock' => $productData['stock'],
                'image' => $productData['image'] ?? null,
                'is_available' => true,
            ]);
        }
    }

    private function createSampleCustomers()
    {
        $customers = [
            [
                'name' => 'John Doe',
                'phone' => '081234567890',
                'email' => 'john.doe@example.com',
                'points' => 150,
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '081234567891',
                'email' => 'jane.smith@example.com',
                'points' => 200,
            ],
            [
                'name' => 'Bob Johnson',
                'phone' => '081234567892',
                'email' => 'bob.johnson@example.com',
                'points' => 75,
            ],
            [
                'name' => 'Alice Brown',
                'phone' => '081234567893',
                'email' => 'alice.brown@example.com',
                'points' => 300,
            ],
            [
                'name' => 'Charlie Wilson',
                'phone' => '081234567894',
                'email' => 'charlie.wilson@example.com',
                'points' => 50,
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
}