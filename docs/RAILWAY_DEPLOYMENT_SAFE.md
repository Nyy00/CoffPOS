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

## Troubleshooting

- ✅ Pastikan keys sudah diset di Railway Variables
- ✅ Pastikan format key benar (production: `Mid-`, sandbox: `SB-Mid-`)
- ✅ Pastikan `MIDTRANS_IS_PRODUCTION` sesuai dengan jenis key
- ✅ Check Railway logs untuk error details

## Security

- Jangan commit keys ke repository
- Gunakan environment variables di Railway
- Monitor transaksi secara berkala