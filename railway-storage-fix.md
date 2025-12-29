# Solusi Upload Gambar Receipt di Railway Production

## 1. Periksa Konfigurasi Environment

Pastikan di Railway environment variables:
```
FILESYSTEM_DISK=public
APP_URL=https://your-domain.railway.app
```

## 2. Tambahkan Command untuk Storage Link

Buat file `railway-deploy.sh`:
```bash
#!/bin/bash
php artisan storage:link --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 3. Update Dockerfile (jika menggunakan Docker)

Tambahkan di Dockerfile:
```dockerfile
RUN php artisan storage:link --force
RUN chmod -R 775 storage/
RUN chmod -R 775 bootstrap/cache/
```

## 4. Periksa Directory Permissions

Jalankan command ini di Railway:
```bash
ls -la storage/
ls -la storage/app/
ls -la storage/app/public/
ls -la public/storage
```

## 5. Test Upload dengan Error Logging

Tambahkan logging di SimpleImageService untuk debug:
```php
Log::info('Upload attempt', [
    'file_size' => $file->getSize(),
    'file_type' => $file->getMimeType(),
    'storage_path' => storage_path('app/public'),
    'disk_space' => disk_free_space(storage_path('app/public'))
]);
```

## 6. Alternative: Gunakan S3 untuk Production

Update config/filesystems.php:
```php
'default' => env('FILESYSTEM_DISK', 'local'),

// Tambahkan kondisi untuk production
'default' => env('APP_ENV') === 'production' ? 's3' : env('FILESYSTEM_DISK', 'local'),
```

Set environment variables di Railway:
```
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=your-bucket-name
FILESYSTEM_DISK=s3
```