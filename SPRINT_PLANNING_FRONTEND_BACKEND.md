# 🏃 CoffPOS - Sprint Planning (Frontend & Backend Split)

**Last Updated**: December 5, 2025  
**Sprint Duration**: 2 weeks per sprint  
**Team Size**: 4-5 people (Backend Developers + Frontend Developers)

---

## 📁 Project Folder Structure

### Backend Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   │   ├── ProductController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── UserController.php
│   │   │   ├── ExpenseController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── ReportController.php
│   │   │   └── DashboardController.php
│   │   ├── Cashier/        # Cashier controllers
│   │   │   ├── POSController.php
│   │   │   └── TransactionController.php
│   │   └── Frontend/       # Frontend controllers (done)
│   ├── Requests/           # Form Request validation
│   │   ├── ProductRequest.php
│   │   ├── CategoryRequest.php
│   │   ├── CustomerRequest.php
│   │   ├── UserRequest.php
│   │   └── ExpenseRequest.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Services/               # Business logic services
│   ├── ImageService.php
│   ├── TransactionService.php
│   └── ReportService.php
└── Models/                 # Eloquent models (done)
    ├── User.php
    ├── Product.php
    ├── Category.php
    ├── Customer.php
    ├── Transaction.php
    ├── TransactionItem.php
    └── Expense.php

routes/
└── web.php                 # Web routes

storage/
└── app/
    └── public/
        └── images/         # Uploaded images
            ├── products/
            ├── categories/
            ├── users/
            └── receipts/
```

### Frontend Structure
```
resources/
├── views/
│   ├── layouts/
│   │   ├── frontend.blade.php
│   │   ├── guest.blade.php
│   │   └── app.blade.php
│   ├── components/         # Reusable components
│   │   ├── alert.blade.php
│   │   ├── modal.blade.php
│   │   ├── table.blade.php
│   │   ├── card.blade.php
│   │   ├── badge.blade.php
│   │   ├── button.blade.php
│   │   ├── pagination.blade.php
│   │   ├── form/
│   │   │   ├── input.blade.php
│   │   │   ├── select.blade.php
│   │   │   ├── textarea.blade.php
│   │   │   ├── file-upload.blade.php
│   │   │   └── date-picker.blade.php
│   │   └── pos/
│   │       ├── shopping-cart.blade.php
│   │       └── payment-section.blade.php
│   ├── admin/              # Admin pages
│   │   ├── dashboard.blade.php
│   │   ├── products/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   ├── categories/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── customers/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   ├── users/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── expenses/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   ├── transactions/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   └── reports/
│   │       ├── index.blade.php
│   │       ├── daily.blade.php
│   │       ├── monthly.blade.php
│   │       ├── products.blade.php
│   │       ├── stock.blade.php
│   │       └── profit-loss.blade.php
│   ├── cashier/            # Cashier pages
│   │   └── pos.blade.php
│   ├── receipts/           # Receipt templates
│   │   └── transaction.blade.php
│   ├── reports/            # Report PDF templates
│   │   └── layouts/
│   │       └── pdf.blade.php
│   ├── frontend/           # Public pages (done)
│   │   ├── home.blade.php
│   │   ├── menu.blade.php
│   │   ├── about.blade.php
│   │   └── contact.blade.php
│   └── auth/               # Auth pages (done)
│       ├── login.blade.php
│       └── register.blade.php
├── js/
│   ├── admin/
│   │   ├── products-search.js
│   │   ├── customers-search.js
│   │   ├── transactions-search.js
│   │   └── dashboard-charts.js
│   ├── pos/
│   │   ├── products-search.js
│   │   ├── shopping-cart.js
│   │   └── payment.js
│   ├── components/
│   │   └── image-preview.js
│   └── receipt-print.js
└── css/
    ├── app.css
    ├── receipt-print.css
    └── reports-pdf.css
