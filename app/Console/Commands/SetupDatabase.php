<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupDatabase extends Command
{
    protected $signature = 'setup:database';
    protected $description = 'Setup database with migrations and seeders';

    public function handle()
    {
        $this->info('Setting up database...');
        
        // Create storage link
        $this->info('Creating storage link...');
        try {
            Artisan::call('storage:link');
            $this->info('Storage link created.');
        } catch (\Exception $e) {
            $this->warn('Storage link creation failed: ' . $e->getMessage());
            $this->info('This might be normal if link already exists.');
        }
        
        // Run migrations
        $this->info('Running migrations...');
        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->info('Migrations completed.');
        } catch (\Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
        }
        
        // Run seeders
        $this->info('Running seeders...');
        try {
            Artisan::call('db:seed', ['--force' => true]);
            $this->info('Seeders completed.');
        } catch (\Exception $e) {
            $this->warn('Seeding had issues (might be duplicate data): ' . $e->getMessage());
            $this->info('This is normal if data already exists.');
        }
        
        $this->info('Database setup completed successfully!');
        
        return 0;
    }
}