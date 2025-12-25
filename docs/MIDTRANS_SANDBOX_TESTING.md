# Panduan Testing Midtrans Sandbox - CoffPOS

## 🔧 Konfigurasi Sandbox

Sistem sudah dikonfigurasi dengan Midtrans Sandbox:
- **Environment**: Sandbox (Development)

## 🧪 Cara Testing

### 1. Akses POS System
1. Login ke sistem sebagai cashier
2. Buka halaman POS: `http://coffpos.test/cashier/pos`
3. Tambahkan produk ke keranjang
4. Pilih metode pembayaran "Digital"
5. Klik "Bayar Digital"

### 2. Test Cards untuk Credit Card

**✅ Success Transaction:**
```
Card Number: 4811 1111 1111 1114
CVV: 123
Exp Month: 01
Exp Year: 2025
```

**❌ Failed Transaction:**
```
Card Number: 4911 1111 1111 1113
CVV: 123
Exp Month: 01
Exp Year: 2025
```

**⚠️ Challenge by FDS:**
```
Card Number: 4411 1111 1111 1118
CVV: 123
Exp Month: 01
Exp Year: 2025
```

### 3. Test E-Wallet

**GoPay:**
- Nomor HP: `081234567890`
- PIN: `123456`

**ShopeePay:**
- Nomor HP: `081234567890`
- Akan redirect ke halaman simulasi ShopeePay

**DANA:**
- Nomor HP: `081234567890`
- Akan redirect ke halaman simulasi DANA

### 4. Test Bank Transfer (Virtual Account)

**BCA Virtual Account:**
- Akan generate nomor VA otomatis
- Gunakan simulator pembayaran di Midtrans Dashboard

**Mandiri Virtual Account:**
- Akan generate nomor VA otomatis
- Gunakan simulator pembayaran di Midtrans Dashboard

## 📱 Flow Testing

### Scenario 1: Successful Payment
1. Tambah produk ke keranjang (misal: Espresso Rp 25,000)
2. Pilih "Digital" sebagai metode pembayaran
3. Klik "Bayar Digital"
4. Popup Midtrans akan muncul
5. Pilih "Credit Card"
6. Masukkan test card yang success: `4811 1111 1111 1114`
7. Isi CVV: `123`, Exp: `01/25`
8. Klik "Pay Now"
9. Transaksi berhasil, receipt akan muncul

### Scenario 2: Failed Payment
1. Ulangi langkah 1-6 dari scenario 1
2. Gunakan test card yang failed: `4911 1111 1111 1113`
3. Transaksi akan gagal
4. User akan mendapat notifikasi error

### Scenario 3: E-Wallet Payment
1. Tambah produk ke keranjang
2. Pilih "Digital" sebagai metode pembayaran
3. Klik "Bayar Digital"
4. Pilih "GoPay" atau "ShopeePay"
5. Masukkan nomor HP: `081234567890`
6. Ikuti simulasi pembayaran
7. Transaksi berhasil

## 🔍 Monitoring & Debugging

### 1. Check Logs
```bash
tail -f storage/logs/laravel.log
```

### 2. Midtrans Dashboard
- Login ke: https://dashboard.sandbox.midtrans.com
- Monitor transaksi real-time
- Check payment status

### 3. Database Check
```sql
SELECT * FROM transactions WHERE payment_method = 'digital' ORDER BY created_at DESC;
```

## 🚨 Troubleshooting

### Error: "Snap token creation failed"
**Solusi:**
- Check internet connection
- Verify API keys di `.env`
- Check logs untuk detail error

### Error: "Payment popup tidak muncul"
**Solusi:**
- Check browser console untuk JavaScript errors
- Pastikan Midtrans script loaded
- Check ad blocker atau popup blocker

### Error: "Transaction not found"
**Solusi:**
- Check webhook notification
- Verify order_id matching
- Check database transaction records

### Webhook tidak diterima
**Solusi:**
- Pastikan aplikasi accessible dari internet (gunakan ngrok untuk local testing)
- Check webhook URL di Midtrans Dashboard
- Verify CSRF token handling

## 🌐 Setup Webhook untuk Local Testing

Jika ingin test webhook notification:

1. **Install ngrok:**
   ```bash
   # Download dari https://ngrok.com/
   ngrok http 80
   ```

2. **Update Webhook URL:**
   - Copy URL dari ngrok (misal: `https://abc123.ngrok.io`)
   - Login ke Midtrans Dashboard
   - Settings > Configuration
   - Set Payment Notification URL: `https://abc123.ngrok.io/cashier/pos/midtrans/notification`

3. **Test Webhook:**
   - Lakukan transaksi
   - Check logs untuk incoming webhook
   - Verify transaction status update

## ✅ Test Checklist

- [ ] Credit Card Success (4811 1111 1111 1114)
- [ ] Credit Card Failed (4911 1111 1111 1113)
- [ ] GoPay Payment
- [ ] ShopeePay Payment
- [ ] Bank Transfer (BCA VA)
- [ ] Transaction recorded in database
- [ ] Receipt generation
- [ ] Cart cleared after payment
- [ ] Error handling for failed payments
- [ ] Webhook notification (if setup)

## 📞 Support

Jika ada masalah:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console untuk JavaScript errors
3. Verify Midtrans Dashboard untuk transaction status
4. Test dengan different payment methods

**Happy Testing! 🎉**