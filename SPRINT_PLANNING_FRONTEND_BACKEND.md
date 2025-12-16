# 🏃 CoffPOS - Sprint Planning (Frontend & Backend Split)

**Last Updated**: December 5, 2025  
**Sprint Duration**: 2 weeks per sprint  
**Team Size**: 4-5 people (Backend Developers + Frontend Developers)

---

## 📊 Sprint Overview

| Sprint | Duration | Goal | Backend Focus | Frontend Focus | Status |
|--------|----------|------|---------------|----------------|--------|
| Sprint 0 | Week 1-2 | Database & Authentication Setup | Database, Auth | Layouts, Public Pages | ✅ Done |
| Sprint 1 | Week 3-4 | Backend CRUD + Frontend Admin Pages | CRUD Controllers | Admin CRUD Pages | 📝 Planned |
| Sprint 2 | Week 5-6 | Frontend Enhancement + Search/Filter | Search API, Services | Search UI, Validation | 📝 Planned |
| Sprint 3 | Week 7-8 | POS System + Dashboard | POS Backend, Services | POS UI, Dashboard | 📝 Planned |
| Sprint 4 | Week 9-10 | Reports + Optimization | Report Service, PDF | Report Pages | 📝 Planned |
| Sprint 5 | Week 11-12 | Deployment + Documentation | Deployment | Final Polish | 📝 Planned |

---

## ✅ Sprint 0: Database & Authentication Setup (COMPLETED)

**Duration**: 2 weeks (Week 1-2)  
**Start Date**: November 18, 2025  
**End Date**: December 1, 2025  
**Completed Date**: December 5, 2025  
**Status**: ✅ **COMPLETED**

### 🎯 Sprint Goal
Setup database structure, models, authentication system, dan frontend foundation.

---

### ⚙️ BACKEND WORK (Completed)

#### Database & Models (42 SP)
- ✅ Create 7 database migrations
- ✅ Create 7 Eloquent models dengan relationships
- ✅ Create 4 database seeders
- ✅ Setup 6 database relationships

#### Authentication & Authorization (21 SP)
- ✅ Install Laravel Breeze
- ✅ Configure authentication routes
- ✅ Create RoleMiddleware
- ✅ Implement role-based redirect

#### Routes (5 SP)
- ✅ Setup frontend routes
- ✅ Setup auth routes
- ✅ Setup dashboard routes

---

### 🎨 FRONTEND WORK (Completed)

#### Layouts (8 SP)
- ✅ Create Frontend Layout
- ✅ Create Guest Layout
- ✅ Use App Layout dari Breeze

#### Public Pages (34 SP)
- ✅ Home Page
- ✅ Menu Page
- ✅ About Page
- ✅ Contact Page (dengan Google Maps)

#### Auth Pages (8 SP)
- ✅ Login Page (customized)
- ✅ Register Page (customized)

#### Design System (8 SP)
- ✅ Tailwind CSS configuration
- ✅ Custom color palette
- ✅ Custom fonts

#### Basic Dashboard (8 SP)
- ✅ Basic admin dashboard structure
- ✅ Statistics cards

---

### 📊 Sprint 0 Metrics

| Work Type | Story Points | Status |
|-----------|--------------|--------|
| Backend | 68 SP | ✅ Done |
| Frontend | 66 SP | ✅ Done |
| **Total** | **134 SP** | ✅ **100%** |

---

## 📅 Sprint 1: Backend CRUD + Frontend Admin Pages

**Duration**: 2 weeks (Week 3-4)  
**Start Date**: December 9, 2025  
**End Date**: December 22, 2025  
**Status**: 📝 **PLANNED**

### 🎯 Sprint Goal
Membangun semua backend CRUD operations dan frontend admin pages untuk Products, Categories, Customers, Users, dan Expenses.

---

### ⚙️ BACKEND WORK

#### Admin Controllers (57 SP)

**Task 1.1: ProductController** (13 SP)
- [ ] Create ProductController
- [ ] index() - list dengan search & filter
- [ ] create() - form data
- [ ] store() - simpan dengan image upload
- [ ] show() - detail dengan history
- [ ] edit() - form data
- [ ] update() - update dengan image
- [ ] destroy() - delete dengan validasi

**Task 1.2: CategoryController** (8 SP)
- [ ] Create CategoryController
- [ ] Full CRUD operations
- [ ] Image upload integration

