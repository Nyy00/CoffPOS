# 📖 CoffPOS User Manual

**Panduan Lengkap Penggunaan Sistem Point of Sale CoffPOS**

---

## 📋 **Daftar Isi**

1. [Pengenalan CoffPOS](#pengenalan-coffpos)
2. [Login dan Autentikasi](#login-dan-autentikasi)
3. [Dashboard Admin](#dashboard-admin)
4. [Sistem Point of Sale (POS)](#sistem-point-of-sale-pos)
5. [Manajemen Produk](#manajemen-produk)
6. [Manajemen Kategori](#manajemen-kategori)
7. [Manajemen Customer](#manajemen-customer)
8. [Manajemen Pengeluaran](#manajemen-pengeluaran)
9. [Manajemen Transaksi](#manajemen-transaksi)
10. [Sistem Reporting](#sistem-reporting)
11. [Manajemen User](#manajemen-user)
12. [Tips dan Troubleshooting](#tips-dan-troubleshooting)

---

## 🌟 **Pengenalan CoffPOS**

CoffPOS adalah sistem Point of Sale modern yang dirancang khusus untuk coffee shop, restoran, dan bisnis retail. Sistem ini menyediakan solusi lengkap untuk:

- **Penjualan (POS)**: Interface yang user-friendly untuk proses transaksi
- **Inventory Management**: Manajemen stok produk otomatis
- **Customer Management**: Database customer dengan loyalty points
- **Financial Reporting**: Laporan keuangan dan analisis bisnis
- **Multi-User Support**: Sistem role-based untuk berbagai level user

### **Fitur Utama**
✅ **Real-time Dashboard** dengan statistik penjualan  
✅ **POS System** yang responsif dan mudah digunakan  
✅ **Inventory Tracking** otomatis  
✅ **Customer Loyalty Program**  
✅ **Comprehensive Reporting** dengan PDF export  
✅ **Multi-Payment Methods** (Cash, Card, E-wallet, QRIS)  
✅ **Receipt Printing** dan reprint functionality  

---

## 🔐 **Login dan Autentikasi**

### **Mengakses Sistem**

1. **Buka browser** dan akses URL aplikasi
2. **Masukkan credentials** sesuai role Anda:

#### **Default Login Credentials**

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Admin** | admin@coffpos.com | password | Full access ke semua fitur |
| **Manager** | manager@coffpos.com | password | Akses manajemen dan reporting |
| **Cashier** | cashier@coffpos.com | password | Akses POS dan transaksi |

### **Proses Login**

1. **Masukkan Email** dan **Password**
2. Klik tombol **"Login"**
3. Sistem akan redirect sesuai role:
   - **Admin/Manager**: Dashboard Admin
   - **Cashier**: Halaman POS

### **Logout**

1. Klik **profile icon** di pojok kanan atas
2. Pilih **"Logout"**
3. Anda akan diarahkan kembali ke halaman login

### **Reset Password**

1. Di halaman login, klik **"Forgot Password?"**
2. Masukkan **email address**
3. Check email untuk **reset link**
4. Follow instruksi untuk set password baru

---

## 📊 **Dashboard Admin**

Dashboard adalah pusat kontrol utama yang menampilkan overview bisnis Anda.

### **Komponen Dashboard**

#### **1. Statistics Cards**
Menampilkan metrik penting:
- **Today's Revenue**: Total penjualan hari ini
- **Today's Transactions**: Jumlah transaksi hari ini
- **Items Sold**: Total item terjual hari ini
- **Low Stock Items**: Produk dengan stok menipis

#### **2. Sales Charts**
- **Revenue Chart**: Grafik penjualan harian/mingguan
- **Top Products Chart**: Produk terlaris
- **Payment Methods**: Distribusi metode pembayaran

#### **3. Recent Activities**
- **Recent Transactions**: 10 transaksi terakhir
- **Low Stock Alerts**: Notifikasi stok menipis
- **Top Customers**: Customer dengan pembelian terbanyak

### **Navigasi Dashboard**

#### **Main Menu**
- **Dashboard**: Overview bisnis
- **POS**: Sistem point of sale
- **Products**: Manajemen produk
- **Categories**: Manajemen kategori
- **Customers**: Database customer
- **Expenses**: Manajemen pengeluaran
- **Transactions**: History transaksi
- **Reports**: Sistem reporting
- **Users**: Manajemen user (Admin only)

#### **Quick Actions**
- **Add Product**: Tambah produk baru
- **Add Customer**: Registrasi customer baru
- **View Reports**: Akses cepat ke laporan
- **POS System**: Langsung ke sistem kasir

---

## 🛒 **Sistem Point of Sale (POS)**

POS adalah jantung sistem CoffPOS untuk proses transaksi penjualan.

### **Interface POS**

#### **Layout POS**
1. **Product Search & Grid** (Kiri): Pencarian dan display produk
2. **Shopping Cart** (Kanan): Keranjang belanja
3. **Customer Selection** (Atas): Pilih customer
4. **Payment Section** (Bawah): Proses pembayaran

### **Proses Transaksi**

#### **1. Pilih Customer (Opsional)**
```
🔍 Search Customer
┌─────────────────────────────┐
│ Search customer...          │
│ ○ Walk-in Customer         │
│ ○ John Doe (Points: 150)   │
│ ○ Jane Smith (Points: 89)  │
│ + Add New Customer         │
└─────────────────────────────┘
```

**Cara Pilih Customer:**
- **Walk-in**: Untuk customer tanpa registrasi
- **Registered**: Pilih dari database untuk loyalty points
- **Add New**: Registrasi customer baru

#### **2. Tambah Produk ke Cart**

**Metode 1: Product Grid**
1. Browse produk di grid
2. Klik **"Add to Cart"** pada produk
3. Produk otomatis masuk ke cart

**Metode 2: Search Product**
1. Ketik nama produk di search box
2. Pilih dari hasil pencarian
3. Klik untuk add ke cart

**Metode 3: Category Filter**
1. Pilih kategori di filter
2. Browse produk dalam kategori
3. Add produk yang diinginkan

#### **3. Kelola Shopping Cart**

```
🛒 Shopping Cart (3 items)
┌─────────────────────────────────────┐
│ Espresso                      x2    │
│ Rp 25,000 each           Rp 50,000 │
│ [−] [+] [🗑️] [📝]                   │
├─────────────────────────────────────┤
│ Cappuccino                    x1    │
│ Rp 30,000 each           Rp 30,000 │
│ [−] [+] [🗑️] [📝]                   │
├─────────────────────────────────────┤
│ Croissant                     x1    │
│ Rp 15,000 each           Rp 15,000 │
│ [−] [+] [🗑️] [📝]                   │
└─────────────────────────────────────┘

Subtotal:              Rp 95,000
Discount:              Rp  5,000
Tax (10%):             Rp  9,000
TOTAL:                 Rp 99,000
```

**Fungsi Cart:**
- **[−] [+]**: Ubah quantity
- **[🗑️]**: Hapus item
- **[📝]**: Tambah notes
- **Clear Cart**: Kosongkan semua item

#### **4. Apply Discount**

**Jenis Discount:**
- **Percentage**: Diskon persentase (5%, 10%, dll)
- **Fixed Amount**: Diskon nominal tetap
- **Loyalty Points**: Gunakan poin customer

**Cara Apply Discount:**
1. Klik **"Apply Discount"**
2. Pilih jenis discount
3. Masukkan nilai discount
4. Klik **"Apply"**

#### **5. Proses Pembayaran**

```
💳 Payment Section
┌─────────────────────────────────────┐
│ Payment Method:                     │
│ ○ Cash        ○ Debit Card         │
│ ○ Credit Card ○ E-Wallet           │
│ ○ QRIS        ○ Bank Transfer      │
├─────────────────────────────────────┤
│ Total Amount:         Rp 99,000    │
│ Payment Received:     Rp 100,000   │
│ Change:               Rp 1,000     │
├─────────────────────────────────────┤
│ [💰 Process Payment]               │
│ [⏸️ Hold Transaction]              │
└─────────────────────────────────────┘
```

**Langkah Pembayaran:**
1. **Pilih Payment Method**
2. **Masukkan jumlah bayar** (untuk cash)
3. **Klik "Process Payment"**
4. **Print receipt** otomatis
5. **Cart reset** untuk transaksi berikutnya

#### **6. Receipt dan Completion**

Setelah pembayaran berhasil:
- **Receipt otomatis print**
- **Points ditambahkan** ke customer (jika ada)
- **Stock otomatis berkurang**
- **Transaction tersimpan** di database

### **Fitur Tambahan POS**

#### **Hold Transaction**
Untuk menyimpan transaksi sementara:
1. Klik **"Hold Transaction"**
2. Beri nama/kode untuk identifikasi
3. Transaksi tersimpan di held list
4. Bisa dilanjutkan kapan saja

#### **Reprint Receipt**
1. Akses **Transaction History**
2. Pilih transaksi yang ingin diprint ulang
3. Klik **"Reprint Receipt"**

---

## 📦 **Manajemen Produk**

Kelola semua produk yang dijual di toko Anda.

### **Daftar Produk**

#### **Tampilan List**
```
📦 Products Management
┌─────────────────────────────────────────────────────────┐
│ [🔍 Search] [📂 Category ▼] [+ Add Product]            │
├─────────────────────────────────────────────────────────┤
│ 📷 | Espresso        | Coffee    | Rp 25,000 | 50 | ✅ │
│ 📷 | Cappuccino      | Coffee    | Rp 30,000 | 30 | ✅ │
│ 📷 | Croissant       | Food      | Rp 15,000 | 20 | ✅ │
│ 📷 | Green Tea       | Beverage  | Rp 20,000 |  5 | ⚠️ │
└─────────────────────────────────────────────────────────┘
```

#### **Fitur List**
- **Search**: Cari produk berdasarkan nama
- **Filter Category**: Filter berdasarkan kategori
- **Sort**: Urutkan berdasarkan nama, harga, stok
- **Bulk Actions**: Operasi massal (delete, update stock)

### **Tambah Produk Baru**

#### **Form Add Product**
```
➕ Add New Product
┌─────────────────────────────────────┐
│ Product Name: [________________]    │
│ Category:     [Coffee        ▼]    │
│ Description:  [________________]    │
│               [________________]    │
│ Price:        [Rp ____________]    │
│ Cost:         [Rp ____________]    │
│ Stock:        [______________]     │
│ Image:        [Choose File...]     │
│ Available:    [☑️] Yes  [☐] No     │
│                                    │
│ [💾 Save Product] [❌ Cancel]      │
└─────────────────────────────────────┘
```

#### **Field Explanation**
- **Product Name**: Nama produk (required)
- **Category**: Kategori produk (dropdown)
- **Description**: Deskripsi produk (optional)
- **Price**: Harga jual (required)
- **Cost**: Harga beli/cost (untuk profit calculation)
- **Stock**: Jumlah stok awal
- **Image**: Upload gambar produk (JPG, PNG, max 2MB)
- **Available**: Status ketersediaan produk

### **Edit Produk**

1. **Klik "Edit"** pada produk yang ingin diubah
2. **Modify fields** yang diperlukan
3. **Upload image baru** (jika perlu)
4. **Klik "Update Product"**

### **Manajemen Stok**

#### **Update Stock Individual**
1. Klik **"Update Stock"** pada produk
2. Masukkan **jumlah stok baru**
3. Pilih **reason** (Restock, Adjustment, dll)
4. **Save changes**

#### **Bulk Stock Update**
1. **Select multiple products** dengan checkbox
2. Klik **"Bulk Actions" → "Update Stock"**
3. Masukkan **stock adjustment**
4. **Apply to selected**

#### **Low Stock Alerts**
- Produk dengan stok < 10 akan muncul **warning icon**
- **Dashboard alert** untuk stok menipis
- **Email notification** (jika dikonfigurasi)

### **Import/Export Produk**

#### **Export Products**
1. Klik **"Export"** button
2. Pilih **format** (CSV, Excel)
3. **Download file** hasil export

#### **Import Products** (Bulk)
1. **Download template** CSV
2. **Fill data** produk di template
3. **Upload file** via import feature
4. **Review** dan **confirm import**

---

## 📂 **Manajemen Kategori**

Organisir produk dengan sistem kategori yang terstruktur.

### **Daftar Kategori**

```
📂 Categories Management
┌─────────────────────────────────────────────┐
│ [+ Add Category]                            │
├─────────────────────────────────────────────┤
│ 📷 | Coffee    | Hot & Cold Coffee | 25 | ✏️ │
│ 📷 | Food      | Snacks & Meals    | 12 | ✏️ │
│ 📷 | Beverage  | Non-Coffee Drinks |  8 | ✏️ │
│ 📷 | Dessert   | Sweet Treats      |  5 | ✏️ │
└─────────────────────────────────────────────┘
```

### **Tambah Kategori**

```
➕ Add New Category
┌─────────────────────────────────────┐
│ Category Name: [________________]   │
│ Description:   [________________]   │
│                [________________]   │
│ Image:         [Choose File...]     │
│                                     │
│ [💾 Save Category] [❌ Cancel]      │
└─────────────────────────────────────┘
```

### **Fitur Kategori**
- **Hierarchical structure**: Kategori dan sub-kategori
- **Image support**: Upload gambar untuk setiap kategori
- **Product count**: Jumlah produk per kategori
- **Drag & drop reorder**: Urutkan kategori

---

## 👥 **Manajemen Customer**

Kelola database customer dan loyalty program.

### **Daftar Customer**

```
👥 Customer Management
┌─────────────────────────────────────────────────────────────┐
│ [🔍 Search] [📊 Filter] [+ Add Customer]                   │
├─────────────────────────────────────────────────────────────┤
│ John Doe      | john@email.com    | 150 pts | 25 | Rp 2.5M │
│ Jane Smith    | jane@email.com    |  89 pts | 18 | Rp 1.8M │
│ Bob Johnson   | bob@email.com     | 200 pts | 30 | Rp 3.2M │
└─────────────────────────────────────────────────────────────┘
```

**Kolom Information:**
- **Name**: Nama customer
- **Email**: Email address
- **Points**: Loyalty points terkumpul
- **Transactions**: Jumlah transaksi
- **Total Spent**: Total pembelian

### **Tambah Customer**

```
➕ Add New Customer
┌─────────────────────────────────────┐
│ Full Name:    [________________]    │
│ Email:        [________________]    │
│ Phone:        [________________]    │
│ Address:      [________________]    │
│               [________________]    │
│ Birth Date:   [DD/MM/YYYY]         │
│ Gender:       [Male ▼]             │
│                                     │
│ [💾 Save Customer] [❌ Cancel]      │
└─────────────────────────────────────┘
```

### **Customer Details**

Klik nama customer untuk melihat detail:

#### **Customer Profile**
- **Personal Information**
- **Contact Details**
- **Loyalty Points Balance**
- **Membership Status**

#### **Transaction History**
```
📊 Transaction History - John Doe
┌─────────────────────────────────────────────┐
│ Date       | Code    | Items | Total       │
├─────────────────────────────────────────────┤
│ 2025-12-19 | TRX-001 |   3   | Rp 95,000  │
│ 2025-12-18 | TRX-002 |   2   | Rp 50,000  │
│ 2025-12-17 | TRX-003 |   1   | Rp 25,000  │
└─────────────────────────────────────────────┘
```

#### **Loyalty Points**
- **Points Earned**: Total poin yang diperoleh
- **Points Used**: Poin yang sudah digunakan
- **Current Balance**: Saldo poin saat ini
- **Points History**: Riwayat perolehan dan penggunaan poin

### **Loyalty Program**

#### **Point System**
- **Earn Rate**: 1 point per Rp 1,000 spent
- **Redemption**: 100 points = Rp 10,000 discount
- **Bonus Points**: Special promotions

#### **Customer Tiers**
- **Bronze**: 0-499 points
- **Silver**: 500-999 points  
- **Gold**: 1000+ points

---

## 💰 **Manajemen Pengeluaran**

Track semua pengeluaran bisnis untuk analisis profit-loss.

### **Daftar Pengeluaran**

```
💰 Expense Management
┌─────────────────────────────────────────────────────────────┐
│ [🔍 Search] [📅 Date] [📂 Category] [+ Add Expense]        │
├─────────────────────────────────────────────────────────────┤
│ 2025-12-19 | Coffee Beans    | Inventory  | Rp 500,000 | 📄 │
│ 2025-12-18 | Electricity     | Utilities  | Rp 150,000 | 📄 │
│ 2025-12-17 | Staff Salary    | Payroll    | Rp 2,000,000| 📄 │
└─────────────────────────────────────────────────────────────┘
```

### **Tambah Pengeluaran**

```
➕ Add New Expense
┌─────────────────────────────────────┐
│ Description:  [________________]    │
│ Category:     [Inventory     ▼]    │
│ Amount:       [Rp ____________]    │
│ Date:         [DD/MM/YYYY]         │
│ Receipt:      [Choose File...]     │
│ Notes:        [________________]    │
│               [________________]    │
│                                     │
│ [💾 Save Expense] [❌ Cancel]       │
└─────────────────────────────────────┘
```

### **Kategori Pengeluaran**

**Default Categories:**
- **Inventory**: Pembelian stok/bahan baku
- **Utilities**: Listrik, air, internet
- **Payroll**: Gaji karyawan
- **Marketing**: Promosi dan advertising
- **Maintenance**: Perawatan equipment
- **Other**: Pengeluaran lainnya

### **Receipt Management**

- **Upload Receipt**: Foto/scan receipt sebagai bukti
- **View Receipt**: Preview receipt yang diupload
- **Download Receipt**: Download file receipt
- **Receipt Required**: Untuk expense > Rp 100,000

---

## 📋 **Manajemen Transaksi**

Monitor dan kelola semua transaksi penjualan.

### **Transaction List**

```
📋 Transaction Management
┌─────────────────────────────────────────────────────────────────┐
│ [🔍 Search] [📅 Date] [💳 Payment] [👤 Cashier] [📊 Export]    │
├─────────────────────────────────────────────────────────────────┤
│ TRX-001 | 19/12 10:30 | John Doe  | Alice | 3 | Rp 95,000 | ✅ │
│ TRX-002 | 19/12 11:15 | Walk-in   | Alice | 2 | Rp 50,000 | ✅ │
│ TRX-003 | 19/12 12:00 | Jane Smith| Bob   | 1 | Rp 25,000 | ✅ │
└─────────────────────────────────────────────────────────────────┘
```

### **Transaction Details**

Klik transaction code untuk detail:

```
📄 Transaction Details - TRX-001
┌─────────────────────────────────────┐
│ Date: 19 December 2025, 10:30 AM   │
│ Customer: John Doe                  │
│ Cashier: Alice Johnson              │
│ Payment: Cash                       │
├─────────────────────────────────────┤
│ Items:                              │
│ • Espresso x2        Rp 50,000     │
│ • Croissant x1       Rp 15,000     │
│                                     │
│ Subtotal:            Rp 65,000     │
│ Discount:            Rp  5,000     │
│ Tax (10%):           Rp  6,000     │
│ TOTAL:               Rp 66,000     │
│                                     │
│ Points Earned: 66 points            │
├─────────────────────────────────────┤
│ [🖨️ Reprint] [❌ Void] [📧 Email]   │
└─────────────────────────────────────┘
```

### **Transaction Actions**

#### **Reprint Receipt**
1. Klik **"Reprint"** pada transaksi
2. Receipt akan print ulang
3. Useful untuk customer yang kehilangan receipt

#### **Void Transaction**
1. Klik **"Void"** pada transaksi
2. Masukkan **reason** untuk void
3. **Confirm void** action
4. Stock akan **dikembalikan** otomatis
5. Customer points akan **dikurangi**

**Syarat Void:**
- Transaksi masih dalam **24 jam**
- Status transaksi **"Completed"**
- User memiliki **permission** untuk void

### **Transaction Filters**

#### **Filter Options**
- **Date Range**: Filter berdasarkan tanggal
- **Payment Method**: Cash, Card, E-wallet, dll
- **Cashier**: Filter berdasarkan kasir
- **Customer**: Filter berdasarkan customer
- **Status**: Completed, Voided, Pending
- **Amount Range**: Filter berdasarkan nominal

#### **Export Transactions**
1. **Apply filters** sesuai kebutuhan
2. Klik **"Export"** button
3. Pilih **format** (CSV, Excel, PDF)
4. **Download** file hasil export

---

## 📊 **Sistem Reporting**

Generate berbagai laporan untuk analisis bisnis.

### **Report Dashboard**

```
📊 Reports Dashboard
┌─────────────────────────────────────────────────────────────┐
│ 📈 Daily Sales     📅 Monthly Sales    📦 Product Report   │
│ 📊 Stock Report    💰 Profit & Loss    👥 Customer Report  │
└─────────────────────────────────────────────────────────────┘
```

### **Daily Sales Report**

**Informasi yang Disajikan:**
- **Revenue Summary**: Total penjualan hari ini
- **Transaction Count**: Jumlah transaksi
- **Items Sold**: Total item terjual
- **Average Transaction**: Rata-rata per transaksi
- **Payment Methods**: Breakdown metode pembayaran
- **Hourly Breakdown**: Penjualan per jam
- **Top Products**: Produk terlaris hari ini
- **Top Customers**: Customer dengan pembelian terbanyak

#### **Generate Daily Report**
1. Pilih **"Daily Sales Report"**
2. **Select date** (default: today)
3. Klik **"Generate Report"**
4. **View** report di browser atau **Export PDF**

### **Monthly Sales Report**

**Komponen Report:**
- **Monthly Summary**: Overview penjualan bulan ini
- **Daily Breakdown**: Penjualan per hari dalam bulan
- **Weekly Trends**: Trend penjualan mingguan
- **Growth Comparison**: Perbandingan dengan bulan lalu
- **Top Products**: Produk terlaris bulan ini
- **Customer Analytics**: Analisis customer behavior

### **Product Performance Report**

**Analisis Produk:**
- **Top Performers**: Produk dengan penjualan tertinggi
- **Low Performers**: Produk dengan penjualan rendah
- **Category Analysis**: Performa per kategori
- **Profit Margin**: Analisis margin keuntungan
- **Stock Movement**: Pergerakan stok produk

### **Stock Report**

**Inventory Analysis:**
- **Current Stock**: Status stok saat ini
- **Low Stock Items**: Produk yang perlu restock
- **Out of Stock**: Produk yang habis
- **Stock Value**: Nilai total inventory
- **Reorder Recommendations**: Rekomendasi pemesanan

### **Profit & Loss Report**

**Financial Analysis:**
- **Revenue**: Total pendapatan
- **Cost of Goods Sold**: Harga pokok penjualan
- **Gross Profit**: Laba kotor
- **Operating Expenses**: Biaya operasional
- **Net Profit**: Laba bersih
- **Profit Margin**: Persentase keuntungan

#### **Generate P&L Report**
```
💰 Profit & Loss Report
┌─────────────────────────────────────┐
│ Period: [01/12/2025] to [31/12/2025]│
│                                     │
│ Revenue:              Rp 10,000,000 │
│ COGS:                 Rp  6,000,000 │
│ Gross Profit:         Rp  4,000,000 │
│                                     │
│ Operating Expenses:   Rp  2,500,000 │
│ Net Profit:           Rp  1,500,000 │
│                                     │
│ Profit Margin: 15%                  │
└─────────────────────────────────────┘
```

### **Custom Reports**

#### **Report Builder**
1. Pilih **"Custom Report"**
2. **Select report type** (Sales, Products, Customers)
3. **Set date range**
4. **Choose filters** dan **grouping**
5. **Generate report**

#### **Export Options**
- **PDF**: Professional formatted report
- **Excel**: Data untuk analisis lebih lanjut
- **CSV**: Raw data export

---

## 👤 **Manajemen User**

Kelola user dan permissions (Admin only).

### **User List**

```
👤 User Management
┌─────────────────────────────────────────────────────────┐
│ [+ Add User]                                            │
├─────────────────────────────────────────────────────────┤
│ 👤 | Alice Johnson | alice@email.com | Admin    | ✅ | ✏️ │
│ 👤 | Bob Smith     | bob@email.com   | Manager  | ✅ | ✏️ │
│ 👤 | Carol Davis   | carol@email.com | Cashier  | ✅ | ✏️ │
└─────────────────────────────────────────────────────────┘
```

### **User Roles**

#### **Admin**
- **Full access** ke semua fitur
- **User management**
- **System configuration**
- **All reports** dan analytics

#### **Manager**
- **Dashboard** dan reporting
- **Product** dan inventory management
- **Customer** management
- **Transaction** monitoring
- **Cannot manage** users

#### **Cashier**
- **POS system** access
- **Basic transaction** functions
- **Customer registration**
- **Limited reporting**

### **Add New User**

```
➕ Add New User
┌─────────────────────────────────────┐
│ Full Name:    [________________]    │
│ Email:        [________________]    │
│ Password:     [________________]    │
│ Confirm Pass: [________________]    │
│ Role:         [Cashier       ▼]    │
│ Phone:        [________________]    │
│ Avatar:       [Choose File...]     │
│                                     │
│ [💾 Create User] [❌ Cancel]        │
└─────────────────────────────────────┘
```

### **User Management Actions**

#### **Edit User**
- Update **personal information**
- Change **role** dan permissions
- **Reset password**
- **Upload avatar**

#### **Deactivate User**
- **Suspend** user access
- **Preserve** user data
- **Reactivate** when needed

#### **Activity Log**
- Track **user activities**
- **Login/logout** history
- **Transaction** history per user

---

## 🔧 **Tips dan Troubleshooting**

### **Performance Tips**

#### **Optimize POS Performance**
1. **Regular browser cache clear**
2. **Close unused browser tabs**
3. **Use Chrome/Firefox** untuk performa terbaik
4. **Stable internet connection**

#### **Database Maintenance**
1. **Regular backup** database
2. **Clean old logs** secara berkala
3. **Monitor disk space**
4. **Update system** regularly

### **Common Issues**

#### **Login Problems**

**Issue**: Cannot login
**Solutions:**
1. Check **email** dan **password** spelling
2. Clear **browser cache** dan cookies
3. Try **different browser**
4. Contact **system administrator**

**Issue**: Forgot password
**Solutions:**
1. Use **"Forgot Password"** link
2. Check **email** for reset link
3. Contact **admin** for manual reset

#### **POS Issues**

**Issue**: Products not loading
**Solutions:**
1. **Refresh** browser page
2. Check **internet connection**
3. Clear **browser cache**
4. Contact **technical support**

**Issue**: Receipt not printing
**Solutions:**
1. Check **printer connection**
2. Verify **printer settings**
3. **Restart printer**
4. Check **paper** dan **ink**

#### **Report Issues**

**Issue**: PDF not generating
**Solutions:**
1. **Wait** for processing (large reports take time)
2. Try **smaller date range**
3. **Refresh** page dan try again
4. Contact **support** if persists

**Issue**: Data not accurate
**Solutions:**
1. Check **date range** selection
2. Verify **filters** applied
3. **Refresh** data dan regenerate
4. Contact **admin** for data verification

### **Best Practices**

#### **Daily Operations**
1. **Start shift**: Check system status
2. **Regular backup**: Save important data
3. **Monitor stock**: Check low stock alerts
4. **End shift**: Generate daily report

#### **Security Practices**
1. **Strong passwords**: Use complex passwords
2. **Regular logout**: Don't leave system unattended
3. **Limited access**: Only necessary permissions
4. **Regular updates**: Keep system updated

#### **Data Management**
1. **Regular cleanup**: Archive old data
2. **Backup strategy**: Multiple backup copies
3. **Data validation**: Regular data checks
4. **Documentation**: Keep records updated

---

## 📞 **Support & Contact**

### **Technical Support**
- **Email**: support@coffpos.com
- **Phone**: +62-21-1234-5678
- **Hours**: Monday-Friday, 9 AM - 6 PM

### **Training & Onboarding**
- **User Training**: Available on request
- **Video Tutorials**: Access online resources
- **Documentation**: Complete user guides

### **System Updates**
- **Automatic Updates**: System updates automatically
- **Feature Announcements**: Via email notifications
- **Maintenance Schedule**: Announced in advance

---

## 📋 **Quick Reference**

### **Keyboard Shortcuts**
- **Ctrl + /**: Open search
- **Ctrl + N**: New transaction (POS)
- **Ctrl + P**: Print receipt
- **Ctrl + S**: Save current form
- **Esc**: Close modal/cancel action

### **Default Settings**
- **Tax Rate**: 10%
- **Currency**: Indonesian Rupiah (Rp)
- **Date Format**: DD/MM/YYYY
- **Time Format**: 24-hour
- **Language**: Indonesian/English

### **System Limits**
- **Max file upload**: 2MB
- **Session timeout**: 2 hours
- **Max products per transaction**: 100
- **Max discount**: 50%

---

**CoffPOS User Manual v1.0**  
*Last Updated: December 2025*  
*© 2025 CoffPOS. All rights reserved.*