```

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
**Status**: �  **IN PROGRESS** (Backend 84% Complete)

### 🎯 Sprint Goal
Membangun semua backend CRUD operations dan frontend admin pages untuk Products, Categories, Customers, Users, dan Expenses.

---

### ⚙️ BACKEND WORK

#### Admin Controllers (57 SP)

**Task 1.1: ProductController** (13 SP) ✅ **COMPLETED**
- ✅ Create ProductController
  - **File**: `app/Http/Controllers/Admin/ProductController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**:
    - ✅ index() - list dengan search & filter
    - ✅ create() - form data
    - ✅ store() - simpan dengan image upload
    - ✅ show($id) - detail dengan history
    - ✅ edit($id) - form data
    - ✅ update($id) - update dengan image
    - ✅ destroy($id) - delete dengan validasi

**Task 1.2: CategoryController** (8 SP) ✅ **COMPLETED**
- ✅ Create CategoryController
  - **File**: `app/Http/Controllers/Admin/CategoryController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**: Full CRUD operations
  - ✅ Image upload integration

**Task 1.3: CustomerController** (13 SP) ✅ **COMPLETED**
- ✅ Create CustomerController
  - **File**: `app/Http/Controllers/Admin/CustomerController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**: Full CRUD operations
  - ✅ Transaction history method

**Task 1.4: UserController** (10 SP) ✅ **COMPLETED**
- ✅ Create UserController (Admin only)
  - **File**: `app/Http/Controllers/Admin/UserController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**: Full CRUD operations
  - ✅ Role management
  - ✅ Password reset method

**Task 1.5: ExpenseController** (13 SP) ✅ **COMPLETED**
- ✅ Create ExpenseController
  - **File**: `app/Http/Controllers/Admin/ExpenseController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**: Full CRUD operations
  - ✅ Receipt upload integration

---

#### Form Requests (8 SP)

**Task 1.6: Create Form Requests** (8 SP) ✅ **COMPLETED**
- ✅ ProductRequest (store & update rules)
  - **File**: `app/Http/Requests/ProductRequest.php`
  - **Namespace**: `App\Http\Requests`
  - **Methods**: rules() untuk store & update
- ✅ CategoryRequest (store & update rules)
  - **File**: `app/Http/Requests/CategoryRequest.php`
- ✅ CustomerRequest (store & update rules)
  - **File**: `app/Http/Requests/CustomerRequest.php`
- ✅ UserRequest (store & update rules)
  - **File**: `app/Http/Requests/UserRequest.php`
- ✅ ExpenseRequest (store & update rules)
  - **File**: `app/Http/Requests/ExpenseRequest.php`

---

#### ImageService (8 SP)

**Task 1.7: ImageService Implementation** (8 SP) ✅ **COMPLETED**
- ✅ Create ImageService
  - **File**: `app/Services/ImageService.php`
  - **Namespace**: `App\Services`
  - **Methods**:
    - ✅ upload($file, $folder) - upload gambar
    - ✅ delete($path) - hapus gambar dari storage
    - ✅ resize($file, $width, $height) - resize gambar
    - ✅ optimize($file) - optimasi gambar
    - ✅ validateImage($file) - validasi file gambar
  - **Storage Path**: `storage/app/public/images/{folder}/`

---

#### Routes Setup (3 SP)

**Task 1.8: Admin Routes** (3 SP) ✅ **COMPLETED**
- ✅ Update routes file
  - **File**: `routes/web.php`
  - ✅ Resource routes untuk Products (`/admin/products`)
  - ✅ Resource routes untuk Categories (`/admin/categories`)
  - ✅ Resource routes untuk Customers (`/admin/customers`)
  - ✅ Resource routes untuk Users (`/admin/users`)
  - ✅ Resource routes untuk Expenses (`/admin/expenses`)
  - ✅ Apply middleware (auth, role:admin,manager)

---

### 🎨 FRONTEND WORK

#### Reusable Components (13 SP)

**Task 1.9: Create Components** (13 SP)
- [ ] Alert component (success, error, warning, info)
  - **File**: `resources/views/components/alert.blade.php`
- [ ] Modal component (confirmation, form)
  - **File**: `resources/views/components/modal.blade.php`
