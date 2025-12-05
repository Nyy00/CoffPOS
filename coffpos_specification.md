# CoffPOS - Coffee Shop Point of Sale System
## Spesifikasi Lengkap Tugas Besar Praktikum Web 2025/2026

---

## 📋 Informasi Tim

**Nama Tim:** [Nama Tim Anda]  
**Nama Project:** CoffPOS  
**Teknologi:** Laravel 12 + Blade + SQLite + TablePlus

### Struktur Tim (4-5 Orang)
- **Project Manager:** [Nama]
- **Backend Developer 1:** [Nama]
- **Backend Developer 2:** [Nama] (opsional)
- **Frontend Developer 1:** [Nama]
- **Frontend Developer 2:** [Nama] (opsional)

---

## 🎯 Deskripsi Project

CoffPOS adalah sistem Point of Sale (POS) untuk coffee shop yang memudahkan kasir dalam mengelola transaksi penjualan, stok produk, manajemen menu, dan laporan penjualan. Sistem ini dilengkapi dengan dashboard admin yang komprehensif dan interface kasir yang user-friendly.

---

## 🗂️ Struktur Database

### Database Design (SQLite)

#### 1. Table: users
```sql
- id (Primary Key)
- name
- email (Unique)
- password
- role (enum: 'admin', 'cashier', 'manager')
- phone
- avatar (nullable)
- email_verified_at (nullable)
- remember_token
- created_at
- updated_at
```

#### 2. Table: categories
```sql
- id (Primary Key)
- name
- description (nullable)
- image (nullable)
- created_at
- updated_at
```

#### 3. Table: products
```sql
- id (Primary Key)
- category_id (Foreign Key -> categories.id)
- name
- description (nullable)
- price
- cost (harga modal)
- stock
- image
- is_available (boolean)
- created_at
- updated_at
```

#### 4. Table: customers
```sql
- id (Primary Key)
- name
- phone (Unique)
- email (nullable)
- address (nullable)
- points (loyalty points)
- created_at
- updated_at
```

#### 5. Table: transactions
```sql
- id (Primary Key)
- user_id (Foreign Key -> users.id) [kasir yang melayani]
- customer_id (Foreign Key -> customers.id, nullable)
- transaction_code (Unique, e.g., TRX-20250101-0001)
- subtotal
- discount (nullable)
- tax
- total
- payment_method (enum: 'cash', 'debit', 'credit', 'ewallet', 'qris')
- payment_amount
- change_amount
- status (enum: 'completed', 'cancelled')
- notes (nullable)
- transaction_date
- created_at
- updated_at
```

#### 6. Table: transaction_items
```sql
- id (Primary Key)
- transaction_id (Foreign Key -> transactions.id)
- product_id (Foreign Key -> products.id)
- product_name (snapshot nama produk saat transaksi)
- quantity
- price (snapshot harga saat transaksi)
- subtotal
- notes (nullable, catatan khusus untuk item ini)
- created_at
- updated_at
```

#### 7. Table: expenses
```sql
- id (Primary Key)
- user_id (Foreign Key -> users.id)
- category (enum: 'inventory', 'operational', 'salary', 'utilities', 'other')
- description
- amount
- receipt_image (nullable)
- expense_date
- created_at
- updated_at
```

### Relasi Database
1. **categories → products** (One to Many)
2. **users → transactions** (One to Many)
3. **customers → transactions** (One to Many)
4. **transactions → transaction_items** (One to Many)
5. **products → transaction_items** (One to Many)
6. **users → expenses** (One to Many)

---

## 🎨 Fitur Aplikasi

### A. Halaman Front-End (Landing Page)

#### 1. Home Page
- Hero section dengan tagline coffee shop
- Showcase menu populer
- Testimoni pelanggan
- Informasi lokasi dan jam operasional
- Call-to-action untuk order

#### 2. Menu Page
- Tampilan semua produk berdasarkan kategori
- Live search dan filter
- Detail produk dengan gambar
- Informasi harga dan ketersediaan