**Task 1.3: CustomerController** (13 SP)
- [ ] Create CustomerController
- [ ] Full CRUD operations
- [ ] Transaction history method

**Task 1.4: UserController** (10 SP)
- [ ] Create UserController (Admin only)
- [ ] Full CRUD operations
- [ ] Role management
- [ ] Password reset method

**Task 1.5: ExpenseController** (13 SP)
- [ ] Create ExpenseController
- [ ] Full CRUD operations
- [ ] Receipt upload integration

---

#### Form Requests (8 SP)

**Task 1.6: Create Form Requests** (8 SP)
- [ ] ProductRequest (store & update rules)
- [ ] CategoryRequest (store & update rules)
- [ ] CustomerRequest (store & update rules)
- [ ] UserRequest (store & update rules)
- [ ] ExpenseRequest (store & update rules)

---

#### ImageService (8 SP)

**Task 1.7: ImageService Implementation** (8 SP)
- [ ] upload() method
- [ ] delete() method
- [ ] resize() method
- [ ] optimize() method
- [ ] validateImage() method

---

#### Routes Setup (3 SP)

**Task 1.8: Admin Routes** (3 SP)
- [ ] Resource routes untuk Products
- [ ] Resource routes untuk Categories
- [ ] Resource routes untuk Customers
- [ ] Resource routes untuk Users
- [ ] Resource routes untuk Expenses
- [ ] Apply middleware (auth, role)

---

### 🎨 FRONTEND WORK

#### Reusable Components (13 SP)

**Task 1.9: Create Components** (13 SP)
- [ ] Alert component (success, error, warning, info)
- [ ] Modal component (confirmation, form)
- [ ] Table component (sortable, pagination)
- [ ] Card component
- [ ] Badge component
- [ ] Button component variants

---

#### Products Management Pages (21 SP)

**Task 1.10: Products Pages** (21 SP)
- [ ] index.blade.php (table, search, filter, pagination)
- [ ] create.blade.php (form, image upload dengan preview)
- [ ] edit.blade.php (form, update image)
- [ ] show.blade.php (details, transaction history)

---

#### Categories Management Pages (13 SP)

**Task 1.11: Categories Pages** (13 SP)
- [ ] index.blade.php
- [ ] create.blade.php (form, image upload)
- [ ] edit.blade.php (form, update image)

---

#### Customers Management Pages (21 SP)

**Task 1.12: Customers Pages** (21 SP)
- [ ] index.blade.php (table, search, filter)
- [ ] create.blade.php (form)
- [ ] edit.blade.php (form)
- [ ] show.blade.php (details, transaction history, points)

---

#### Users & Expenses Management Pages (21 SP)

**Task 1.13: Users Pages** (10 SP)
- [ ] index.blade.php
- [ ] create.blade.php (form, role selection, avatar)
- [ ] edit.blade.php (form, change role, reset password)

**Task 1.14: Expenses Pages** (11 SP)
- [ ] index.blade.php (table, filters)
- [ ] create.blade.php (form, receipt upload, date picker)
- [ ] edit.blade.php (form, update receipt)
- [ ] show.blade.php (details, receipt display)

---

### 📊 Sprint 1 Metrics

| Work Type | Story Points | Assignee |
|-----------|--------------|----------|
| **Backend** | **76 SP** | Backend Dev 1 & 2 |
| **Frontend** | **89 SP** | Frontend Dev 1 & 2 |
| **Total** | **165 SP** | **4 Developers** |

**Estimated Days**: 10 working days  
**Daily Capacity**: ~16.5 SP per day

---

### ✅ Sprint 1 Definition of Done

**Backend:**
- [ ] Semua controllers created dan tested
- [ ] Form requests dengan validation rules
- [ ] ImageService functional
- [ ] Unit tests coverage minimal 70%
- [ ] Routes configured dengan middleware

**Frontend:**
- [ ] Semua CRUD pages responsive
- [ ] Image upload dengan preview working
- [ ] Form validation (client & server)
- [ ] Reusable components functional
- [ ] Success/error notifications working

---

## 📅 Sprint 2: Frontend Enhancement + Search/Filter

**Duration**: 2 weeks (Week 5-6)  
**Start Date**: December 23, 2025  
**End Date**: January 5, 2026  
**Status**: 📝 **PLANNED**

