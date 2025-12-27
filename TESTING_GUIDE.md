# 🧪 Testing Guide - Midtrans Integration

## ✅ Konfigurasi Anda

**API Keys yang aktif:**
- Server Key: `SB-Mid-server-geQSl_jcQQbyaKyHzOWaAUHd`
- Client Key: `SB-Mid-client-b02JH07ZfUngrxWr`
- Environment: **Sandbox** (Testing Mode)

## 🚀 Langkah Testing

### 1. Akses POS System
```
http://coffpos.test/cashier/pos
```

### 2. Lakukan Transaksi Digital
1. **Login** sebagai cashier
2. **Tambah produk** ke keranjang (misal: Espresso)
3. **Pilih "Digital"** sebagai metode pembayaran
4. **Klik "Bayar Digital"**
5. **Popup Midtrans** akan muncul

### 3. Test dengan Credit Card
Gunakan test card berikut:

**✅ Success Card:**
```
Card Number: 4811 1111 1111 1114
CVV: 123
Exp Month: 01
Exp Year: 2025
```

**❌ Failed Card:**
```
Card Number: 4911 1111 1111 1113
CVV: 123
Exp Month: 01
Exp Year: 2025
```

### 4. Test E-Wallet

**GoPay:**
- Nomor HP: `081234567890`
- PIN: `123456`

**ShopeePay:**
- Nomor HP: `081234567890`

## 🔍 Apa yang Harus Terjadi

### Skenario Success:
1. Popup Midtrans muncul ✅
2. Pilih Credit Card ✅
3. Masukkan test card success ✅
4. Payment berhasil ✅
5. Receipt muncul ✅
6. Keranjang kosong ✅
7. Transaksi tersimpan di database ✅

### Skenario Failed:
1. Popup Midtrans muncul ✅
2. Pilih Credit Card ✅
3. Masukkan test card failed ✅
4. Payment gagal ❌
5. Error message muncul ✅
6. Keranjang tetap ada ✅

## 🔧 Troubleshooting

### Popup tidak muncul?
**Check:**
- Browser console untuk JavaScript errors
- Network tab untuk failed requests
- Ad blocker atau popup blocker

**Solusi:**
```javascript
// Buka browser console dan cek:
console.log('Snap loaded:', typeof window.snap);
```

### Error "Invalid client key"?
**Check:**
- Client key di `.env` file
- Config cache: `php artisan config:clear`

### Error "Merchant not found"?
**Check:**
- Server key di `.env` file
- Pastikan menggunakan sandbox keys (dimulai dengan `SB-Mid-`)

## 📊 Monitoring

### 1. Check Database
```sql
SELECT 
    id, 
    transaction_code, 
    payment_method, 
    total_amount, 
    status,
    midtrans_transaction_id,
    created_at 
FROM transactions 
WHERE payment_method = 'digital' 
ORDER BY created_at DESC;
```

### 2. Check Logs
```bash
tail -f storage/logs/laravel.log | grep -i midtrans
```

### 3. Midtrans Dashboard
Login ke: https://dashboard.sandbox.midtrans.com
- Username/Email: [sesuai akun Midtrans Anda]
- Monitor transaksi real-time

## 🎯 Test Checklist

- [ ] Popup Midtrans muncul
- [ ] Credit Card Success (4811...)
- [ ] Credit Card Failed (4911...)
- [ ] GoPay simulation
- [ ] ShopeePay simulation
- [ ] Transaction saved to database
- [ ] Receipt generation works
- [ ] Cart cleared after success
- [ ] Error handling for failures
- [ ] Sandbox badge visible in header
- [ ] Test card info shown in modal

## 📱 Next Steps

Setelah testing berhasil:
1. **Dokumentasikan** hasil testing
2. **Test berbagai skenario** pembayaran
3. **Monitor performance** dan error rates
4. **Siapkan untuk production** (jika diperlukan)

## 🆘 Butuh Bantuan?

Jika ada masalah:
1. Check dokumentasi Midtrans: https://docs.midtrans.com
2. Check Laravel logs
3. Test dengan browser berbeda
4. Pastikan internet connection stabil

**Happy Testing! 🎉**