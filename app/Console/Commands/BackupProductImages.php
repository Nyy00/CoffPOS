<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupProductImages extends Command
{
    protected $signature = 'products:backup-images {--restore : Restore images from backup}';
    protected $description = 'Backup or restore product images to/from public/images/products';

    public function handle()
    {
        if ($this->option('restore')) {
            return $this->restoreImages();
        }
        
        return $this->backupImages();
    }
    
    private function backupImages()
    {
        $this->info('Backing up product images from storage to public/images/products...');
        
        $backupPath = public_path('images/products');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        $products = Product::whereNotNull('image')->get();
        $backed = 0;
        
        foreach ($products as $product) {
            $imagePath = $product->image;
            $cleanPath = str_replace('products/', '', $imagePath);
            
            // Check if exists in storage
            if (Storage::disk('public')->exists($imagePath)) {
                $sourceFile = Storage::disk('public')->path($imagePath);
                $backupFile = $backupPath . '/' . $cleanPath;
                
                if (!file_exists($backupFile)) {
                    File::copy($sourceFile, $backupFile);
                    $this->line("✓ Backed up: {$cleanPath}");
                    $backed++;
                } else {
                    $this->line("- Already exists: {$cleanPath}");
                }
            }
        }
        
        $this->info("Backup completed! Backed up {$backed} images to public/images/products");
        return 0;
    }
    
    private function restoreImages()
    {
        $this->info('Restoring product images from public/images/products to storage...');
        
        $backupPath = public_path('images/products');
        if (!File::exists($backupPath)) {
            $this->error('Backup directory not found: ' . $backupPath);
            return 1;
        }
        
        $products = Product::whereNotNull('image')->get();
        $restored = 0;
        
        foreach ($products as $product) {
            $imagePath = $product->image;
            $cleanPath = str_replace('products/', '', $imagePath);
            $backupFile = $backupPath . '/' . $cleanPath;
            
            // Check if backup exists and storage doesn't
            if (file_exists($backupFile) && !Storage::disk('public')->exists($imagePath)) {
                $fileContent = File::get($backupFile);
                Storage::disk('public')->put($imagePath, $fileContent);
                $this->line("✓ Restored: {$cleanPath}");
                $restored++;
            } elseif (Storage::disk('public')->exists($imagePath)) {
                $this->line("- Already in storage: {$cleanPath}");
            } else {
                $this->warn("✗ Backup not found: {$cleanPath}");
            }
        }
        
        $this->info("Restore completed! Restored {$restored} images to storage");
        return 0;
    }
}