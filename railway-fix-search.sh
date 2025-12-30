#!/bin/bash

echo "🔧 Fixing products search functionality for PostgreSQL..."

# Run the pudding products seeder
echo "📦 Seeding pudding products..."
php artisan seed:pudding-products

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Search fix deployment complete!"
echo "🔍 You can now search for 'pudding' in the products page"