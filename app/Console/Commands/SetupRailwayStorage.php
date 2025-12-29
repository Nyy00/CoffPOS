<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SetupRailwayStorage extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'railway:setup-storage {--force : Force setup even if directories exist}';

    /**
     * The console command description.
     */
    protected $description = 'Setup storage directories and move images for Railway deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚂 Setting up Railway storage...');

        // Create storage directories
        $this->createStorageDirectories();

        // Create symbolic link
        $this->createStorageLink();

        // Move existing images
        $this->moveExistingImages();

        // Create placeholder images
        $this->createPlaceholderImages();

        // Test storage
        $this->testStorage();

        $this->info('✅ Railway storage setup complete!');
        
        return Command::SUCCESS;
    }

    private function createStorageDirectories()
    {
        $this->info('📁 Creating storage directories...');

        $directories = [
            'products',
            'categories',
            'avatars',
            'receipts',
            'images'
        ];

        foreach ($directories as $dir) {
            $path = storage_path("app/public/{$dir}");
            if (!File::exists($path)) {
                File::makeDirectory($path, 0775, true);
                $this->line("Created: {$dir}");
            } else {
                $this->line("Exists: {$dir}");
            }
        }
    }

    private function createStorageLink()
    {
        $this->info('🔗 Creating storage symbolic link...');

        try {
            $this->call('storage:link', ['--force' => true]);
            $this->line('Storage link created successfully');
        } catch (\Exception $e) {
            $this->error('Failed to create storage link: ' . $e->getMessage());
        }
    }

    private function moveExistingImages()
    {
        $this->info('📦 Moving existing images to storage...');

        $imageDirs = [
            'products' => 'products',
            'categories' => 'categories',
            'avatars' => 'avatars'
        ];

        foreach ($imageDirs as $publicDir => $storageDir) {
            $publicPath = public_path("images/{$publicDir}");
            $storagePath = storage_path("app/public/{$storageDir}");

            if (File::exists($publicPath)) {
                $files = File::files($publicPath);
                $moved = 0;

                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    $destinationPath = "{$storagePath}/{$filename}";

                    if (!File::exists($destinationPath) || $this->option('force')) {
                        File::copy($file->getPathname(), $destinationPath);
                        $moved++;
                    }
                }

                $this->line("Moved {$moved} files from {$publicDir}");
            } else {
                $this->line("No {$publicDir} directory found in public/images");
            }
        }
    }

    private function createPlaceholderImages()
    {
        $this->info('🖼️ Creating placeholder images...');

        // Simple 1x1 pixel transparent PNG
        $placeholderData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

        $placeholders = [
            'products/placeholder-product.png',
            'categories/placeholder-category.png',
            'avatars/default-avatar.png',
            'placeholder.png'
        ];

        foreach ($placeholders as $placeholder) {
            $storagePath = storage_path("app/public/{$placeholder}");
            $publicPath = public_path("images/{$placeholder}");

            // Create in storage
            if (!File::exists($storagePath) || $this->option('force')) {
                File::put($storagePath, $placeholderData);
                $this->line("Created storage placeholder: {$placeholder}");
            }

            // Create in public as fallback
            File::ensureDirectoryExists(dirname($publicPath));
            if (!File::exists($publicPath) || $this->option('force')) {
                File::put($publicPath, $placeholderData);
                $this->line("Created public placeholder: {$placeholder}");
            }
        }
    }

    private function testStorage()
    {
        $this->info('🧪 Testing storage...');

        try {
            // Test write
            $testFile = 'test-' . time() . '.txt';
            $testContent = 'Railway storage test: ' . now()->toDateTimeString();

            Storage::disk('public')->put($testFile, $testContent);

            if (Storage::disk('public')->exists($testFile)) {
                $this->line('✅ Can write to storage');
                
                // Test URL generation
                $url = Storage::url($testFile);
                $this->line("✅ Storage URL: {$url}");

                // Clean up
                Storage::disk('public')->delete($testFile);
                $this->line('✅ Can delete from storage');
            } else {
                $this->error('❌ Cannot write to storage');
            }

        } catch (\Exception $e) {
            $this->error('❌ Storage test failed: ' . $e->getMessage());
        }

        // Test image helper
        try {
            if (class_exists('\App\Helpers\ImageHelper')) {
                $testUrl = \App\Helpers\ImageHelper::getProductImageUrl('test.jpg', 'Test Product');
                $this->line("✅ Image helper works: {$testUrl}");
            }
        } catch (\Exception $e) {
            $this->error('❌ Image helper test failed: ' . $e->getMessage());
        }

        // Show storage info
        $this->table(['Setting', 'Value'], [
            ['Default Disk', config('filesystems.default')],
            ['Storage Path', storage_path('app/public')],
            ['Public Path', public_path('storage')],
            ['Storage Link Exists', is_link(public_path('storage')) ? 'Yes' : 'No'],
            ['App URL', config('app.url')],
        ]);
    }
}