# Week 1-2: Setup & Database - CoffPOS

## ✅ Completed Tasks

### 1. Database Migrations
Semua tabel database telah dibuat dengan struktur lengkap:

- ✅ **users** - Tabel user dengan role (admin, manager, cashier)
- ✅ **categories** - Kategori produk
- ✅ **products** - Produk dengan relasi ke categories
- ✅ **customers** - Data pelanggan dengan loyalty points
- ✅ **transactions** - Transaksi penjualan
- ✅ **transaction_items** - Detail item transaksi
- ✅ **expenses** - Pengeluaran operasional

### 2. Database Relationships (ERD)
```
users (1) -----> (N) transactions
users (1) -----> (N) expenses
categories (1) -----> (N) products
customers (1) -----> (N) transactions
transactions (1) -----> (N) transaction_items
products (1) -----> (N) transaction_items
```

**Total Relasi: 6 relasi** ✅ (Memenuhi requirement > 1 relasi)

### 3. Models dengan Relationships
Semua model telah dibuat dengan:
- ✅ Fillable attributes
- ✅ Type casting
- ✅ Relationships (hasMany, belongsTo)
- ✅ Helper methods (isAdmin, isManager, isCashier)

**Models:**
- User.php
- Category.php
- Product.php
- Customer.php
- Transaction.php
- TransactionItem.php
- Expense.php

### 4. Database Seeders
Data awal telah dibuat untuk testing:

- ✅ **UserSeeder** - 3 users (admin, manager, cashier)
- ✅ **CategorySeeder** - 4 kategori (Coffee, Non Coffee, Food, Dessert)
- ✅ **ProductSeeder** - 12 produk sample
- ✅ **CustomerSeeder** - 3 customer sample

### 5. Authentication & Authorization
- ✅ **RoleMiddleware** - Middleware untuk role-based access control
- ✅ Registered di bootstrap/app.php
- ✅ Helper methods di User model (isAdmin, isManager, isCashier)

### 6. Storage Structure
Folder untuk upload gambar telah dibuat:
```
storage/app/public/
├── products/
├── categories/
├── users/
└── receipts/
```

## 🔑 Default Login Credentials

### Admin
- Email: `admin@coffpos.com`
- Password: `password`
- Role: admin

### Manager
- Email: `manager@coffpos.com`
- Password: `password`
- Role: manager

### Cashier
- Email: `cashier@coffpos.com`
- Password: `password`
- Role: cashier

## 📊 Database Schema Summary

### Users Table
- id, name, email, password, role, phone, avatar
- Role: admin, cashier, manager

### Categories Table
- id, name, description, image

### Products Table
- id, category_id, name, description, price, cost, stock, image, is_available

### Customers Table
- id, name, phone (unique), email, address, points

### Transactions Table
- id, user_id, customer_id, transaction_code (unique)
- subtotal, discount, tax, total
- payment_method, payment_amount, change_amount
- status, notes, transaction_date

### Transaction Items Table
- id, transaction_id, product_id, product_name
- quantity, price, subtotal, notes

### Expenses Table
- id, user_id, category, description, amount
- receipt_image, expense_date

## 🚀 Next Steps (Week 3-4)

1. **Backend Development:**
   - Create Controllers (Admin, Cashier, Frontend)
   - Implement CRUD operations
   - Create Form Requests for validation
   - Create Services (TransactionService, ReportService, ImageService)

2. **Authentication:**
   - Install Laravel Breeze atau buat custom auth
   - Setup login/register pages
   - Implement role-based redirects

3. **API Integration:**
   - Setup Google Maps API
   - Prepare for other API integrations

## 📝 Notes

- Database menggunakan SQLite (database.sqlite)
- Semua migrations berhasil dijalankan
- Seeders berhasil populate data sample
- Storage link sudah dibuat
- Middleware role sudah registered

## ✨ Database Features

- ✅ Foreign key constraints
- ✅ Cascade delete untuk relasi
- ✅ Unique constraints (email, phone, transaction_code)
- ✅ Enum types untuk role, payment_method, status
- ✅ Decimal precision untuk harga dan total
- ✅ Nullable fields untuk optional data
- ✅ Timestamps untuk audit trail

---