- [ ] Table component (sortable, pagination)
  - **File**: `resources/views/components/table.blade.php`
- [ ] Card component
  - **File**: `resources/views/components/card.blade.php`
- [ ] Badge component
  - **File**: `resources/views/components/badge.blade.php`
- [ ] Button component variants
  - **File**: `resources/views/components/button.blade.php`

---

#### Products Management Pages (21 SP)

**Task 1.10: Products Pages** (21 SP)
- [ ] index.blade.php (table, search, filter, pagination)
  - **File**: `resources/views/admin/products/index.blade.php`
  - **Route**: `GET /admin/products`
- [ ] create.blade.php (form, image upload dengan preview)
  - **File**: `resources/views/admin/products/create.blade.php`
  - **Route**: `GET /admin/products/create`
- [ ] edit.blade.php (form, update image)
  - **File**: `resources/views/admin/products/edit.blade.php`
  - **Route**: `GET /admin/products/{id}/edit`
- [ ] show.blade.php (details, transaction history)
  - **File**: `resources/views/admin/products/show.blade.php`
  - **Route**: `GET /admin/products/{id}`

---

#### Categories Management Pages (13 SP)

**Task 1.11: Categories Pages** (13 SP)
- [ ] index.blade.php
  - **File**: `resources/views/admin/categories/index.blade.php`
  - **Route**: `GET /admin/categories`
- [ ] create.blade.php (form, image upload)
  - **File**: `resources/views/admin/categories/create.blade.php`
  - **Route**: `GET /admin/categories/create`
- [ ] edit.blade.php (form, update image)
  - **File**: `resources/views/admin/categories/edit.blade.php`
  - **Route**: `GET /admin/categories/{id}/edit`

---

#### Customers Management Pages (21 SP)

**Task 1.12: Customers Pages** (21 SP)
- [ ] index.blade.php (table, search, filter)
  - **File**: `resources/views/admin/customers/index.blade.php`
  - **Route**: `GET /admin/customers`
- [ ] create.blade.php (form)
  - **File**: `resources/views/admin/customers/create.blade.php`
  - **Route**: `GET /admin/customers/create`
- [ ] edit.blade.php (form)
  - **File**: `resources/views/admin/customers/edit.blade.php`
  - **Route**: `GET /admin/customers/{id}/edit`
- [ ] show.blade.php (details, transaction history, points)
  - **File**: `resources/views/admin/customers/show.blade.php`
  - **Route**: `GET /admin/customers/{id}`

---

#### Users & Expenses Management Pages (21 SP)

**Task 1.13: Users Pages** (10 SP)
- [ ] index.blade.php
  - **File**: `resources/views/admin/users/index.blade.php`
  - **Route**: `GET /admin/users`
- [ ] create.blade.php (form, role selection, avatar)
  - **File**: `resources/views/admin/users/create.blade.php`
  - **Route**: `GET /admin/users/create`
- [ ] edit.blade.php (form, change role, reset password)
  - **File**: `resources/views/admin/users/edit.blade.php`
  - **Route**: `GET /admin/users/{id}/edit`

**Task 1.14: Expenses Pages** (11 SP)
- [ ] index.blade.php (table, filters)
  - **File**: `resources/views/admin/expenses/index.blade.php`
  - **Route**: `GET /admin/expenses`
- [ ] create.blade.php (form, receipt upload, date picker)
  - **File**: `resources/views/admin/expenses/create.blade.php`
  - **Route**: `GET /admin/expenses/create`
- [ ] edit.blade.php (form, update receipt)
  - **File**: `resources/views/admin/expenses/edit.blade.php`
  - **Route**: `GET /admin/expenses/{id}/edit`
- [ ] show.blade.php (details, receipt display)
  - **File**: `resources/views/admin/expenses/show.blade.php`
  - **Route**: `GET /admin/expenses/{id}`

---

### 📊 Sprint 1 Metrics

