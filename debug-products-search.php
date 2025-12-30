<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== DEBUG PRODUCTS SEARCH ===\n\n";

// Get all products with descriptions
$allProducts = Product::all();
echo "All products with descriptions:\n";
foreach ($allProducts as $product) {
    echo "ID: {$product->id}\n";
    echo "Name: {$product->name}\n";
    echo "Description: " . ($product->description ?: 'NULL') . "\n";
    echo "---\n";
}

// Test specific search
$searchTerm = 'Espresso';
echo "\nTesting search for: '{$searchTerm}'\n";
echo "SQL Query equivalent:\n";
echo "WHERE (name LIKE '%{$searchTerm}%' OR description LIKE '%{$searchTerm}%')\n\n";

$query = Product::with('category');
$query->where(function ($q) use ($searchTerm) {
    $q->where('name', 'like', "%{$searchTerm}%")
      ->orWhere('description', 'like', "%{$searchTerm}%");
});

$results = $query->get();
echo "Results:\n";
foreach ($results as $product) {
    $nameMatch = str_contains(strtolower($product->name), strtolower($searchTerm));
    $descMatch = str_contains(strtolower($product->description ?: ''), strtolower($searchTerm));
    
    echo "- {$product->name}\n";
    echo "  Name match: " . ($nameMatch ? 'YES' : 'NO') . "\n";
    echo "  Desc match: " . ($descMatch ? 'YES' : 'NO') . "\n";
    echo "  Description: " . ($product->description ?: 'NULL') . "\n";
    echo "\n";
}