#### 3. About Us
- Sejarah coffee shop
- Tim barista
- Visi misi

#### 4. Contact
- Form kontak terintegrasi API (contoh: EmailJS atau SendGrid)
- Peta lokasi (Google Maps API)
- Informasi kontak

### B. Halaman Back-End (Dashboard Admin)

#### 1. Dashboard (Admin/Manager)
**Menampilkan minimal 1 data statistik:**
- Total penjualan hari ini
- Total transaksi hari ini
- Produk terlaris (Top 5)
- Revenue chart (grafik mingguan/bulanan)
- Stok produk menipis (alerts)
- Total pelanggan baru bulan ini

**Visualisasi:**
- Chart.js atau ApexCharts untuk grafik penjualan
- Card statistics dengan icon
- Recent transactions table

#### 2. Kasir POS Interface (Cashier Role)
- Pencarian produk (Live Search)
- Keranjang belanja interaktif
- Input customer (opsional)
- Pilihan metode pembayaran
- Kalkulasi otomatis (subtotal, pajak, total)
- Print receipt/nota
- Riwayat transaksi hari ini

#### 3. Manajemen Produk (CRUDS)
**Create:**
- Form tambah produk baru
- Upload gambar produk
- Validasi input (nama, harga, stok, kategori)

**Read:**
- Tabel daftar semua produk
- Pagination
- Live search
- Filter berdasarkan kategori dan ketersediaan

**Update:**
- Edit informasi produk
- Update stok
- Ganti gambar produk

**Delete:**
- Soft delete atau hard delete
- Konfirmasi sebelum hapus
- Validasi (tidak bisa hapus jika ada transaksi terkait)

**Search & Filter:**
- Live search berdasarkan nama produk
- Filter kategori
- Filter status ketersediaan
- Sort by (harga, stok, terbaru)

#### 4. Manajemen Kategori (CRUDS)
- CRUD kategori produk
- Upload gambar kategori
- Live search dan filter
- Validasi gambar (jpg, png, max 2MB)

#### 5. Manajemen Pelanggan (CRUDS)
- CRUD data pelanggan
- Sistem poin loyalitas
- Riwayat transaksi pelanggan
- Live search dan filter

#### 6. Manajemen Transaksi
- Lihat semua transaksi
- Detail transaksi (items, pembayaran)
- Filter berdasarkan tanggal, status, kasir
- Export laporan (PDF & Excel)
- Void/cancel transaksi

#### 7. Laporan Penjualan (PDF Reporting)
**Jenis Laporan:**
- Laporan Penjualan Harian (PDF)
- Laporan Penjualan Bulanan (PDF)
- Laporan Produk Terlaris (PDF)
- Laporan Stok Produk (PDF)
- Laporan Laba Rugi (PDF)

**Fitur:**
- Filter by date range
- Export to PDF (menggunakan DomPDF atau Snappy)
- Include chart/grafik dalam PDF
- Header dengan logo dan info toko

#### 8. Manajemen Pengeluaran (CRUDS)
- CRUD pengeluaran operasional
- Upload struk/kwitansi (gambar)
- Kategori pengeluaran
- Live search dan filter by tanggal

#### 9. Manajemen User (Admin Only)
- CRUD user/staff
- Set role (admin, cashier, manager)
- Upload foto profil
- Reset password

---

## 🔐 Authentication & Authorization

### Authentication
1. **Login**
   - Email dan password
   - Remember me checkbox
   - Redirect berdasarkan role

2. **Register**
   - Form registrasi user baru (hanya admin yang bisa register user)
   - Validasi email unique
   - Password confirmation

3. **Logout**
   - Invalidate session
   - Redirect ke halaman login

### Authorization (Middleware & Gates)
**Role-based Access Control:**

**Admin:**
- Full access semua fitur
- Manajemen user
- Laporan lengkap

**Manager:**
- Dashboard analytics
- Manajemen produk & kategori
- Laporan penjualan
- Tidak bisa manajemen user

