#!/bin/bash

echo "🚀 Running post-deploy setup..."

# Create storage link
echo "📁 Creating storage link..."
php artisan storage:link

# Restore product images from backup
echo "🖼️ Restoring product images..."
php artisan products:backup-images --restore

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo "✅ Post-deploy setup completed!"