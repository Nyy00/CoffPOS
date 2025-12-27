<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Coffee',
                'description' => 'Berbagai macam kopi pilihan',
                'image' => 'categories/coffee.jpg',
            ],
            [
                'name' => 'Non Coffee',
                'description' => 'Minuman non kopi',
                'image' => 'categories/non-coffee.jpg',
            ],
            [
                'name' => 'Food',
                'description' => 'Makanan dan snack',
                'image' => 'categories/food.jpg',
            ],
            [
                'name' => 'Dessert',
                'description' => 'Dessert dan kue',
                'image' => 'categories/dessert.jpg',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
