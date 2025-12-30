<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== TEST PRODUCTS SEARCH ===\n\n";

// Test search functionality
$searchTerm = 'coffee';
echo "Testing search for: '{$searchTerm}'\n";

$query = Product::with('category');

// Apply search filter (same as controller)
$query->where(function ($q) use ($searchTerm) {
    $q->where('name', 'like', "%{$searchTerm}%")
      ->orWhere('description', 'like', "%{$searchTerm}%");
});

$results = $query->get();

echo "Found {$results->count()} products:\n";
foreach ($results as $product) {
    echo "- {$product->name} (Category: {$product->category->name})\n";
}

echo "\n=== TEST CATEGORY FILTER ===\n";

$categories = Category::all();
echo "Available categories:\n";
foreach ($categories as $category) {
    $productCount = Product::where('category_id', $category->id)->count();
    echo "- {$category->name} (ID: {$category->id}) - {$productCount} products\n";
}

// Test category filter
if ($categories->count() > 0) {
    $testCategory = $categories->first();
    echo "\nTesting filter for category: {$testCategory->name}\n";
    
    $categoryProducts = Product::where('category_id', $testCategory->id)->get();
    echo "Found {$categoryProducts->count()} products in this category:\n";
    foreach ($categoryProducts as $product) {
        echo "- {$product->name}\n";
    }
}

echo "\n=== TEST AVAILABILITY FILTER ===\n";

$availableCount = Product::where('is_available', 1)->count();
$unavailableCount = Product::where('is_available', 0)->count();

echo "Available products: {$availableCount}\n";
echo "Unavailable products: {$unavailableCount}\n";