| Work Type | Story Points | Status | Progress |
|-----------|--------------|--------|----------|
| **Backend** | **76 SP** | ✅ **COMPLETED** | **100%** |
| **Frontend** | **89 SP** | ❌ **NOT STARTED** | **0%** |
| **Total** | **165 SP** | 🔄 **IN PROGRESS** | **46%** |

**Backend Completed**: 76/76 SP (100%) ✅  
**Frontend Remaining**: 89 SP (0%) ❌  
**Overall Progress**: 76/165 SP (46%) 🔄

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
- [ ] Add methods to ProductController
  - **File**: `app/Http/Controllers/Admin/ProductController.php`
  - [ ] searchProducts() endpoint - `GET /api/admin/products/search?q={query}`
  - [ ] filterProducts() endpoint - `GET /api/admin/products/filter?category={id}&available={bool}`
  - [ ] Pagination support

**Task 2.2: Customers Search API** (5 SP)
- [ ] Add methods to CustomerController
  - **File**: `app/Http/Controllers/Admin/CustomerController.php`
  - [ ] searchCustomers() endpoint - `GET /api/admin/customers/search?q={query}`
  - [ ] filterCustomers() endpoint - `GET /api/admin/customers/filter?points_min={int}`
  - [ ] Pagination support

**Task 2.3: Transactions Search API** (5 SP)
- [ ] Add methods to TransactionController
  - **File**: `app/Http/Controllers/Admin/TransactionController.php`
  - [ ] searchTransactions() endpoint - `GET /api/admin/transactions/search?code={code}`
  - [ ] filterTransactions() endpoint - `GET /api/admin/transactions/filter?date_from={date}&date_to={date}`
  - [ ] Date range filter

**Task 2.4: Expenses Search API** (3 SP)
- [ ] Add methods to ExpenseController
  - **File**: `app/Http/Controllers/Admin/ExpenseController.php`
  - [ ] searchExpenses() endpoint - `GET /api/admin/expenses/search?q={query}`
  - [ ] filterExpenses() endpoint - `GET /api/admin/expenses/filter?category={category}`

---

### 🎨 FRONTEND WORK

#### Live Search Implementation (18 SP)

**Task 2.5: Products Live Search** (8 SP)
- [ ] Add search functionality to Products index page
  - **File**: `resources/views/admin/products/index.blade.php`
  - **JavaScript**: `resources/js/admin/products-search.js` (atau inline di blade)
  - [ ] Search bar dengan debounce
  - [ ] Real-time results update
  - [ ] Loading indicator
  - [ ] Empty results handling

**Task 2.6: Customers Live Search** (5 SP)
- [ ] Add search functionality to Customers index page
  - **File**: `resources/views/admin/customers/index.blade.php`
  - **JavaScript**: `resources/js/admin/customers-search.js`
  - [ ] Search bar dengan debounce
  - [ ] Real-time results update

**Task 2.7: Transactions Live Search** (5 SP)
- [ ] Add search functionality to Transactions index page
  - **File**: `resources/views/admin/transactions/index.blade.php`
  - **JavaScript**: `resources/js/admin/transactions-search.js`
  - [ ] Search by transaction code
  - [ ] Real-time results update

---

#### Filtering UI (16 SP)

**Task 2.8: Products Filtering** (8 SP)
- [ ] Add filter UI to Products index page
  - **File**: `resources/views/admin/products/index.blade.php`
  - **JavaScript**: `resources/js/admin/products-filter.js`
  - [ ] Category filter dropdown
  - [ ] Availability filter toggle
  - [ ] Price range filter (min-max inputs)
  - [ ] Multiple filters combination
  - [ ] Clear filters button

**Task 2.9: Transactions & Expenses Filtering** (8 SP)
- [ ] Add filter UI to Transactions index page
  - **File**: `resources/views/admin/transactions/index.blade.php`
  - **JavaScript**: `resources/js/admin/transactions-filter.js`
  - [ ] Date range picker (use date-picker component)
  - [ ] Payment method filter (dropdown)
  - [ ] Status filter (dropdown)
  - [ ] Cashier filter (dropdown)