**Cashier:**
- POS interface
- Tambah transaksi
- Lihat riwayat transaksi sendiri
- Update stock (terbatas)

---

## 🖼️ Manajemen Gambar

### Upload Image
- **Produk:** Upload gambar produk (JPG, PNG, max 2MB)
- **Kategori:** Upload gambar kategori
- **User:** Upload avatar
- **Pengeluaran:** Upload foto struk/kwitansi

### Validasi
```php
'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
```

### Storage
- Simpan di `storage/app/public/images/`
- Optimasi gambar (resize, compress)
- Generate thumbnail otomatis

### Delete
- Hapus file fisik saat data dihapus
- Validasi apakah file exists

---

## 🌐 Integrasi API Publik

### 1. Google Maps API (Wajib)
**Untuk halaman Contact:**
- Menampilkan lokasi coffee shop
- Marker interaktif
- Direction/routing

**Implementasi:**
```html
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
```

### 2. Open Weather API (Opsional)
**Untuk Dashboard:**
- Tampilkan cuaca hari ini
- Rekomendasi menu berdasarkan cuaca
- Widget cuaca di halaman depan

**Endpoint:**
```
https://api.openweathermap.org/data/2.5/weather?q=Bandung&appid=YOUR_API_KEY
```

### 3. Currency Exchange API (Opsional)
**Untuk multi-currency:**
- Konversi harga ke mata uang lain
- Update rate otomatis

**Contoh API:**
- ExchangeRate-API
- Fixer.io

### 4. WhatsApp Business API (Bonus)
**Untuk notifikasi:**
- Kirim receipt via WhatsApp
- Notifikasi stok habis ke admin

---

## 🎨 Frontend Framework & Design

### Teknologi
- **Laravel Blade:** Template engine
- **Tailwind CSS:** Utility-first CSS framework (DILARANG: Bootstrap, Materialize, Breeze, Filament)
- **Alpine.js:** JavaScript framework ringan untuk interaktivitas
- **Chart.js / ApexCharts:** Visualisasi data

### Design System
**Color Palette (Coffee Theme):**
- Primary: `#6F4E37` (Coffee Brown)
- Secondary: `#C9A87C` (Light Coffee)
- Accent: `#D4AF37` (Gold)
- Dark: `#3E2723` (Dark Chocolate)
- Light: `#F5E6D3` (Cream)

**Typography:**
- Heading: Poppins / Montserrat
- Body: Inter / Roboto

**Components:**
- Card untuk statistik
- Modal untuk form
- Toast notification
- Loading spinner
- Confirmation dialog

### Responsive Design
- Mobile-first approach
- Breakpoints: sm, md, lg, xl
- Touch-friendly untuk tablet (kasir)

---

## 🚀 Bonus Fitur (Nilai Tambahan)

### 1. Login dengan Google OAuth (Bonus A)
**Implementasi:**
- Laravel Socialite
- Google OAuth 2.0
- One-click login untuk customer

### 2. Payment Gateway Integration (Bonus C)
**Opsi:**
- Midtrans
- Xendit
- Doku

**Fitur:**
- Online payment untuk pre-order
- QRIS integration
- Payment notification

### 3. Deploy API ke Cloud (Bonus B)
**Platform:**
- Railway
- Heroku
- DigitalOcean
- AWS EC2

### 4. Fitur Tambahan
- **Real-time notification** (Pusher/Laravel Echo)
- **Kitchen Display System** (KDS untuk barista)
- **QR Code untuk menu** (Generate QR di tabel)
- **Loyalty program advanced** (gamification)
- **Multi-outlet support**
- **Inventory forecasting** (prediksi stok)
- **Voice order** (Web Speech API)
- **PWA** (Progressive Web App untuk kasir)

### 5. Workshop/Training
- Video tutorial penggunaan sistem
- User manual dalam PDF
- Training session untuk staff

---

## 📦 Tech Stack

