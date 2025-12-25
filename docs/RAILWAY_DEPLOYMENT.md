# Railway Deployment Guide - Midtrans Configuration

## Masalah Midtrans di Production

Jika Anda mengalami error seperti ini di production:
```
Midtrans is not properly configured. Please set valid MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in your .env file.
```

## Solusi Langkah-demi-Langkah

### 1. Dapatkan API Keys dari Midtrans

#### Production Keys:
1. Login ke [dashboard.midtrans.com](https://dashboard.midtrans.com)
2. Lengkapi verifikasi bisnis
3. Dapatkan Server Key dan Client Key (dimulai dengan `Mid-`)

#### Sandbox Keys (untuk testing):
1. Login ke [dashboard.sandbox.midtrans.com](https://dashboard.sandbox.midtrans.com)
2. Dapatkan Server Key dan Client Key (dimulai dengan `SB-Mid-`)

### 2. Konfigurasi di Railway

1. Buka Railway Dashboard → Project → Variables
2. Tambahkan environment variables:

```
MIDTRANS_SERVER_KEY=[paste-your-server-key-here]
MIDTRANS_CLIENT_KEY=[paste-your-client-key-here]
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Catatan**: Ganti `[paste-your-server-key-here]` dengan key asli dari dashboard Midtrans.

### 3. Redeploy

Setelah menambahkan environment variables, redeploy aplikasi di Railway.

### 4. Verifikasi

Buat endpoint untuk mengecek konfigurasi:

```php
Route::get('/health/midtrans', function () {
    return response()->json([
        'configured' => !empty(config('midtrans.server_key')),
        'production' => config('midtrans.is_production'),
    ]);
});
```

## Testing

### Test Cards (Sandbox):
- Success: `4811 1111 1111 1114`
- Failure: `4911 1111 1111 1113`
- CVV: `123`, Expiry: `12/25`

## Troubleshooting Error 401 "Access denied due to unauthorized transaction"

### Kemungkinan Penyebab:

1. **Server Key Salah atau Tidak Valid**
   - Key sudah expired atau direvoke
   - Key untuk environment yang salah (production vs sandbox)
   - Key mengandung spasi atau karakter tambahan

2. **Environment Mismatch**
   - Menggunakan sandbox key tapi `MIDTRANS_IS_PRODUCTION=true`
   - Menggunakan production key tapi `MIDTRANS_IS_PRODUCTION=false`

3. **Akun Midtrans Belum Diverifikasi**
   - Untuk production, akun harus diverifikasi dan disetujui
   - Untuk sandbox, pastikan akun aktif

### Langkah Debugging:

1. **Cek Konfigurasi di Railway**
   ```
   Buka Railway → Project → Variables
   Pastikan semua environment variables sudah diset dengan benar
   ```

2. **Verifikasi Keys**
   - Production keys dimulai dengan: `Mid-server-` dan `Mid-client-`
   - Sandbox keys dimulai dengan: `SB-Mid-server-` dan `SB-Mid-client-`

3. **Test Endpoint Debug**
   Akses: `https://your-domain.com/health/midtrans`
   
   Response yang benar:
   ```json
   {
     "midtrans_configured": true,
     "is_production": true,
     "environment": "production"
   }
   ```

### Solusi Berdasarkan Skenario:

#### Skenario 1: Testing di Production dengan Sandbox
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-your-sandbox-key
MIDTRANS_CLIENT_KEY=SB-Mid-client-your-sandbox-key
MIDTRANS_IS_PRODUCTION=false
```

#### Skenario 2: Production Sesungguhnya
```env
MIDTRANS_SERVER_KEY=Mid-server-your-production-key
MIDTRANS_CLIENT_KEY=Mid-client-your-production-key
MIDTRANS_IS_PRODUCTION=true
```

### Validasi Keys:

1. **Login ke Dashboard Midtrans**
   - Sandbox: https://dashboard.sandbox.midtrans.com
   - Production: https://dashboard.midtrans.com

2. **Cek Status Akun**
   - Pastikan akun aktif dan tidak suspended
   - Untuk production: pastikan sudah diverifikasi

3. **Generate Keys Baru**
   - Jika keys lama bermasalah, generate keys baru
   - Update di Railway Variables
   - Redeploy aplikasi

## Security

- Jangan commit keys ke repository
- Gunakan environment variables di Railway
- Monitor transaksi secara berkala