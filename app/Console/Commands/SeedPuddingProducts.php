<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;

class SeedPuddingProducts extends Command
{
    protected $signature = 'seed:pudding-products';
    protected $description = 'Seed pudding products for testing search functionality';

    public function handle()
    {
        $this->info('Checking for pudding products...');
        
        // Check if pudding products already exist
        $existingPudding = Product::where(function($q) {
            if (config('database.default') === 'pgsql') {
                $q->where('name', 'ILIKE', '%pudding%');
            } else {
                $q->where('name', 'like', '%pudding%');
            }
        })->count();
        $this->info("Found {$existingPudding} existing pudding products");
        
        // Get dessert category
        $dessertCategory = Category::where(function($q) {
            if (config('database.default') === 'pgsql') {
                $q->where('name', 'ILIKE', '%dessert%');
            } else {
                $q->where('name', 'like', '%dessert%');
            }
        })->first();
        if (!$dessertCategory) {
            $this->error('Dessert category not found! Creating one...');
            $dessertCategory = Category::create([
                'name' => 'Dessert',
                'description' => 'Sweet desserts and treats'
            ]);
        }
        
        $puddingProducts = [
            [
                'category_id' => $dessertCategory->id,
                'code' => 'PROD013',
                'name' => 'Chocolate Pudding',
                'description' => 'Pudding cokelat lembut',
                'price' => 20000,
                'cost' => 8000,
                'stock' => 25,
                'image' => 'products/pudding.jpg',
                'is_available' => true
            ],
            [
                'category_id' => $dessertCategory->id,
                'code' => 'PROD014',
                'name' => 'Vanilla Pudding',
                'description' => 'Pudding vanilla klasik',
                'price' => 18000,
                'cost' => 7000,
                'stock' => 25,
                'image' => 'products/vanilla-pudding.jpg',
                'is_available' => true
            ]
        ];
        
        foreach ($puddingProducts as $product) {
            $existing = Product::where('code', $product['code'])->first();
            if (!$existing) {
                Product::create($product);
                $this->info("Created product: {$product['name']}");
            } else {
                $this->info("Product already exists: {$product['name']}");
            }
        }
        
        // Test search
        $this->info('Testing search functionality...');
        $searchResults = Product::where(function($q) {
            if (config('database.default') === 'pgsql') {
                $q->where('name', 'ILIKE', '%pudding%');
            } else {
                $q->where('name', 'like', '%pudding%');
            }
        })->get();
        $this->info("Search results for 'pudding': {$searchResults->count()} products found");
        
        foreach ($searchResults as $product) {
            $this->line("- {$product->name} (ID: {$product->id})");
        }
        
        $this->info('Done!');
        return 0;
    }
}