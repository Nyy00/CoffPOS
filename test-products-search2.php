<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== TEST PRODUCTS SEARCH WITH ACTUAL NAMES ===\n\n";

// Get all products first
$allProducts = Product::all();
echo "All products:\n";
foreach ($allProducts as $product) {
    echo "- {$product->name}\n";
}

// Test search with actual product names
$searchTerms = ['Espresso', 'Latte', 'Tiramisu'];

foreach ($searchTerms as $searchTerm) {
    echo "\n--- Testing search for: '{$searchTerm}' ---\n";
    
    $query = Product::with('category');
    $query->where(function ($q) use ($searchTerm) {
        $q->where('name', 'like', "%{$searchTerm}%")
          ->orWhere('description', 'like', "%{$searchTerm}%");
    });
    
    $results = $query->get();
    echo "Found {$results->count()} products:\n";
    foreach ($results as $product) {
        echo "- {$product->name}\n";
    }
}