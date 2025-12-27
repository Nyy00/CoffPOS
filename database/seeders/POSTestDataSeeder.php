<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class POSTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds for POS testing
     */
    public function run(): void
    {
        // Create test categories
        $categories = [
            [
                'name' => 'Coffee',
                'description' => 'Hot and cold coffee beverages',
                'image' => null
            ],
            [
                'name' => 'Tea',
                'description' => 'Various tea selections',
                'image' => null
            ],
            [
                'name' => 'Pastries',
                'description' => 'Fresh baked goods',
                'image' => null
            ],
            [
                'name' => 'Snacks',
                'description' => 'Light snacks and treats',
                'image' => null
            ],
            [
                'name' => 'Beverages',
                'description' => 'Non-coffee beverages',
                'image' => null
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }

        // Get created categories
        $coffeeCategory = Category::where('name', 'Coffee')->first();
        $teaCategory = Category::where('name', 'Tea')->first();
        $pastryCategory = Category::where('name', 'Pastries')->first();
        $snackCategory = Category::where('name', 'Snacks')->first();
        $beverageCategory = Category::where('name', 'Beverages')->first();

        // Create test products
        $products = [
            // Coffee Products
            [
                'name' => 'Espresso',
                'description' => 'Strong black coffee shot',
                'code' => 'ESP001',
                'price' => 15000,
                'cost' => 8000,
                'stock' => 100,
                'min_stock' => 10,
                'category_id' => $coffeeCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Americano',
                'description' => 'Espresso with hot water',
                'code' => 'AME001',
                'price' => 18000,
                'cost' => 10000,
                'stock' => 80,
                'min_stock' => 10,
                'category_id' => $coffeeCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk and foam',
                'code' => 'CAP001',
                'price' => 25000,
                'cost' => 15000,
                'stock' => 60,
                'min_stock' => 10,
                'category_id' => $coffeeCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Latte',
                'description' => 'Espresso with steamed milk',
                'code' => 'LAT001',
                'price' => 28000,
                'cost' => 16000,
                'stock' => 50,
                'min_stock' => 10,
                'category_id' => $coffeeCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Mocha',
                'description' => 'Espresso with chocolate and steamed milk',
                'code' => 'MOC001',
                'price' => 32000,
                'cost' => 18000,
                'stock' => 40,
                'min_stock' => 10,
                'category_id' => $coffeeCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Iced Coffee',
                'description' => 'Cold brew coffee with ice',
                'code' => 'ICE001',
                'price' => 22000,
                'cost' => 12000,
                'stock' => 3, // Low stock for testing
                'min_stock' => 10,
                'category_id' => $coffeeCategory->id,
                'is_available' => true,
                'image' => null
            ],

            // Tea Products
            [
                'name' => 'Earl Grey Tea',
                'description' => 'Classic bergamot flavored tea',
                'code' => 'TEA001',
                'price' => 20000,
                'cost' => 8000,
                'stock' => 45,
                'min_stock' => 10,
                'category_id' => $teaCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Green Tea',
                'description' => 'Healthy antioxidant rich tea',
                'code' => 'TEA002',
                'price' => 18000,
                'cost' => 7000,
                'stock' => 55,
                'min_stock' => 10,
                'category_id' => $teaCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Chamomile Tea',
                'description' => 'Relaxing herbal tea',
                'code' => 'TEA003',
                'price' => 22000,
                'cost' => 9000,
                'stock' => 35,
                'min_stock' => 10,
                'category_id' => $teaCategory->id,
                'is_available' => true,
                'image' => null
            ],

            // Pastry Products
            [
                'name' => 'Croissant',
                'description' => 'Buttery flaky pastry',
                'code' => 'PAS001',
                'price' => 15000,
                'cost' => 8000,
                'stock' => 25,
                'min_stock' => 5,
                'category_id' => $pastryCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Chocolate Muffin',
                'description' => 'Rich chocolate muffin',
                'code' => 'PAS002',
                'price' => 18000,
                'cost' => 10000,
                'stock' => 20,
                'min_stock' => 5,
                'category_id' => $pastryCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Blueberry Scone',
                'description' => 'Fresh blueberry scone',
                'code' => 'PAS003',
                'price' => 20000,
                'cost' => 12000,
                'stock' => 15,
                'min_stock' => 5,
                'category_id' => $pastryCategory->id,
                'is_available' => true,
                'image' => null
            ],

            // Snack Products
            [
                'name' => 'Cookies',
                'description' => 'Assorted cookies',
                'code' => 'SNK001',
                'price' => 12000,
                'cost' => 6000,
                'stock' => 30,
                'min_stock' => 10,
                'category_id' => $snackCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Sandwich',
                'description' => 'Fresh made sandwich',
                'code' => 'SNK002',
                'price' => 35000,
                'cost' => 20000,
                'stock' => 12,
                'min_stock' => 5,
                'category_id' => $snackCategory->id,
                'is_available' => true,
                'image' => null
            ],

            // Beverage Products
            [
                'name' => 'Orange Juice',
                'description' => 'Fresh squeezed orange juice',
                'code' => 'BEV001',
                'price' => 25000,
                'cost' => 12000,
                'stock' => 40,
                'min_stock' => 10,
                'category_id' => $beverageCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Mineral Water',
                'description' => 'Bottled mineral water',
                'code' => 'BEV002',
                'price' => 8000,
                'cost' => 3000,
                'stock' => 100,
                'min_stock' => 20,
                'category_id' => $beverageCategory->id,
                'is_available' => true,
                'image' => null
            ],
            [
                'name' => 'Smoothie',
                'description' => 'Mixed fruit smoothie',
                'code' => 'BEV003',
                'price' => 30000,
                'cost' => 15000,
                'stock' => 25,
                'min_stock' => 5,
                'category_id' => $beverageCategory->id,
                'is_available' => true,
                'image' => null
            ]
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['code' => $productData['code']],
                $productData
            );
        }

        // Create test customers
        $customers = [
            [
                'name' => 'John Doe',
                'phone' => '081234567890',
                'email' => 'john.doe@example.com',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'loyalty_points' => 150
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '081234567891',
                'email' => 'jane.smith@example.com',
                'address' => 'Jl. Thamrin No. 456, Jakarta',
                'loyalty_points' => 250
            ],
            [
                'name' => 'Bob Johnson',
                'phone' => '081234567892',
                'email' => 'bob.johnson@example.com',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'loyalty_points' => 75
            ],
            [
                'name' => 'Alice Brown',
                'phone' => '081234567893',
                'email' => 'alice.brown@example.com',
                'address' => 'Jl. Kuningan No. 321, Jakarta',
                'loyalty_points' => 320
            ],
            [
                'name' => 'Charlie Wilson',
                'phone' => '081234567894',
                'email' => 'charlie.wilson@example.com',
                'address' => 'Jl. Senayan No. 654, Jakarta',
                'loyalty_points' => 180
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::firstOrCreate(
                ['phone' => $customerData['phone']],
                $customerData
            );
        }

        // Create test cashier user if not exists
        User::firstOrCreate(
            ['email' => 'cashier@coffpos.com'],
            [
                'name' => 'Test Cashier',
                'email' => 'cashier@coffpos.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'email_verified_at' => now()
            ]
        );

        // Create test admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@coffpos.com'],
            [
                'name' => 'Test Admin',
                'email' => 'admin@coffpos.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]
        );

        $this->command->info('POS test data seeded successfully!');
        $this->command->info('Test Users Created:');
        $this->command->info('- Cashier: cashier@coffpos.com / password');
        $this->command->info('- Admin: admin@coffpos.com / password');
        $this->command->info('');
        $this->command->info('Test Data Created:');
        $this->command->info('- 5 Categories');
        $this->command->info('- 18 Products (including low stock items)');
        $this->command->info('- 5 Customers with loyalty points');
    }
}