- [ ] Add filter UI to Expenses index page
  - **File**: `resources/views/admin/expenses/index.blade.php`
  - **JavaScript**: `resources/js/admin/expenses-filter.js`

---

#### Sorting & Pagination (10 SP)

**Task 2.10: Sort Functionality** (5 SP)
- [ ] Update Table component untuk sortable headers
  - **File**: `resources/views/components/table.blade.php`
  - [ ] Sortable table headers
  - [ ] Ascending/Descending toggle
  - [ ] Visual indicators (arrows)
  - **JavaScript**: Add sort functionality

**Task 2.11: Pagination** (5 SP)
- [ ] Create Pagination component
  - **File**: `resources/views/components/pagination.blade.php`
  - [ ] Pagination component
  - [ ] Items per page selector (10, 25, 50, 100)
  - [ ] Page navigation (first, prev, next, last)
  - [ ] Show total records
  - **Usage**: Include di semua index pages

---

#### Client-Side Validation (8 SP)

**Task 2.12: Form Validation** (8 SP)
- [ ] Create Client-side validation
  - **JavaScript**: `resources/js/components/form-validation.js`
  - [ ] Real-time validation feedback
  - [ ] Error messages display (update form components)
  - [ ] Validation rules matching backend
  - [ ] Prevent submit jika ada errors
  - [ ] Visual feedback (red border, icons)
  - **Apply ke**: Semua form components (input, select, textarea, file-upload)

---

#### Notifications & Interactions (10 SP)

**Task 2.13: Toast Notifications** (5 SP)
- [ ] Create Toast Notification system
  - **Component**: `resources/views/components/toast-container.blade.php`
  - **JavaScript**: `resources/js/components/toast.js`
  - [ ] Success, error, warning, info toasts
  - [ ] Auto-dismiss (5 seconds)
  - [ ] Manual close button
  - [ ] Multiple toasts support
  - **Include di**: Layout file (`resources/views/layouts/app.blade.php`)

**Task 2.14: Confirmation Dialogs** (5 SP)
- [ ] Update Modal component untuk confirmation
  - **File**: `resources/views/components/modal.blade.php`
  - **JavaScript**: Update `resources/js/components/modal.js`
  - [ ] Delete confirmation modal (variant)
  - [ ] Void transaction confirmation (variant)
  - [ ] Custom messages support
  - [ ] Keyboard shortcuts (Esc to close)

---

#### Image Preview & Date Picker (6 SP)

**Task 2.15: Image Preview** (3 SP)
- [ ] Update File Upload component dengan preview
  - **File**: `resources/views/components/form/file-upload.blade.php`
  - **JavaScript**: `resources/js/components/image-preview.js`
  - [ ] Preview sebelum upload
  - [ ] Preview untuk update (show current image)
  - [ ] Remove image option

**Task 2.16: Date Picker** (3 SP)
- [ ] Create Date Picker component
  - **File**: `resources/views/components/form/date-picker.blade.php`
  - **JavaScript**: Install date picker library (flatpickr atau native HTML5)
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
- [ ] Create TransactionService
  - **File**: `app/Services/TransactionService.php`
  - **Namespace**: `App\Services`
  - **Methods**:
    - [ ] createTransaction($data) - buat transaksi baru
    - [ ] calculateTotal($items, $discount, $tax) - kalkulasi total
    - [ ] updateStock($items) - update stok produk otomatis
    - [ ] generateTransactionCode() - generate kode unik (TRX-YYYYMMDD-XXXX)
    - [ ] voidTransaction($id) - void transaksi
    - [ ] applyLoyaltyPoints($customerId, $total) - update poin customer

---

#### POSController (13 SP)

