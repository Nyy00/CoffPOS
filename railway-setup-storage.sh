#!/bin/bash
# Railway Storage Setup Script
# Run this in Railway console to setup storage properly

echo "🚂 Railway Storage Setup"
echo "======================="

# 1. Create storage directories
echo "📁 Creating storage directories..."
mkdir -p storage/app/public/products
mkdir -p storage/app/public/categories  
mkdir -p storage/app/public/avatars
mkdir -p storage/app/public/receipts
mkdir -p storage/app/public/images
mkdir -p storage/logs

# 2. Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 755 public/

# 3. Create symbolic link (force)
echo "🔗 Creating storage symbolic link..."
php artisan storage:link --force

# 4. Move existing images from public/images to storage (if they exist)
echo "📦 Moving existing images to storage..."
if [ -d "public/images/products" ]; then
    echo "Moving product images..."
    cp -r public/images/products/* storage/app/public/products/ 2>/dev/null || true
fi

if [ -d "public/images/categories" ]; then
    echo "Moving category images..."
    cp -r public/images/categories/* storage/app/public/categories/ 2>/dev/null || true
fi

if [ -d "public/images/avatars" ]; then
    echo "Moving avatar images..."
    cp -r public/images/avatars/* storage/app/public/avatars/ 2>/dev/null || true
fi

# 5. Create placeholder images
echo "🖼️ Creating placeholder images..."
# Create a simple 1x1 pixel PNG as placeholder
echo "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==" | base64 -d > storage/app/public/products/placeholder-product.png
echo "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==" | base64 -d > storage/app/public/categories/placeholder-category.png
echo "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==" | base64 -d > storage/app/public/avatars/default-avatar.png

# Also create in public/images as fallback
mkdir -p public/images/products
mkdir -p public/images/categories
mkdir -p public/images/avatars

echo "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==" | base64 -d > public/images/products/placeholder-product.png
echo "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==" | base64 -d > public/images/categories/placeholder-category.png
echo "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==" | base64 -d > public/images/avatars/default-avatar.png

# 6. Clear and cache configurations
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "📦 Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Run migrations (if needed)
echo "🗄️ Running migrations..."
php artisan migrate --force

# 8. Verify setup
echo "✅ Verifying setup..."
echo "Storage directories:"
ls -la storage/app/public/
echo ""
echo "Public storage link:"
ls -la public/storage
echo ""
echo "Image files:"
ls -la storage/app/public/products/ 2>/dev/null || echo "No product images yet"
echo ""

# 9. Test image access
echo "🧪 Testing image access..."
php artisan tinker --execute="
echo 'Storage disk: ' . config('filesystems.default') . PHP_EOL;
echo 'Storage URL: ' . \Storage::url('products/test.jpg') . PHP_EOL;
echo 'Can write to storage: ' . (\Storage::disk('public')->put('test.txt', 'test') ? 'YES' : 'NO') . PHP_EOL;
\Storage::disk('public')->delete('test.txt');
"

echo ""
echo "🎉 Storage setup complete!"
echo ""
echo "Next steps:"
echo "1. Test image upload in admin panel"
echo "2. Check if images display correctly"
echo "3. If still issues, check Railway logs"
echo "4. Consider using S3 for production storage"