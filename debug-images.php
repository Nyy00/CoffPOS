<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

echo "=== DEBUG PRODUCT IMAGES ===\n\n";

$products = Product::whereNotNull('image')->take(3)->get();

foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "DB Path: {$product->image}\n";
    
    // Check if file exists in storage
    $storageExists = Storage::disk('public')->exists($product->image);
    echo "Storage exists: " . ($storageExists ? 'YES' : 'NO') . "\n";
    
    if ($storageExists) {
        $storageUrl = Storage::url($product->image);
        echo "Storage URL: {$storageUrl}\n";
    }
    
    // Check ImageHelper result
    $imageUrl = ImageHelper::getProductImageUrl($product->image, $product->name);
    echo "ImageHelper URL: {$imageUrl}\n";
    
    // Check if file exists in public/images
    $cleanPath = str_replace('products/', '', $product->image);
    $publicPath = "images/products/{$cleanPath}";
    $publicExists = file_exists(public_path($publicPath));
    echo "Public exists: " . ($publicExists ? 'YES' : 'NO') . " at {$publicPath}\n";
    
    echo "---\n";
}