**Task 3.2: POSController** (13 SP)
- [ ] Create POSController
  - **File**: `app/Http/Controllers/Cashier/POSController.php`
  - **Namespace**: `App\Http\Controllers\Cashier`
  - **Methods**:
    - [ ] index() - POS page data - `GET /pos`
    - [ ] searchProducts() - API live search - `GET /api/pos/products/search`
    - [ ] addToCart() - API add to cart - `POST /api/pos/cart/add`
    - [ ] updateCart() - API update cart - `PUT /api/pos/cart/update`
    - [ ] removeFromCart() - API remove from cart - `DELETE /api/pos/cart/remove`
    - [ ] processTransaction() - process payment - `POST /api/pos/transaction/process`
    - [ ] printReceipt() - generate receipt data - `GET /api/pos/receipt/{id}`
  - **Routes**: Add di `routes/web.php`

---

#### TransactionController (Cashier) (8 SP)

**Task 3.3: Cashier TransactionController** (8 SP)
- [ ] Create/Update TransactionController (Cashier)
  - **File**: `app/Http/Controllers/Cashier/TransactionController.php`
  - **Namespace**: `App\Http\Controllers\Cashier`
  - **Methods**:
    - [ ] index() - transactions hari ini - `GET /cashier/transactions`
    - [ ] show($id) - transaction detail - `GET /cashier/transactions/{id}`
    - [ ] reprintReceipt($id) - reprint receipt - `GET /cashier/transactions/{id}/receipt`
  - **Routes**: Add di `routes/web.php`

---

#### DashboardController Enhancement (13 SP)