### 🎯 Sprint Goal
Meningkatkan UX dengan live search, filter, validation, notifications, dan enhance frontend interactions.

---

### ⚙️ BACKEND WORK

#### Search & Filter API Endpoints (18 SP)

**Task 2.1: Products Search API** (5 SP)
- [ ] searchProducts() endpoint
- [ ] filterProducts() endpoint
- [ ] Pagination support

**Task 2.2: Customers Search API** (5 SP)
- [ ] searchCustomers() endpoint
- [ ] filterCustomers() endpoint
- [ ] Pagination support

**Task 2.3: Transactions Search API** (5 SP)
- [ ] searchTransactions() endpoint
- [ ] filterTransactions() endpoint
- [ ] Date range filter

**Task 2.4: Expenses Search API** (3 SP)
- [ ] searchExpenses() endpoint
- [ ] filterExpenses() endpoint

---

### 🎨 FRONTEND WORK

#### Live Search Implementation (18 SP)

**Task 2.5: Products Live Search** (8 SP)
- [ ] Search bar dengan debounce
- [ ] Real-time results update
- [ ] Loading indicator
- [ ] Empty results handling

**Task 2.6: Customers Live Search** (5 SP)
- [ ] Search bar dengan debounce
- [ ] Real-time results update

**Task 2.7: Transactions Live Search** (5 SP)
- [ ] Search by transaction code
- [ ] Real-time results update

---

#### Filtering UI (16 SP)

**Task 2.8: Products Filtering** (8 SP)
- [ ] Category filter dropdown
- [ ] Availability filter toggle
- [ ] Price range filter
- [ ] Multiple filters combination
- [ ] Clear filters button

**Task 2.9: Transactions & Expenses Filtering** (8 SP)
- [ ] Date range picker
- [ ] Payment method filter
- [ ] Status filter
- [ ] Cashier filter

---

#### Sorting & Pagination (10 SP)

**Task 2.10: Sort Functionality** (5 SP)
- [ ] Sortable table headers
- [ ] Ascending/Descending toggle
- [ ] Visual indicators

**Task 2.11: Pagination** (5 SP)
- [ ] Pagination component
- [ ] Items per page selector
- [ ] Page navigation
- [ ] Show total records

---

#### Client-Side Validation (8 SP)

**Task 2.12: Form Validation** (8 SP)
- [ ] Real-time validation feedback
- [ ] Error messages display
- [ ] Validation rules matching backend
- [ ] Prevent submit jika ada errors
- [ ] Visual feedback (red border, icons)

---

#### Notifications & Interactions (10 SP)

**Task 2.13: Toast Notifications** (5 SP)
- [ ] Success, error, warning, info toasts
- [ ] Auto-dismiss (5 seconds)
- [ ] Manual close
- [ ] Multiple toasts support

**Task 2.14: Confirmation Dialogs** (5 SP)
- [ ] Delete confirmation modal
- [ ] Void transaction confirmation
- [ ] Custom messages
- [ ] Keyboard shortcuts

---

#### Image Preview & Date Picker (6 SP)

**Task 2.15: Image Preview** (3 SP)
- [ ] Preview sebelum upload
- [ ] Preview untuk update
- [ ] Remove image option

**Task 2.16: Date Picker** (3 SP)
- [ ] Date picker component
- [ ] Date range picker
- [ ] Calendar UI

---

### 📊 Sprint 2 Metrics

| Work Type | Story Points | Assignee |
|-----------|--------------|----------|
| **Backend** | **18 SP** | Backend Dev 1 |
| **Frontend** | **68 SP** | Frontend Dev 1 & 2 |
| **Total** | **86 SP** | **3 Developers** |

**Estimated Days**: 10 working days  
**Daily Capacity**: ~8.6 SP per day

---

### ✅ Sprint 2 Definition of Done

**Backend:**
- [ ] Semua search & filter API endpoints functional
- [ ] Pagination support untuk semua endpoints
- [ ] API response time < 300ms
- [ ] Error handling implemented

**Frontend:**
- [ ] Live search response time < 300ms
- [ ] All filters working dengan baik
- [ ] Sort functionality working
- [ ] Pagination working
- [ ] Client-side validation working
- [ ] Toast notifications working
- [ ] Confirmation dialogs working
- [ ] Image preview working
- [ ] Date picker working