### Backend
- **Framework:** Laravel 12
- **Database:** SQLite
- **ORM:** Eloquent
- **Authentication:** Laravel Sanctum/Breeze
- **PDF Generator:** DomPDF / Snappy
- **Image Processing:** Intervention Image

### Frontend
- **Template Engine:** Blade
- **CSS Framework:** Tailwind CSS
- **JavaScript:** Alpine.js + Vanilla JS
- **Charts:** Chart.js / ApexCharts
- **Icons:** Heroicons / Feather Icons

### Development Tools
- **Database Manager:** TablePlus
- **Version Control:** Git/GitHub
- **Package Manager:** Composer, NPM
- **Testing:** PHPUnit

---

## 📁 Struktur Folder Laravel

```
CoffPOS/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── CustomerController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── ExpenseController.php
│   │   │   │   └── ReportController.php
│   │   │   ├── Cashier/
│   │   │   │   ├── POSController.php
│   │   │   │   └── TransactionController.php
│   │   │   └── Frontend/
│   │   │       ├── HomeController.php
│   │   │       ├── MenuController.php
│   │   │       └── ContactController.php
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   └── CheckStockMiddleware.php
│   │   └── Requests/
│   │       ├── ProductRequest.php
│   │       ├── TransactionRequest.php
│   │       └── CustomerRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Customer.php
│   │   ├── Transaction.php
│   │   ├── TransactionItem.php
│   │   └── Expense.php
│   └── Services/
│       ├── TransactionService.php
│       ├── ReportService.php
│       └── ImageService.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   ├── UserSeeder.php
│   │   ├── CategorySeeder.php
│   │   └── ProductSeeder.php
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php (frontend)
│   │   │   ├── admin.blade.php (backend)
│   │   │   └── pos.blade.php (kasir)
│   │   ├── frontend/
│   │   │   ├── home.blade.php
│   │   │   ├── menu.blade.php
│   │   │   ├── about.blade.php
│   │   │   └── contact.blade.php
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── products/
│   │   │   ├── categories/
│   │   │   ├── customers/
│   │   │   ├── transactions/
│   │   │   ├── expenses/
│   │   │   ├── reports/
│   │   │   └── users/
│   │   ├── cashier/
│   │   │   ├── pos.blade.php
│   │   │   └── transactions.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   └── components/
│   │       ├── navbar.blade.php
│   │       ├── sidebar.blade.php
│   │       ├── card.blade.php
│   │       └── modal.blade.php
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── api.php (opsional)
├── public/
│   ├── images/
│   └── assets/
├── storage/
│   └── app/
│       └── public/
│           ├── products/
│           ├── categories/
│           ├── users/
│           └── receipts/
└── database.sqlite
```

---

## 🔄 Alur Kerja Sistem

### 1. Alur Transaksi POS
```
1. Kasir login → Akses halaman POS
2. Scan/pilih produk → Tambah ke keranjang
3. Input customer (opsional) → Cek poin loyalitas
4. Terapkan diskon (jika ada)
5. Pilih metode pembayaran → Input nominal pembayaran
6. Kalkulasi kembalian → Simpan transaksi
7. Kurangi stok otomatis → Update poin customer
8. Print receipt → Selesai
```

### 2. Alur Laporan
```
1. Admin akses menu Reports
2. Pilih jenis laporan → Set date range
3. Filter data → Generate preview
4. Export to PDF → Download/Print
```

### 3. Alur Manajemen Stok
```
1. Admin tambah produk baru → Set stok awal
2. Saat transaksi → Stok berkurang otomatis
3. Alert jika stok < 10 → Notifikasi ke admin
4. Admin restock → Update stok
5. Log riwayat stok → Tracking inventory
```

---

## 🧪 Testing & Validation

### Unit Testing
- Test model relationships
- Test calculation logic (total, tax, change)
- Test authentication & authorization

### Feature Testing
- Test CRUD operations
- Test transaction flow
- Test PDF generation
- Test API integration