**Task 3.4: DashboardController** (13 SP)
- [ ] Create/Update DashboardController
  - **File**: `app/Http/Controllers/Admin/DashboardController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**:
    - [ ] index() - statistics dengan charts data - `GET /admin/dashboard`
    - [ ] getStatistics() - API real-time stats - `GET /api/admin/dashboard/statistics`
    - [ ] getTopProducts() - produk terlaris - `GET /api/admin/dashboard/top-products`
    - [ ] getRecentTransactions() - transaksi terbaru - `GET /api/admin/dashboard/recent-transactions`
    - [ ] getLowStockAlerts() - alert stok menipis - `GET /api/admin/dashboard/low-stock`
    - [ ] getRevenueStats() - revenue statistics - `GET /api/admin/dashboard/revenue`

---

#### Receipt Generation (13 SP)

**Task 3.5: Receipt System** (13 SP)
- [ ] Receipt generation di TransactionService
  - **File**: `app/Services/TransactionService.php`
  - [ ] generateReceiptData($transactionId) method
- [ ] Receipt view template
  - **File**: `resources/views/receipts/transaction.blade.php`
  - [ ] Receipt format (transaction code, date, items, totals, payment info)
- [ ] Print receipt functionality
  - **JavaScript**: `resources/js/receipt-print.js`
  - [ ] Print CSS: `resources/css/receipt-print.css`

---

### 🎨 FRONTEND WORK

#### POS Interface (63 SP)

**Task 3.6: POS Product Search & Grid** (13 SP)
- [ ] Update POS page
  - **File**: `resources/views/cashier/pos.blade.php`
  - **JavaScript**: `resources/js/pos/products-search.js`
  - [ ] Product search bar (live search)
  - [ ] Product grid/list display
  - [ ] Category filter (dropdown/tabs)
  - [ ] Quick add to cart buttons
  - [ ] Product cards dengan image, name, price

**Task 3.7: Shopping Cart Component** (21 SP)
- [ ] Create Shopping Cart component
  - **File**: `resources/views/components/pos/shopping-cart.blade.php`
  - **JavaScript**: `resources/js/pos/shopping-cart.js`
  - **Include di**: `resources/views/cashier/pos.blade.php`
  - [ ] Cart sidebar/panel (fixed right side)
  - [ ] Item list dengan details (name, quantity, price, subtotal)
  - [ ] Quantity controls (+ / - buttons)
  - [ ] Remove item button
  - [ ] Item notes input (optional)
  - [ ] Cart summary (subtotal, discount, tax, total)
  - [ ] Clear cart button

**Task 3.8: Customer Selection** (8 SP)
- [ ] Create Customer Selection component
  - **File**: `resources/views/components/pos/customer-selection.blade.php`
  - **JavaScript**: `resources/js/pos/customer-selection.js`
  - **Include di**: `resources/views/cashier/pos.blade.php`
  - [ ] Search customer (live search)
  - [ ] Quick add customer baru (modal/form)
  - [ ] Display selected customer info
  - [ ] Display loyalty points
  - [ ] Apply loyalty discount option (checkbox/toggle)

**Task 3.9: Payment Processing UI** (21 SP)
- [ ] Create Payment section component
  - **File**: `resources/views/components/pos/payment-section.blade.php`
  - **JavaScript**: `resources/js/pos/payment.js`
  - **Include di**: `resources/views/cashier/pos.blade.php`
  - [ ] Payment method selection (radio buttons: cash, debit, credit, e-wallet, QRIS)
  - [ ] Discount input (number input dengan % atau fixed amount)
  - [ ] Tax calculation display (auto calculate)
  - [ ] Total display (besar dan jelas)
  - [ ] Payment amount input
  - [ ] Change calculation (auto display)
  - [ ] Process payment button
  - [ ] Hold transaction button

---

#### Dashboard Enhancement (38 SP)

**Task 3.10: Dashboard Charts** (21 SP)
- [ ] Install Chart.js
  - **Package**: `npm install chart.js`
  - **File**: Update `package.json` dan run `npm install`
- [ ] Update Dashboard page
  - **File**: `resources/views/admin/dashboard.blade.php`
  - **JavaScript**: `resources/js/admin/dashboard-charts.js`
  - [ ] Revenue chart (weekly/monthly) - Line chart
  - [ ] Sales trend chart - Line chart
  - [ ] Top products chart (bar chart) - Bar chart
  - [ ] Payment methods distribution (pie chart) - Pie/Doughnut chart
  - [ ] Charts responsive design

**Task 3.11: Enhanced Statistics Cards** (8 SP)
- [ ] Update Dashboard statistics cards
  - **File**: `resources/views/admin/dashboard.blade.php`
  - **Component**: Create/Update `resources/views/components/dashboard/stat-card.blade.php`
  - [ ] Total revenue hari ini
  - [ ] Total revenue bulan ini
  - [ ] Total transactions hari ini
  - [ ] Total customers
  - [ ] Low stock alerts count
  - [ ] Comparison dengan periode sebelumnya (percentage dengan arrows)

**Task 3.12: Recent Transactions & Alerts** (9 SP)
- [ ] Update Dashboard dengan tables
  - **File**: `resources/views/admin/dashboard.blade.php`
  - [ ] Recent transactions table (last 10)
    - **Component**: `resources/views/components/dashboard/recent-transactions.blade.php`
  - [ ] Low stock alerts section
    - **Component**: `resources/views/components/dashboard/low-stock-alerts.blade.php`
  - [ ] Quick actions buttons
  - [ ] Link ke detail pages

---

#### POS Transaction History (8 SP)

**Task 3.13: POS History** (8 SP)
- [ ] Create POS Transaction History page/component
  - **File**: `resources/views/cashier/transactions/index.blade.php`
  - **Route**: `GET /cashier/transactions`
  - **JavaScript**: `resources/js/cashier/transactions.js`
  - [ ] List transactions hari ini (table)
  - [ ] Transaction details modal
    - **Component**: `resources/views/components/pos/transaction-detail-modal.blade.php`
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
- [ ] Install PDF library
  - **Package**: `composer require barryvdh/laravel-dompdf` (atau Snappy)
  - **Config**: `config/dompdf.php` (auto-generated)
  - **Service Provider**: Register di `config/app.php`
- [ ] Configure PDF settings
  - **File**: `config/dompdf.php`
- [ ] Create base PDF template
  - **File**: `resources/views/reports/layouts/pdf.blade.php`

---

#### ReportService (21 SP)

**Task 4.2: ReportService** (21 SP)
- [ ] Create ReportService
  - **File**: `app/Services/ReportService.php`
  - **Namespace**: `App\Services`
  - **Methods**:
    - [ ] generateDailyReport($date) - generate laporan harian
    - [ ] generateMonthlyReport($month, $year) - generate laporan bulanan
    - [ ] generateProductReport($dateRange) - laporan produk terlaris
    - [ ] generateStockReport() - laporan stok
    - [ ] generateProfitLossReport($dateRange) - laporan laba rugi
    - [ ] exportToPDF($report, $type) - export report ke PDF

---

#### ReportController (21 SP)

**Task 4.3: ReportController** (21 SP)
- [ ] Create ReportController
  - **File**: `app/Http/Controllers/Admin/ReportController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**:
    - [ ] index() - menu reports - `GET /admin/reports`
    - [ ] daily() - laporan penjualan harian - `GET /admin/reports/daily`
    - [ ] monthly() - laporan penjualan bulanan - `GET /admin/reports/monthly`
    - [ ] products() - laporan produk terlaris - `GET /admin/reports/products`
    - [ ] stock() - laporan stok produk - `GET /admin/reports/stock`
    - [ ] profitLoss() - laporan laba rugi - `GET /admin/reports/profit-loss`
    - [ ] exportPDF() - export report ke PDF - `GET /admin/reports/{type}/export`
  - **Routes**: Add di `routes/web.php`