---

## 📅 Sprint 3: POS System + Dashboard

**Duration**: 2 weeks (Week 7-8)  
**Start Date**: January 6, 2026  
**End Date**: January 19, 2026  
**Status**: 📝 **PLANNED**

### 🎯 Sprint Goal
Membangun POS system lengkap untuk kasir dan enhance dashboard dengan charts dan real-time statistics.

---

### ⚙️ BACKEND WORK

#### TransactionService (13 SP)

**Task 3.1: TransactionService** (13 SP)
- [ ] createTransaction() method
- [ ] calculateTotal() method
- [ ] updateStock() method
- [ ] generateTransactionCode() method
- [ ] voidTransaction() method
- [ ] applyLoyaltyPoints() method

---

#### POSController (13 SP)

**Task 3.2: POSController** (13 SP)
- [ ] index() - POS page data
- [ ] searchProducts() - API live search
- [ ] addToCart() - API add to cart
- [ ] updateCart() - API update cart
- [ ] removeFromCart() - API remove from cart
- [ ] processTransaction() - process payment
- [ ] printReceipt() - generate receipt data

---

#### TransactionController (Cashier) (8 SP)

**Task 3.3: Cashier TransactionController** (8 SP)
- [ ] index() - transactions hari ini
- [ ] show() - transaction detail
- [ ] reprintReceipt() - reprint receipt

---

#### DashboardController Enhancement (13 SP)

**Task 3.4: DashboardController** (13 SP)
- [ ] index() - statistics dengan charts data
- [ ] getStatistics() - API real-time stats
- [ ] getTopProducts() - produk terlaris
- [ ] getRecentTransactions() - transaksi terbaru
- [ ] getLowStockAlerts() - alert stok menipis
- [ ] getRevenueStats() - revenue statistics

---

#### Receipt Generation (13 SP)

**Task 3.5: Receipt System** (13 SP)
- [ ] Generate receipt data
- [ ] Receipt template/view
- [ ] Print receipt functionality
- [ ] Receipt format (transaction code, date, items, totals)

---

### 🎨 FRONTEND WORK

#### POS Interface (63 SP)

**Task 3.6: POS Product Search & Grid** (13 SP)
- [ ] Product search bar (live search)
- [ ] Product grid/list display
- [ ] Category filter
- [ ] Quick add to cart buttons
- [ ] Product cards

**Task 3.7: Shopping Cart Component** (21 SP)
- [ ] Cart sidebar/panel
- [ ] Item list dengan details
- [ ] Quantity controls (+ / -)
- [ ] Remove item button
- [ ] Item notes input
- [ ] Cart summary (subtotal, discount, tax, total)
- [ ] Clear cart button

**Task 3.8: Customer Selection** (8 SP)
- [ ] Search customer (live search)
- [ ] Quick add customer baru
- [ ] Display selected customer info
- [ ] Display loyalty points
- [ ] Apply loyalty discount option

**Task 3.9: Payment Processing UI** (21 SP)
- [ ] Payment method selection
- [ ] Discount input
- [ ] Tax calculation display
- [ ] Total display
- [ ] Payment amount input
- [ ] Change calculation (auto)
- [ ] Process payment button
- [ ] Hold transaction button

---

#### Dashboard Enhancement (38 SP)

**Task 3.10: Dashboard Charts** (21 SP)
- [ ] Install Chart.js
- [ ] Revenue chart (weekly/monthly)
- [ ] Sales trend chart
- [ ] Top products chart (bar chart)
- [ ] Payment methods distribution (pie chart)
- [ ] Charts responsive design

**Task 3.11: Enhanced Statistics Cards** (8 SP)
- [ ] Total revenue hari ini
- [ ] Total revenue bulan ini
- [ ] Total transactions hari ini
- [ ] Total customers
- [ ] Low stock alerts count
- [ ] Comparison dengan periode sebelumnya (percentage dengan arrows)

**Task 3.12: Recent Transactions & Alerts** (9 SP)
- [ ] Recent transactions table (last 10)
- [ ] Low stock alerts section
- [ ] Quick actions buttons
- [ ] Link ke detail pages

---

#### POS Transaction History (8 SP)

