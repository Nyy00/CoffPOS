<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class FixProductImagePaths extends Command
{
    protected $signature = 'products:fix-image-paths';
    protected $description = 'Fix product image paths to use correct storage location';

    public function handle()
    {
        $this->info('Starting to fix product image paths...');
        
        $products = Product::whereNotNull('image')->get();
        $fixed = 0;
        $skipped = 0;
        
        foreach ($products as $product) {
            $currentPath = $product->image;
            $this->line("Checking product: {$product->name} - Current path: {$currentPath}");
            
            // Skip if already has correct format (products/filename)
            if (str_starts_with($currentPath, 'products/')) {
                $this->line("  ✓ Already correct format");
                $skipped++;
                continue;
            }
            
            // Check if file exists in public/images/products/
            $publicPath = "images/products/{$currentPath}";
            if (file_exists(public_path($publicPath))) {
                // Copy to storage and update path
                $newPath = "products/{$currentPath}";
                
                if (!Storage::disk('public')->exists($newPath)) {
                    // Copy file from public to storage
                    $fileContent = file_get_contents(public_path($publicPath));
                    Storage::disk('public')->put($newPath, $fileContent);
                    $this->line("  ✓ Copied to storage: {$newPath}");
                } else {
                    $this->line("  ✓ Already exists in storage: {$newPath}");
                }
                
                // Update database
                $product->update(['image' => $newPath]);
                $this->line("  ✓ Updated database path");
                $fixed++;
            } else {
                $this->warn("  ✗ File not found: {$publicPath}");
            }
        }
        
        $this->info("Completed! Fixed: {$fixed}, Skipped: {$skipped}");
        return 0;
    }
}