---

#### TransactionController (Admin) (13 SP)

**Task 4.4: Admin TransactionController** (13 SP)
- [ ] Create/Update TransactionController (Admin)
  - **File**: `app/Http/Controllers/Admin/TransactionController.php`
  - **Namespace**: `App\Http\Controllers\Admin`
  - **Methods**:
    - [ ] index() - list dengan filter lengkap - `GET /admin/transactions`
    - [ ] show($id) - detail transaction - `GET /admin/transactions/{id}`
    - [ ] void($id) - void transaction - `POST /admin/transactions/{id}/void`
    - [ ] export() - export transactions - `GET /admin/transactions/export`
  - **Routes**: Add di `routes/web.php`

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
- [ ] Create PDF layout template
  - **File**: `resources/views/reports/layouts/pdf.blade.php`
  - [ ] Header template (logo, company info)
  - [ ] Footer template (page numbers, date)
- [ ] Create PDF styles
  - **File**: `resources/css/reports-pdf.css`
  - [ ] Table styles
  - [ ] Chart/images support
  - [ ] Print-optimized layout

---

#### Report Pages (21 SP)

**Task 4.8: Report Pages** (21 SP)
- [ ] Reports menu page
  - **File**: `resources/views/admin/reports/index.blade.php`
  - **Route**: `GET /admin/reports`
- [ ] Daily sales report page
  - **File**: `resources/views/admin/reports/daily.blade.php`
  - **Route**: `GET /admin/reports/daily`
- [ ] Monthly sales report page
  - **File**: `resources/views/admin/reports/monthly.blade.php`
  - **Route**: `GET /admin/reports/monthly`
- [ ] Products report page
  - **File**: `resources/views/admin/reports/products.blade.php`
  - **Route**: `GET /admin/reports/products`
- [ ] Stock report page
  - **File**: `resources/views/admin/reports/stock.blade.php`
  - **Route**: `GET /admin/reports/stock`
- [ ] Profit/Loss report page
  - **File**: `resources/views/admin/reports/profit-loss.blade.php`
  - **Route**: `GET /admin/reports/profit-loss`
- [ ] Date range selector (component)
  - **File**: `resources/views/components/reports/date-range-selector.blade.php`
- [ ] Generate & Export buttons (components)

---

#### Transaction Management Pages (13 SP)

**Task 4.9: Transaction Pages** (13 SP)
- [ ] Transactions index page dengan filters
  - **File**: `resources/views/admin/transactions/index.blade.php`
  - **Route**: `GET /admin/transactions`
- [ ] Transaction detail page
  - **File**: `resources/views/admin/transactions/show.blade.php`
  - **Route**: `GET /admin/transactions/{id}`
- [ ] Void transaction functionality (modal/confirmation)
  - **JavaScript**: `resources/js/admin/transactions-void.js`
- [ ] Export buttons
  - **Include di**: `resources/views/admin/transactions/index.blade.php`

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

