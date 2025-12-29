#!/bin/bash
# Railway Production Fix Commands
# Run these commands in Railway console or add to deployment script

echo "🚂 Railway Production Fix Script"
echo "================================"

# 1. Create storage directories
echo "📁 Creating storage directories..."
mkdir -p storage/app/public/receipts
mkdir -p storage/app/public/images
mkdir -p storage/app/public/products
mkdir -p storage/app/public/categories
mkdir -p storage/app/public/avatars
mkdir -p storage/logs

# 2. Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 755 public/

# 3. Create symbolic link (force)
echo "🔗 Creating storage symbolic link..."
php artisan storage:link --force

# 4. Clear and cache configurations
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "📦 Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Run migrations (if needed)
echo "🗄️ Running migrations..."
php artisan migrate --force

# 6. Verify setup
echo "✅ Verifying setup..."
ls -la storage/app/public/
ls -la public/storage
php artisan about

echo "🎉 Setup complete!"
echo ""
echo "If upload still fails, check:"
echo "1. Environment variables (FILESYSTEM_DISK, APP_URL)"
echo "2. Database connection"
echo "3. Consider using S3 for file storage"