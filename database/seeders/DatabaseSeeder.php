<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "Starting database seeding...\n";
        
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            // POSDataSeeder::class, // Uncomment if you want to run POS test data
        ]);
        
        echo "Database seeding completed!\n";
    }
}
