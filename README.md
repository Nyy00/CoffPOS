# ☕ CoffPOS - Coffee Shop Point of Sale System

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![SQLite](https://img.shields.io/badge/Database-SQLite-green)
![Status](https://img.shields.io/badge/Status-Week%201--2%20Completed-success)

Sistem Point of Sale (POS) modern untuk coffee shop yang memudahkan kasir dalam mengelola transaksi penjualan, stok produk, manajemen menu, dan laporan penjualan.

## 📋 Deskripsi Project

CoffPOS adalah sistem POS komprehensif yang dibangun dengan Laravel 12, Blade, dan SQLite. Sistem ini dilengkapi dengan dashboard admin yang powerful dan interface kasir yang user-friendly.

## ✨ Fitur Utama

### 🎯 Core Features
- **Dashboard Admin** - Analytics dan statistik penjualan
- **POS Interface** - Interface kasir yang cepat dan mudah
- **Manajemen Produk** - CRUD lengkap dengan kategori
- **Manajemen Customer** - Sistem loyalty points
- **Transaksi** - Pencatatan transaksi lengkap
- **Laporan** - PDF reporting untuk berbagai jenis laporan
- **Manajemen Pengeluaran** - Tracking pengeluaran operasional

### 👥 Role-Based Access
- **Admin** - Full access ke semua fitur
- **Manager** - Analytics, reports, dan manajemen produk
- **Cashier** - POS interface dan transaksi

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- SQLite extension

### Installation

#### Option 1: Quick Setup with Laravel Herd (Recommended) ⚡
**5-minute setup!**

1. Install [Laravel Herd](https://herd.laravel.com/) and [TablePlus](https://tableplus.com/)
2. Move project to Herd directory
3. Run setup commands
4. Access at http://coffpos.test

**Full Guide**: [QUICK_SETUP_HERD.md](QUICK_SETUP_HERD.md)

---

#### Option 2: Traditional Setup

1. **Clone Repository**
```bash
git clone https://github.com/your-team/CoffPOS.git
cd CoffPOS
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
touch database/database.sqlite
php artisan migrate --seed
```

5. **Storage Link**
```bash
php artisan storage:link
```

6. **Build Assets**
```bash
npm run dev
```

7. **Run Server**
```bash
php artisan serve
```

8. **Access Application**
```
URL: http://localhost:8000
```

## 🔑 Default Login Credentials

### Admin
- **Email**: admin@coffpos.com
- **Password**: password
- **Role**: Full access

### Manager
- **Email**: manager@coffpos.com
- **Password**: password
- **Role**: Analytics & Reports

### Cashier
- **Email**: cashier@coffpos.com
- **Password**: password
- **Role**: POS Interface

## 📊 Database Structure

### Main Tables
- **users** - User management dengan role
- **categories** - Kategori produk
- **products** - Produk dengan harga dan stok
- **customers** - Data pelanggan dengan loyalty points
- **transactions** - Transaksi penjualan
- **transaction_items** - Detail item transaksi
- **expenses** - Pengeluaran operasional

### Relationships
```
users (1) → (N) transactions
users (1) → (N) expenses
categories (1) → (N) products
customers (1) → (N) transactions
transactions (1) → (N) transaction_items
products (1) → (N) transaction_items
```

**Total: 6 relasi antar tabel**

## 📁 Project Structure

```
CoffPOS/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
└── storage/
    └── app/
        └── public/
            ├── products/
            ├── categories/
            ├── users/
            └── receipts/
```

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 12
- **Database**: SQLite
- **ORM**: Eloquent
- **Authentication**: Laravel Sanctum

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Tailwind CSS
- **JavaScript**: Alpine.js
- **Charts**: Chart.js / ApexCharts

### Tools
- **Database Manager**: TablePlus
- **Version Control**: Git/GitHub
- **Package Manager**: Composer, NPM

## 📚 Documentation

- [Full Specification](coffpos_specification.md) - Complete project spec

## 🎯 Development Timeline

- ✅ **Week 1-2**: Setup & Database (COMPLETED)
- 🔄 **Week 3-4**: Backend Development (IN PROGRESS)
- ⏳ **Week 5-6**: Frontend Development
- ⏳ **Week 7-8**: POS & Dashboard
- ⏳ **Week 9-10**: Reporting & Polish
- ⏳ **Week 11-12**: Deployment & Documentation


## 🔧 Useful Commands

```bash
# Database
php artisan migrate:fresh --seed  # Reset database
php artisan db:show               # Show database info

# Development
php artisan serve                 # Start server
npm run dev                       # Build assets

# Cache
php artisan optimize:clear        # Clear all caches

# Tinker
php artisan tinker                # Interactive shell
```


## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ProductTest
```

## 🐛 Troubleshooting

### Database tidak terbuat
```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```

### Storage link error
```bash
php artisan storage:link --force
```

### Permission error (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```


## 📝 License

This project is licensed under the MIT License.

## 👥 Team

**Nama Tim**: [Nama Tim Anda]

- **Project Manager**: [Nama]
- **Backend Developer 1**: [Nama]
- **Backend Developer 2**: [Nama]
- **Frontend Developer 1**: [Nama]
- **Frontend Developer 2**: [Nama]

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check dokumentasi di folder project
2. Review spesifikasi di [coffpos_specification.md](coffpos_specification.md)
3. Konsultasi dengan mentor/dosen

## 🎓 Academic Project

Project ini dibuat untuk memenuhi Tugas Besar Praktikum Web 2025/2026.

**Teknologi**: Laravel 12 + Blade + SQLite + TablePlus

---

<p align="center">
Made with ☕ and ❤️ by [-]
</p>

<p align="center">
<a href="https://laravel.com" target="_blank">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
</a>
</p>
