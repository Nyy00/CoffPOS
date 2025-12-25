# 🚀 Midtrans Sandbox - Quick Start Guide

## ✅ Setup Complete!

Sistem CoffPOS Anda sudah dikonfigurasi dengan Midtrans Sandbox dan siap untuk testing!

### 🔧 Konfigurasi Aktif:
- **Environment**: Sandbox (Testing)
- **Server Key**: `SB-Mid-server-geQSl_jcQQbyaKyHzOWaAUHd`
- **Client Key**: `SB-Mid-client-b02JH07ZfUngrxWr`

## 🎯 Cara Testing (5 Menit)

### 1. Akses POS System
```
http://coffpos.test/cashier/pos
```

### 2. Lakukan Transaksi Digital
1. **Tambah produk** ke keranjang
2. **Pilih "Digital"** sebagai metode pembayaran
3. **Klik "Bayar Digital"** - popup Midtrans akan muncul
4. **Pilih "Credit Card"**
5. **Gunakan test card**:
   ```
   Card Number: 4811 1111 1111 1114
   CVV: 123
   Exp: 01/25
   ```
6. **Klik "Pay Now"**
7. ✅ **Transaksi berhasil!**

## 🧪 Test Cards Lainnya

### ✅ Success
```
4811 1111 1111 1114
```

### ❌ Failed  
```
4911 1111 1111 1113
```

### ⚠️ Challenge
```
4411 1111 1111 1118
```

## 📱 Test E-Wallet

### GoPay
- Nomor HP: `081234567890`
- PIN: `123456`

### ShopeePay
- Nomor HP: `081234567890`

## 🔍 Visual Indicators

Anda akan melihat:
- **Badge "SANDBOX"** di header POS
- **Info testing** di payment modal
- **Test card details** saat pilih digital payment

## 📊 Monitoring

### Check Transaksi
```sql
SELECT * FROM transactions WHERE payment_method = 'digital';
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Midtrans Dashboard
https://dashboard.sandbox.midtrans.com

## 🚨 Troubleshooting

### Popup tidak muncul?
- Check browser console
- Disable ad blocker
- Refresh halaman

### Error "Snap token failed"?
- Check internet connection
- Verify API keys di `.env`

## 🎉 Ready to Test!

Sistem sudah siap digunakan. Silakan test berbagai skenario pembayaran digital!

**Happy Testing! 🚀**