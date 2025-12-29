# 🚂 Railway Production Troubleshooting Guide

## Masalah: Upload Gambar Tidak Bisa di Semua Fitur

### Kemungkinan Penyebab:
1. **Database Connection Issues** - Railway PostgreSQL tidak terkoneksi dengan benar
2. **Storage Configuration** - Filesystem tidak dikonfigurasi dengan benar
3. **Missing Storage Link** - Symbolic link storage tidak ada
4. **Permission Issues** - Directory tidak memiliki permission yang tepat
5. **Environment Variables** - Konfigurasi environment tidak sesuai

## 🔍 Langkah Debugging

### 1. Upload File Debug ke Railway
```bash
# Upload railway-debug-comprehensive.php ke folder public/
# Akses: https://your-domain.railway.app/railway-debug-comprehensive.php
```

### 2. Periksa Railway Console Logs
```bash
# Di Railway dashboard, buka Deployments > View Logs
# Cari error messages saat upload gambar
```

### 3. Jalankan Fix Commands
```bash
# Di Railway console, jalankan:
chmod +x railway-fix-commands.sh
./railway-fix-commands.sh
```

## ⚙️ Konfigurasi Railway Environment Variables

### Database (Otomatis dari Railway PostgreSQL)
```
PGHOST=containers-us-west-xxx.railway.app
PGPORT=5432
PGDATABASE=railway
PGUSER=postgres
PGPASSWORD=xxx
```

### Application Settings
```
APP_NAME=CoffPOS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.railway.app
APP_KEY=base64:your-app-key-here
```

### Storage & Cache
```
FILESYSTEM_DISK=public
CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### Optional: AWS S3 (Recommended)
```
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=your-bucket
FILESYSTEM_DISK=s3
```

## 🛠️ Solusi Berdasarkan Masalah

### Jika Database Connection Gagal:
1. **Periksa PostgreSQL Service**
   - Pastikan PostgreSQL addon aktif di Railway
   - Restart PostgreSQL service jika perlu

2. **Periksa Environment Variables**
   - Pastikan `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD` ter-set
   - Cek di Railway dashboard > Variables

3. **Update Database Config**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

### Jika Storage Issues:
1. **Buat Storage Directories**
   ```bash
   mkdir -p storage/app/public/{receipts,images,products,categories,avatars}
   chmod -R 775 storage/
   ```

2. **Create Storage Link**
   ```bash
   php artisan storage:link --force
   ```

3. **Verify Permissions**
   ```bash
   ls -la storage/app/public/
   ls -la public/storage
   ```

### Jika Masih Bermasalah - Gunakan S3:
1. **Setup AWS S3 Bucket**
   - Buat bucket di AWS S3
   - Set public read access untuk images
   - Dapatkan Access Key & Secret

2. **Update Environment Variables**
   ```
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=your-key
   AWS_SECRET_ACCESS_KEY=your-secret
   AWS_DEFAULT_REGION=ap-southeast-1
   AWS_BUCKET=your-bucket-name
   ```

3. **Update Code (Optional)**
   ```php
   // config/filesystems.php
   'default' => env('APP_ENV') === 'production' ? 's3' : env('FILESYSTEM_DISK', 'local'),
   ```

## 🚀 Deployment Script untuk Railway

Buat file `railway-deploy.sh`:
```bash
#!/bin/bash
echo "🚂 Railway Deployment Script"

# Install dependencies
composer install --no-dev --optimize-autoloader

# Create storage directories
mkdir -p storage/app/public/{receipts,images,products,categories,avatars}
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Create storage link
php artisan storage:link --force

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

echo "✅ Deployment complete"
```

## 📋 Checklist Troubleshooting

- [ ] Database connection test passed
- [ ] Storage directories exist and writable
- [ ] Symbolic link `public/storage` exists
- [ ] Environment variables properly set
- [ ] Migrations ran successfully
- [ ] Cache cleared and rebuilt
- [ ] Upload test with debug logging
- [ ] Check Railway logs for errors

## 🆘 Jika Semua Gagal

1. **Restart Railway Service**
   - Di Railway dashboard, restart deployment

2. **Redeploy dari Scratch**
   - Trigger new deployment
   - Pastikan semua environment variables ter-set

3. **Contact Railway Support**
   - Jika masalah persisten, hubungi Railway support
   - Sertakan logs dan error messages

## 📞 Debug Commands

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Test storage
php artisan tinker
>>> Storage::disk('public')->put('test.txt', 'test');
>>> Storage::disk('public')->exists('test.txt');

# Check environment
php artisan about
php artisan env

# View logs
tail -f storage/logs/laravel.log
```

---

**⚠️ Ingat: Hapus file debug setelah troubleshooting selesai!**