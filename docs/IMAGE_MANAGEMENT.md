# Image Management Guide

## Masalah Gambar Hilang Setelah Deploy

Setelah redeploy di Railway, gambar yang diupload melalui admin akan hilang karena folder `storage/app/public` tidak di-commit ke git. Namun gambar dari seeder tetap ada karena disimpan di `public/images/products`.

## Solusi yang Diterapkan

### 1. ImageHelper dengan Fallback Otomatis
- Cek storage terlebih dahulu (`/storage/products/`)
- Fallback ke public assets (`/images/products/`)
- Fallback ke placeholder jika tidak ada

### 2. Backup & Restore System
```bash
# Backup gambar dari storage ke public/images/products
php artisan products:backup-images

# Restore gambar dari public/images/products ke storage
php artisan products:backup-images --restore
```

### 3. Auto-Restore pada Deploy
- Railway akan otomatis menjalankan restore setelah deploy
- Konfigurasi ada di `railway.json` dan `app/Console/Commands/SetupDatabase.php`

## Workflow untuk Developer

### Sebelum Deploy Besar
```bash
# Backup semua gambar yang diupload
php artisan products:backup-images
```

### Setelah Deploy (Otomatis)
- System akan otomatis restore gambar dari backup
- Jika ada masalah, jalankan manual: `php artisan products:backup-images --restore`

### Upload Gambar Baru
1. Upload melalui admin panel (akan tersimpan di storage)
2. Jalankan backup sebelum deploy berikutnya:
   ```bash
   php artisan products:backup-images
   ```

## File Locations

### Storage (Temporary - hilang saat deploy)
- `storage/app/public/products/` - Gambar upload baru
- Accessible via `/storage/products/filename.jpg`

### Public Assets (Permanent - di-commit ke git)
- `public/images/products/` - Gambar seeder + backup
- Accessible via `/images/products/filename.jpg`

## Troubleshooting

### Gambar Tidak Muncul
1. Cek apakah storage link ada: `php artisan storage:link`
2. Restore dari backup: `php artisan products:backup-images --restore`
3. Cek ImageHelper debug: `php debug-images.php`

### Setelah Upload Gambar Baru
1. Test di local/staging
2. Backup sebelum deploy: `php artisan products:backup-images`
3. Deploy
4. Verify gambar muncul (auto-restore akan jalan)

## Commands Reference

```bash
# Setup database (include image restore)
php artisan setup:database

# Backup images
php artisan products:backup-images

# Restore images
php artisan products:backup-images --restore

# Fix image paths (jika ada masalah path)
php artisan products:fix-image-paths

# Debug images
php debug-images.php
```