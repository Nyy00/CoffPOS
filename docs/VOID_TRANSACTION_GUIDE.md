# Void Transaction Guide

## Overview
Fitur void transaction memungkinkan admin untuk membatalkan transaksi yang sudah completed dengan alasan tertentu.

## Kapan Tombol Void Muncul

Tombol void **hanya muncul** jika memenuhi semua kondisi:
- ✅ Status transaksi = `completed`
- ✅ Transaksi dibuat dalam **24 jam terakhir**
- ✅ User memiliki permission admin/manager

## Proses Void Transaction

### 1. Klik Tombol Void
- Tombol void muncul di kolom Actions pada tabel transaksi
- Hanya untuk transaksi yang memenuhi syarat

### 2. Modal Konfirmasi
- Modal akan menampilkan kode transaksi
- **Wajib mengisi alasan void** (required field)
- Informasi dampak void transaction

### 3. Dampak Void Transaction
Ketika transaksi di-void, sistem akan:
- ✅ Mengubah status menjadi `voided`
- ✅ **Mengembalikan stock produk** sesuai quantity yang dibeli
- ✅ **Mengurangi loyalty points** customer (jika ada)
- ✅ Menyimpan alasan void dan siapa yang melakukan
- ✅ Mencatat waktu void

## Database Schema

### Kolom Baru di Transactions Table
```sql
- void_reason (string, nullable) - Alasan void
- voided_by (foreign key to users) - Siapa yang void
- voided_at (timestamp, nullable) - Kapan di-void
```

### Status Enum
```sql
status ENUM('completed', 'cancelled', 'voided')
```

## Troubleshooting

### Error: "Check constraint violation"
**Penyebab**: Database constraint tidak mengizinkan status 'voided'
**Solusi**: Jalankan migration terbaru
```bash
php artisan migrate
```

### Tombol Void Tidak Muncul
**Kemungkinan penyebab**:
1. Transaksi sudah lebih dari 24 jam
2. Status bukan 'completed' (mungkin sudah voided)
3. User tidak memiliki permission

### Modal Void Error JavaScript
**Penyebab**: Elemen modal tidak ditemukan
**Solusi**: Pastikan modal `voidModal` ada di halaman

## API Endpoint

```
POST /admin/transactions/{transaction}/void
```

**Request Body**:
```json
{
    "reason": "Alasan void transaction"
}
```

**Response Success**:
```json
{
    "message": "Transaction has been voided successfully."
}
```

## Testing

### Manual Test
1. Buat transaksi baru melalui POS
2. Pastikan status = completed
3. Cek tombol void muncul di admin/transactions
4. Test void dengan alasan yang jelas

### Script Test
```bash
# Test void functionality
php force-test-void.php

# Check transaction status
php check-transactions.php
```

## Security Notes

- Hanya admin yang bisa void transaction
- Void transaction tidak bisa di-undo
- Semua void activity ter-log dengan user dan timestamp
- Alasan void wajib diisi untuk audit trail

## Best Practices

1. **Selalu isi alasan yang jelas** saat void transaction
2. **Konfirmasi dengan customer** sebelum void (jika perlu)
3. **Cek stock produk** setelah void untuk memastikan ter-restore
4. **Monitor void activity** untuk mencegah penyalahgunaan