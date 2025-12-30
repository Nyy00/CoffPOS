<?php
/**
 * Production Search Fix Script
 * Jalankan di Railway Console: php fix-search-production.php
 */

echo "🔧 Fixing PostgreSQL search compatibility...\n";

// 1. Update ProductController search methods
$controllerPath = __DIR__ . '/app/Http/Controllers/Admin/ProductController.php';
$controllerContent = file_get_contents($controllerPath);

// Replace LIKE with database-aware search
$patterns = [
    // Pattern 1: Basic search in index method
    '/\$q->where\(\'name\', \'like\', "%\{\$search\}%"\)\s*->orWhere\(\'description\', \'like\', "%\{\$search\}%"\);/' => 
    'if (config(\'database.default\') === \'pgsql\') {
                    $q->where(\'name\', \'ILIKE\', "%{$search}%")
                      ->orWhere(\'description\', \'ILIKE\', "%{$search}%");
                } else {
                    $q->where(\'name\', \'like\', "%{$search}%")
                      ->orWhere(\'description\', \'like\', "%{$search}%");
                }',
    
    // Pattern 2: Search with category
    '/\$q->where\(\'name\', \'like\', "%\{\$search\}%"\)\s*->orWhere\(\'description\', \'like\', "%\{\$search\}%"\)\s*->orWhereHas\(\'category\', function \(\$q\) use \(\$search\) \{\s*\$q->where\(\'name\', \'like\', "%\{\$search\}%"\);\s*\}\);/' =>
    'if (config(\'database.default\') === \'pgsql\') {
                    $q->where(\'name\', \'ILIKE\', "%{$search}%")
                      ->orWhere(\'description\', \'ILIKE\', "%{$search}%")
                      ->orWhereHas(\'category\', function ($q) use ($search) {
                          $q->where(\'name\', \'ILIKE\', "%{$search}%");
                      });
                } else {
                    $q->where(\'name\', \'like\', "%{$search}%")
                      ->orWhere(\'description\', \'like\', "%{$search}%")
                      ->orWhereHas(\'category\', function ($q) use ($search) {
                          $q->where(\'name\', \'like\', "%{$search}%");
                      });
                }'
];

foreach ($patterns as $pattern => $replacement) {
    $controllerContent = preg_replace($pattern, $replacement, $controllerContent);
}

file_put_contents($controllerPath, $controllerContent);
echo "✅ ProductController updated\n";

// 2. Seed pudding products
echo "📦 Seeding pudding products...\n";
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create pudding products
$dessertCategory = \App\Models\Category::where(function($q) {
    if (config('database.default') === 'pgsql') {
        $q->where('name', 'ILIKE', '%dessert%');
    } else {
        $q->where('name', 'like', '%dessert%');
    }
})->first();

if (!$dessertCategory) {
    $dessertCategory = \App\Models\Category::create([
        'name' => 'Dessert',
        'description' => 'Sweet desserts and treats'
    ]);
    echo "✅ Created Dessert category\n";
}

$puddingProducts = [
    [
        'category_id' => $dessertCategory->id,
        'code' => 'PROD013',
        'name' => 'Chocolate Pudding',
        'description' => 'Pudding cokelat lembut',
        'price' => 20000,
        'cost' => 8000,
        'stock' => 25,
        'image' => 'products/pudding.jpg',
        'is_available' => true
    ],
    [
        'category_id' => $dessertCategory->id,
        'code' => 'PROD014',
        'name' => 'Vanilla Pudding',
        'description' => 'Pudding vanilla klasik',
        'price' => 18000,
        'cost' => 7000,
        'stock' => 25,
        'image' => 'products/vanilla-pudding.jpg',
        'is_available' => true
    ]
];

foreach ($puddingProducts as $product) {
    $existing = \App\Models\Product::where('code', $product['code'])->first();
    if (!$existing) {
        \App\Models\Product::create($product);
        echo "✅ Created: {$product['name']}\n";
    } else {
        echo "ℹ️  Already exists: {$product['name']}\n";
    }
}

// 3. Test search
echo "🔍 Testing search...\n";
$searchResults = \App\Models\Product::where(function($q) {
    if (config('database.default') === 'pgsql') {
        $q->where('name', 'ILIKE', '%pudding%');
    } else {
        $q->where('name', 'like', '%pudding%');
    }
})->count();

echo "✅ Found {$searchResults} pudding products\n";

// 4. Clear caches
echo "🧹 Clearing caches...\n";
\Artisan::call('config:clear');
\Artisan::call('cache:clear');
\Artisan::call('route:clear');
\Artisan::call('view:clear');

echo "🎉 Search fix completed! Database: " . config('database.default') . "\n";
echo "🔍 Try searching for 'pudding' in the products page\n";
?>