**Task 3.13: POS History** (8 SP)
- [ ] List transactions hari ini
- [ ] Transaction details modal
- [ ] Reprint receipt button
- [ ] Void transaction button (jika belum lama)

---

### 📊 Sprint 3 Metrics

| Work Type | Story Points | Assignee |
|-----------|--------------|----------|
| **Backend** | **60 SP** | Backend Dev 1 & 2 |
| **Frontend** | **109 SP** | Frontend Dev 1 & 2 |
| **Total** | **169 SP** | **4 Developers** |

**Estimated Days**: 10 working days  
**Daily Capacity**: ~16.9 SP per day

---

### ✅ Sprint 3 Definition of Done

**Backend:**
- [ ] TransactionService fully functional
- [ ] POSController API endpoints working
- [ ] DashboardController dengan statistics
- [ ] Receipt generation working
- [ ] Stock update otomatis verified
- [ ] Unit tests coverage minimal 70%

**Frontend:**
- [ ] POS interface fully functional
- [ ] Shopping cart working
- [ ] Payment processing working
- [ ] Receipt printing working
- [ ] Dashboard charts render dengan baik
- [ ] Statistics akurat dan real-time
- [ ] All responsive dan tested

---

## 📅 Sprint 4: Reports + Optimization

**Duration**: 2 weeks (Week 9-10)  
**Start Date**: January 20, 2026  
**End Date**: February 2, 2026  
**Status**: 📝 **PLANNED**

### 🎯 Sprint Goal
Membangun sistem reporting dengan PDF export dan melakukan code optimization serta bug fixes.

---

### ⚙️ BACKEND WORK

#### PDF Library Setup (3 SP)

**Task 4.1: Install PDF Library** (3 SP)
- [ ] Install DomPDF atau Snappy
- [ ] Configure PDF settings
- [ ] Create base PDF template

---

#### ReportService (21 SP)

**Task 4.2: ReportService** (21 SP)
- [ ] generateDailyReport() method
- [ ] generateMonthlyReport() method
- [ ] generateProductReport() method
- [ ] generateStockReport() method
- [ ] generateProfitLossReport() method
- [ ] exportToPDF() method

---

#### ReportController (21 SP)

**Task 4.3: ReportController** (21 SP)
- [ ] index() - menu reports
- [ ] daily() - laporan penjualan harian
- [ ] monthly() - laporan penjualan bulanan
- [ ] products() - laporan produk terlaris
- [ ] stock() - laporan stok produk
- [ ] profitLoss() - laporan laba rugi
- [ ] exportPDF() - export report ke PDF

---

#### TransactionController (Admin) (13 SP)

**Task 4.4: Admin TransactionController** (13 SP)
- [ ] index() - list dengan filter lengkap
- [ ] show() - detail transaction
- [ ] void() - void transaction
- [ ] export() - export transactions

---

#### Code Optimization (8 SP)

**Task 4.5: Code Optimization** (8 SP)
- [ ] Optimize database queries (N+1 problem)
- [ ] Add indexes untuk search columns
- [ ] Cache statistics
- [ ] Remove unused code

---

#### Bug Fixes (8 SP)

**Task 4.6: Bug Fixing** (8 SP)
- [ ] Fix reported bugs
- [ ] Fix edge cases
- [ ] Fix validation issues
- [ ] Fix performance issues

---

### 🎨 FRONTEND WORK

#### PDF Templates (8 SP)

**Task 4.7: PDF Templates** (8 SP)
- [ ] Header template (logo, company info)
- [ ] Footer template (page numbers, date)
- [ ] Table styles
- [ ] Chart/images support
- [ ] Responsive layout

---

#### Report Pages (21 SP)

**Task 4.8: Report Pages** (21 SP)
- [ ] Reports menu page
- [ ] Daily sales report page
- [ ] Monthly sales report page
- [ ] Products report page
- [ ] Stock report page
- [ ] Profit/Loss report page
- [ ] Date range selector
- [ ] Generate & Export buttons

---

#### Transaction Management Pages (13 SP)

**Task 4.9: Transaction Pages** (13 SP)
- [ ] Transactions index page dengan filters
- [ ] Transaction detail page
- [ ] Void transaction functionality
- [ ] Export buttons

---

#### UI Polish (8 SP)