### Validasi Form
**Produk:**
- Name: required, string, max:255
- Price: required, numeric, min:0
- Stock: required, integer, min:0
- Image: required, image, mimes:jpeg,png,jpg, max:2048

**Transaksi:**
- Payment method: required, in:cash,debit,credit,ewallet,qris
- Payment amount: required, numeric, min:total
- Items: required, array, min:1

**Customer:**
- Phone: required, unique, regex:/^[0-9]{10,13}$/
- Email: nullable, email, unique

---

## 🌐 Web Hosting & Deployment

### Requirements
- PHP 8.2+
- SQLite extension
- Composer
- Node.js & NPM

### Deployment Steps
1. Setup server (shared hosting/VPS)
2. Upload files via FTP/Git
3. Install dependencies: `composer install --optimize-autoloader --no-dev`
4. Setup environment: copy `.env.example` to `.env`
5. Generate key: `php artisan key:generate`
6. Run migrations: `php artisan migrate --seed`
7. Link storage: `php artisan storage:link`
8. Build assets: `npm run build`
9. Set permissions: `chmod -R 775 storage bootstrap/cache`
10. Configure domain & SSL

### Hosting Recommendation
- **Shared Hosting:** Niagahoster, Hostinger
- **VPS:** DigitalOcean, Vultr, Linode
- **Platform as a Service:** Railway, Heroku, Vercel (frontend only)

---

## 📊 Timeline Pengerjaan (Estimasi)

### Week 1-2: Setup & Database
- Setup project Laravel 12
- Design database (ERD)
- Create migrations & seeders
- Setup authentication

### Week 3-4: Backend Development
- Develop models & relationships
- Create controllers & services
- Implement CRUD operations
- API integration

### Week 5-6: Frontend Development
- Design UI/UX
- Implement Tailwind CSS
- Create Blade templates
- Integrate with backend

### Week 7-8: POS & Dashboard
- Develop POS interface
- Create dashboard analytics
- Implement charts & statistics
- Testing & debugging

### Week 9-10: Reporting & Polish
- PDF reporting system
- Image management
- Live search & filters
- Optimization & bug fixes

### Week 11-12: Deployment & Documentation
- Web hosting deployment
- User manual
- Code documentation
- Final testing

---

## 📝 Dokumentasi & Laporan

### Laporan Kerja (Menyusul)
**Struktur Laporan:**
1. Cover
2. Kata Pengantar
3. Daftar Isi
4. BAB I: Pendahuluan
   - Latar Belakang
   - Tujuan
   - Manfaat
5. BAB II: Landasan Teori
   - Laravel Framework
   - POS System
   - Database Design
6. BAB III: Analisis & Perancangan
   - Use Case Diagram
   - ERD (Entity Relationship Diagram)
   - Flowchart
   - Wireframe/Mockup
7. BAB IV: Implementasi
   - Struktur Database
   - Fitur-fitur Aplikasi
   - Screenshot Aplikasi
   - Code Snippet (penting)
8. BAB V: Testing & Deployment
   - Testing Scenario
   - Bug Report & Fixes
   - Deployment Process
9. BAB VI: Penutup
   - Kesimpulan
   - Saran
10. Daftar Pustaka
11. Lampiran (screenshot lengkap, code)

### Dokumentasi Teknis
- **README.md:** Cara instalasi & setup
- **API Documentation:** Jika ada REST API
- **User Manual:** Panduan penggunaan sistem
- **Code Comments:** Inline documentation

---

## 🎯 Checklist Fitur (Sesuai Spesifikasi)

### ✅ Wajib (A. Fitur Minimal)
- [ ] Halaman Front-end (Landing page, Menu, About, Contact)
- [ ] Dashboard Admin (dengan minimal 1 data statistik)
- [ ] Database dengan > 1 relasi (sudah: 6 relasi)
- [ ] Frontend Framework (Tailwind CSS - bukan Bootstrap/Materialize)
- [ ] CRUDS Complete:
  - [ ] Create (semua module)
  - [ ] Read (semua module)
  - [ ] Update (semua module)
  - [ ] Delete (semua module)
  - [ ] Search (Live Search)
  - [ ] Filter (multi-filter)
