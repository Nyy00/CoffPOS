# Panduan Setup Midtrans untuk CoffPOS

## 1. Registrasi Akun Midtrans

1. Kunjungi [https://midtrans.com](https://midtrans.com)
2. Daftar akun baru atau login jika sudah punya akun
3. Verifikasi email dan lengkapi profil bisnis

## 2. Dapatkan API Keys

### Sandbox (Development)
1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Pilih environment "Sandbox"
3. Pergi ke Settings > Access Keys
4. Copy **Server Key** dan **Client Key**

### Production (Live)
1. Setelah bisnis diverifikasi, switch ke environment "Production"
2. Copy **Server Key** dan **Client Key** untuk production

## 3. Konfigurasi Environment

Update file `.env` dengan API keys Midtrans:

```env
# Midtrans Configuration
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY_HERE
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY_HERE
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Catatan:**
- Untuk sandbox, keys dimulai dengan `SB-Mid-`
- Untuk production, keys dimulai dengan `Mid-`
- Set `MIDTRANS_IS_PRODUCTION=true` untuk production

## 4. Setup Webhook (Notification URL)

1. Di Midtrans Dashboard, pergi ke Settings > Configuration
2. Set **Payment Notification URL** ke:
   ```
   https://yourdomain.com/cashier/pos/midtrans/notification
   ```
3. Set **Finish Redirect URL** ke:
   ```
   https://yourdomain.com/cashier/pos
   ```
4. Set **Unfinish Redirect URL** ke:
   ```
   https://yourdomain.com/cashier/pos
   ```
5. Set **Error Redirect URL** ke:
   ```
   https://yourdomain.com/cashier/pos
   ```

## 5. Testing

### Test Cards untuk Sandbox

**Credit Card:**
- Card Number: `4811 1111 1111 1114`
- CVV: `123`
- Exp Month: `01`
- Exp Year: `2025`

**Hasil yang berbeda:**
- `4811 1111 1111 1114` → Success
- `4911 1111 1111 1113` → Failure
- `4411 1111 1111 1118` → Challenge by FDS

### Test E-Wallet

**GoPay:**
- Gunakan nomor HP: `081234567890`
- PIN: `123456`

**ShopeePay:**
- Gunakan nomor HP: `081234567890`

## 6. Metode Pembayaran yang Didukung

CoffPOS mendukung semua metode pembayaran Midtrans:

- **Credit Card** (Visa, MasterCard, JCB, Amex)
- **Debit Card** (BCA, Mandiri, BNI, BRI, dll)
- **Bank Transfer** (Virtual Account)
- **E-Wallet** (GoPay, ShopeePay, DANA, LinkAja, dll)
- **Over the Counter** (Indomaret, Alfamart)
- **Cardless Credit** (Akulaku, Kredivo)

## 7. Keamanan

1. **Jangan pernah** expose Server Key di frontend
2. Server Key hanya digunakan di backend
3. Client Key aman untuk digunakan di frontend
4. Selalu validasi notification dari Midtrans
5. Gunakan HTTPS untuk production

## 8. Monitoring

1. Monitor transaksi di Midtrans Dashboard
2. Check logs di `storage/logs/laravel.log`
3. Setup alerting untuk failed transactions

## 9. Go Live Checklist

- [ ] Bisnis sudah diverifikasi Midtrans
- [ ] API Keys production sudah didapat
- [ ] Environment variables sudah diupdate
- [ ] Webhook URLs sudah diset ke domain production
- [ ] SSL certificate sudah aktif
- [ ] Testing sudah dilakukan
- [ ] Monitoring sudah disetup

## 10. Troubleshooting

### Error: "Merchant not found"
- Pastikan Server Key benar
- Pastikan environment (sandbox/production) sesuai

### Error: "Access denied"
- Pastikan Client Key benar
- Check CORS settings di Midtrans Dashboard

### Webhook tidak diterima
- Pastikan URL webhook accessible dari internet
- Check firewall settings
- Pastikan endpoint mengembalikan HTTP 200

### Transaksi tidak ter-update
- Check webhook logs
- Pastikan notification handler berjalan dengan benar
- Check database connection

## Support

Jika ada masalah:
1. Check dokumentasi Midtrans: [https://docs.midtrans.com](https://docs.midtrans.com)
2. Contact Midtrans support: [https://midtrans.com/contact-us](https://midtrans.com/contact-us)
3. Check CoffPOS logs di `storage/logs/`