**Task 4.10: UI/UX Polish** (8 SP)
- [ ] Consistent spacing
- [ ] Consistent colors
- [ ] Hover effects
- [ ] Loading states
- [ ] Empty states
- [ ] Error states
- [ ] Mobile responsiveness improvements

---

### 📊 Sprint 4 Metrics

| Work Type | Story Points | Assignee |
|-----------|--------------|----------|
| **Backend** | **74 SP** | Backend Dev 1 & 2 |
| **Frontend** | **50 SP** | Frontend Dev 1 & 2 |
| **Total** | **124 SP** | **4 Developers** |

**Estimated Days**: 10 working days  
**Daily Capacity**: ~12.4 SP per day

---

### ✅ Sprint 4 Definition of Done

**Backend:**
- [ ] Semua reports functional
- [ ] PDF export working untuk semua report types
- [ ] Code optimized (no N+1 queries)
- [ ] Performance improved
- [ ] All bugs fixed

**Frontend:**
- [ ] Report pages responsive
- [ ] PDF preview working
- [ ] Export buttons functional
- [ ] UI polished
- [ ] All states handled (loading, empty, error)

---

## 📅 Sprint 5: Deployment + Documentation

**Duration**: 2 weeks (Week 11-12)  
**Start Date**: February 3, 2026  
**End Date**: February 16, 2026  
**Status**: 📝 **PLANNED**

### 🎯 Sprint Goal
Deploy aplikasi ke production dan complete semua documentation untuk final presentation.

---

### ⚙️ BACKEND WORK

#### Hosting & Deployment (26 SP)

**Task 5.1: Choose & Setup Hosting** (8 SP)
- [ ] Research hosting providers
- [ ] Choose hosting provider
- [ ] Create account
- [ ] Setup server environment
- [ ] Configure PHP version (8.2+)
- [ ] Configure database

**Task 5.2: Domain & SSL** (5 SP)
- [ ] Register/configure domain
- [ ] Setup SSL certificate
- [ ] Configure DNS
- [ ] Test domain access

**Task 5.3: Deploy Application** (13 SP)
- [ ] Upload application files
- [ ] Setup .env file untuk production
- [ ] Run composer install
- [ ] Run npm run build
- [ ] Setup storage link
- [ ] Set permissions
- [ ] Run migrations
- [ ] Run seeders

---

#### Production Database (5 SP)

**Task 5.4: Production Database** (5 SP)
- [ ] Backup database
- [ ] Run migrations
- [ ] Seed initial data
- [ ] Verify database

---

#### Production Testing (8 SP)

**Task 5.5: Production Testing** (8 SP)
- [ ] Test semua features di production
- [ ] Test authentication
- [ ] Test CRUD operations
- [ ] Test POS system
- [ ] Test reports & PDF
- [ ] Performance testing
- [ ] Security testing

---

#### Code Documentation (8 SP)

**Task 5.6: Code Comments** (8 SP)
- [ ] Add inline comments untuk complex logic
- [ ] Document services methods
- [ ] Document controllers methods
- [ ] README.md update

---

### 🎨 FRONTEND WORK

#### Final Testing & Bug Fixes (8 SP)

**Task 5.7: Final Testing** (8 SP)
- [ ] Final testing semua features
- [ ] Fix last-minute bugs
- [ ] Cross-browser testing
- [ ] Mobile testing
- [ ] Performance check

---

#### Documentation (21 SP)

**Task 5.8: User Manual** (13 SP)
- [ ] Write user manual (PDF)
- [ ] Screenshots untuk setiap feature
- [ ] Step-by-step instructions
- [ ] Troubleshooting section
- [ ] FAQ section

**Task 5.9: Admin Guide** (8 SP)
- [ ] Write admin guide
- [ ] Role permissions explanation
- [ ] System configuration
- [ ] Maintenance procedures

---

#### Presentation Preparation (8 SP)

**Task 5.10: Presentation Prep** (8 SP)
- [ ] Create presentation slides
- [ ] Prepare demo
- [ ] Prepare Q&A answers
- [ ] Practice presentation

---

### 📊 Sprint 5 Metrics

| Work Type | Story Points | Assignee |
|-----------|--------------|----------|
| **Backend** | **47 SP** | Backend Dev 1 & 2 |
| **Frontend** | **45 SP** | Frontend Dev 1 & PM |
| **Total** | **92 SP** | **4-5 People** |