- [ ] Manajemen Gambar:
  - [ ] Upload gambar
  - [ ] Delete gambar
  - [ ] Validasi jenis gambar (jpeg, png, jpg)
- [ ] Authentication & Authorization:
  - [ ] Login
  - [ ] Register
  - [ ] Logout
  - [ ] Role-based access
- [ ] PDF Reporting (minimal 1 jenis laporan)
- [ ] API Publik Integration (Google Maps API)
- [ ] Web Hosting (Deploy online)

### 🌟 Bonus (B. Bonus Nilai)
- [ ] Login dengan Google OAuth API
- [ ] Deploy API ke Cloud (Railway/Heroku)
- [ ] Payment Gateway (Midtrans/Xendit)
- [ ] Workshop/Tutorial Video
- [ ] Fitur tambahan lainnya (PWA, Real-time, dll)

---

## 🔗 Repository & Version Control

### Git Workflow
**Branching Strategy:**
```
main (production)
├── develop (development)
    ├── feature/authentication
    ├── feature/products
    ├── feature/pos
    ├── feature/reports
    └── feature/api-integration
```

### Commit Convention
```
feat: Menambahkan fitur POS
fix: Memperbaiki bug pada transaksi
docs: Update README
style: Format code dengan prettier
refactor: Refactor TransactionService
test: Tambah unit test untuk Product model
```

### Minimal 10x Commit per Anggota
**Contoh Pembagian:**
- **Project Manager:** Setup project, config, docs, deployment (10-15 commits)
- **Backend Dev 1:** Models, migrations, API, services (15-20 commits)
- **Backend Dev 2:** Controllers, authentication, authorization (15-20 commits)
- **Frontend Dev 1:** Landing page, UI components (15-20 commits)
- **Frontend Dev 2:** Dashboard, POS interface, charts (15-20 commits)

---

## 🎓 Referensi & Resources

### Official Documentation
- Laravel 12: https://laravel.com/docs/12.x
- Tailwind CSS: https://tailwindcss.com/docs
- Alpine.js: https://alpinejs.dev/
- Chart.js: https://www.chartjs.org/

### Tutorial & Learning
- Laravel Daily (YouTube)
- Traversy Media (YouTube)
- Laracasts (premium)
- Laravel News

### Design Inspiration
- Dribbble (search: "POS system")
- Behance (search: "coffee shop dashboard")
- Awwwards (web design)

### API Documentation
- Google Maps: https://developers.google.com/maps/documentation
- OpenWeather: https://openweathermap.org/api
- Midtrans: https://docs.midtrans.com/

---

## 📞 Kontak & Support

**Mentor:** [Nama Mentor - Menyusul]  
**GitHub Repository:** [Link Repository]  
**Deadline Registrasi:** [Menyusul]  
**Deadline Pengumpulan:** 23:59 WIB - [Tanggal Menyusul]  
**Presentasi:** Minggu tenang sebelum UAS

---

## 🚀 Getting Started

### Installation Guide

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
Frontend: http://localhost:8000
Admin: http://localhost:8000/admin
Login credentials:
- Admin: admin@coffpos.com / password
- Cashier: cashier@coffpos.com / password
```

---

## 🎉 Kesimpulan

CoffPOS adalah sistem POS komprehensif yang memenuhi semua spesifikasi tugas besar Praktikum Web 2025/2026. Dengan fitur-fitur lengkap mulai dari manajemen produk, transaksi real-time, laporan PDF, hingga integrasi API publik, project ini siap menjadi solusi modern untuk coffee shop.

**Good luck & happy coding! ☕️💻**

---

*Dokumen ini dibuat sebagai panduan lengkap pengerjaan Tugas Besar. Silakan sesuaikan dengan kebutuhan tim dan feedback dari mentor.*