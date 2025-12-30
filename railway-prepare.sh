#!/bin/bash

echo "🚀 Preparing Laravel for Railway deployment..."

# Create necessary directories
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "✅ Directories created and permissions set"

# Clear any existing cache
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "✅ Caches cleared"

# Generate app key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
    echo "✅ App key generated"
fi

echo "🎉 Railway preparation complete!"