**Estimated Days**: 10 working days  
**Daily Capacity**: ~9.2 SP per day

---

### ✅ Sprint 5 Definition of Done

**Backend:**
- [ ] Application deployed ke production
- [ ] All features working di production
- [ ] Database migrated
- [ ] Code documented
- [ ] Performance acceptable

**Frontend:**
- [ ] All features tested di production
- [ ] Cross-browser compatible
- [ ] Mobile responsive verified
- [ ] Documentation complete
- [ ] Presentation ready

---

## 📊 Overall Sprint Summary

| Sprint | Backend SP | Frontend SP | Total SP | Status |
|--------|-----------|-------------|----------|--------|
| Sprint 0 | 68 | 66 | 134 | ✅ Done |
| Sprint 1 | 76 | 89 | 165 | 📝 Planned |
| Sprint 2 | 18 | 68 | 86 | 📝 Planned |
| Sprint 3 | 60 | 109 | 169 | 📝 Planned |
| Sprint 4 | 74 | 50 | 124 | 📝 Planned |
| Sprint 5 | 47 | 45 | 92 | 📝 Planned |
| **Total** | **343** | **427** | **770** | - |

**Average Backend per Sprint**: ~57 SP  
**Average Frontend per Sprint**: ~71 SP  
**Total Development Time**: 12 weeks (60 working days)

---

## 👥 Team Assignment Recommendations

### Backend Developers

**Backend Developer 1** (Focus: Controllers, Services, API)
- Sprint 1: ProductController, ExpenseController, ImageService
- Sprint 2: Search & Filter API endpoints
- Sprint 3: TransactionService, POSController
- Sprint 4: ReportService, ReportController, Optimization

**Backend Developer 2** (Focus: Models, Requests, Database)
- Sprint 1: CategoryController, CustomerController, UserController, Form Requests
- Sprint 3: DashboardController, Receipt Generation
- Sprint 4: TransactionController, Bug Fixes
- Sprint 5: Deployment, Database, Testing

### Frontend Developers

**Frontend Developer 1** (Focus: Admin Pages, Components, UX)
- Sprint 1: Products Pages, Customers Pages, Components
- Sprint 2: Search UI, Filtering UI, Validation
- Sprint 3: Dashboard Enhancement, Charts
- Sprint 4: Report Pages, UI Polish

**Frontend Developer 2** (Focus: POS, Forms, Interactions)
- Sprint 1: Categories Pages, Users/Expenses Pages
- Sprint 2: Notifications, Image Preview, Date Picker
- Sprint 3: POS Interface, Payment UI
- Sprint 4: Transaction Pages
- Sprint 5: Final Testing, Documentation

---

## 📝 Notes

### Dependencies

- **Sprint 1**: Backend controllers harus selesai sebelum frontend pages bisa diintegrasikan
- **Sprint 2**: Frontend enhancement bergantung pada Sprint 1 completion
- **Sprint 3**: POS backend harus selesai sebelum POS frontend
- **Sprint 4**: ReportService harus selesai sebelum report pages
- **Sprint 5**: Semua sprint sebelumnya harus selesai sebelum deployment

### Parallel Work Opportunities

- **Sprint 1**: Backend controllers dan frontend components bisa dikerjakan parallel
- **Sprint 2**: Search API dan Search UI bisa dikerjakan parallel dengan koordinasi
- **Sprint 3**: TransactionService dan POS UI bisa dikerjakan parallel setelah spec jelas

### Risks

- **Sprint 1**: Time constraint untuk complete semua CRUD
- **Sprint 3**: POS system complexity might need more time
- **Sprint 4**: PDF generation complexity
- **Sprint 5**: Hosting setup issues, deployment complications

### Mitigation

- Focus on HIGH priority tasks first
- Break down large tasks into smaller ones
- Regular code reviews untuk catch issues early
- Daily standups untuk identify blockers quickly
- Buffer time untuk unexpected issues

---

**Status**: 🟢 ON TRACK  
**Next Sprint**: Sprint 1 - Backend CRUD + Frontend Admin Pages  
**Sprint Start**: December 9, 2025

---

<p align="center">
<strong>Sprint Planning Complete (Frontend & Backend Split)</strong><br>
<em>Last Updated: December 5, 2025</em>
</p>

