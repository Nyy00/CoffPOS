#!/bin/bash

echo "🚀 Running post-deployment tasks..."

# Wait for database to be ready
echo "⏳ Waiting for database connection..."
sleep 5

# Run migrations safely
echo "📦 Running database migrations..."
php artisan migrate --force

# Seed pudding products for search testing
echo "🍮 Seeding pudding products..."
php artisan seed:pudding-products

# Clear and cache config
echo "🧹 Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Post-deployment tasks completed!"