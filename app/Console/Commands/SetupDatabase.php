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
        
        // Run migrations
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info('Migrations completed.');
        
        // Run seeders
        $this->info('Running seeders...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->info('Seeders completed.');
        
        $this->info('Database setup completed successfully!');
        
        return 0;
    }
}