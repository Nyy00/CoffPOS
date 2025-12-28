# CoffPOS - Entity Relationship Diagram (ERD)
## Updated Database Schema Based on Actual Code Structure

## Database Schema Visualization

```
┌─────────────────────────┐
│        USERS            │
├─────────────────────────┤
│ PK  id                  │
│     name                │
│     email (unique)      │
│     password            │
│     role (enum)         │
│     phone               │
│     avatar              │
│     email_verified_at   │
│     remember_token      │
│     created_at          │
│     updated_at          │
└─────────────────────────┘
         │ 1
         │
         │ N
         ├──────────────────────────────────┐
         │                                  │
         ▼                                  ▼
┌─────────────────────────┐      ┌─────────────────────────┐
│     TRANSACTIONS        │      │       EXPENSES          │
├─────────────────────────┤      ├─────────────────────────┤
│ PK  id                  │      │ PK  id                  │
│ FK  user_id             │      │ FK  user_id             │
│ FK  customer_id         │      │     category (enum)     │
│     transaction_code    │      │     description         │
│     subtotal            │      │     amount              │
│     discount            │      │     receipt_image       │
│     tax                 │      │     expense_date        │
│     total               │      │     notes               │
│     subtotal_amount     │      │     created_at          │
│     discount_amount     │      │     updated_at          │
│     tax_amount          │      └─────────────────────────┘
│     total_amount        │
│     payment_method      │
│     payment_amount      │
│     change_amount       │
│     status (enum)       │
│     payment_status      │
│     notes               │
│     transaction_date    │
│     midtrans_trans_id   │
│     midtrans_pay_type   │
│     midtrans_snap_token │
│     midtrans_response   │
│     created_at          │
│     updated_at          │
└─────────────────────────┘
         │ 1
         │
         │ N
         ▼
┌─────────────────────────┐
│   TRANSACTION_ITEMS     │
├─────────────────────────┤
│ PK  id                  │
│ FK  transaction_id      │
│ FK  product_id          │
│     product_name        │
│     quantity            │
│     price               │
│     subtotal            │
│     notes               │
│     created_at          │
│     updated_at          │
└─────────────────────────┘
         │ N
         │
         │ 1
         ▼
┌─────────────────────────┐
│       PRODUCTS          │
├─────────────────────────┤
│ PK  id                  │
│ FK  category_id         │
│     code (unique)       │
│     name                │
│     description         │
│     price               │
│     cost                │
│     stock               │
│     min_stock           │
│     image               │
│     is_available        │
│     created_at          │
│     updated_at          │
└─────────────────────────┘
         │ N
         │
         │ 1
         ▼
┌─────────────────────────┐
│      CATEGORIES         │
├─────────────────────────┤
│ PK  id                  │
│     name                │
│     description         │
│     image               │
│     created_at          │
│     updated_at          │
└─────────────────────────┘


┌─────────────────────────┐
│      CUSTOMERS          │
├─────────────────────────┤
│ PK  id                  │
│     name                │
│     phone (unique)      │
│     email               │
│     address             │
│     points              │
│     created_at          │
│     updated_at          │
└─────────────────────────┘
         │ 1
         │
         │ N
         └──────────────────────────────────┐
                                            │
                                            ▼
                                   (TRANSACTIONS)
```

## Relationships Summary

### 1. Users → Transactions (One to Many)
- Satu user (kasir) dapat melakukan banyak transaksi
- Foreign Key: `transactions.user_id` → `users.id`
- On Delete: CASCADE

### 2. Users → Expenses (One to Many)
- Satu user dapat mencatat banyak pengeluaran
- Foreign Key: `expenses.user_id` → `users.id`
- On Delete: CASCADE

### 3. Categories → Products (One to Many)
- Satu kategori dapat memiliki banyak produk
- Foreign Key: `products.category_id` → `categories.id`
- On Delete: CASCADE

### 4. Customers → Transactions (One to Many)
- Satu customer dapat melakukan banyak transaksi
- Foreign Key: `transactions.customer_id` → `customers.id`
- On Delete: SET NULL (nullable)

### 5. Transactions → Transaction Items (One to Many)
- Satu transaksi dapat memiliki banyak item
- Foreign Key: `transaction_items.transaction_id` → `transactions.id`
- On Delete: CASCADE

### 6. Products → Transaction Items (One to Many)
- Satu produk dapat muncul di banyak transaksi
- Foreign Key: `transaction_items.product_id` → `products.id`
- On Delete: CASCADE

### 7. Users → Sessions (One to Many)
- Satu user dapat memiliki banyak session
- Foreign Key: `sessions.user_id` → `users.id`
- On Delete: CASCADE (nullable)

## Data Types & Constraints

### Enum Types
- **users.role**: 'admin', 'cashier', 'manager'
- **transactions.payment_method**: 'cash', 'debit', 'credit', 'ewallet', 'qris', 'digital'
- **transactions.status**: 'completed', 'cancelled'
- **transactions.payment_status**: 'pending', 'paid', 'failed', 'cancelled'
- **expenses.category**: 'inventory', 'operational', 'salary', 'utilities', 'other'

### Unique Constraints
- users.email
- customers.phone
- transactions.transaction_code
- products.code

### Decimal Fields (10,2)
- products.price, products.cost
- transactions.subtotal, discount, tax, total, payment_amount, change_amount
- transactions.subtotal_amount, discount_amount, tax_amount, total_amount
- transaction_items.price, subtotal
- expenses.amount

### Boolean Fields
- products.is_available

### JSON Fields
- transactions.midtrans_response

### Nullable Fields
- users.avatar, email_verified_at, phone
- categories.description, image
- products.description, image
- customers.email, address
- transactions.customer_id, discount, notes, payment_status
- transactions.subtotal_amount, discount_amount, tax_amount, total_amount
- transactions.midtrans_transaction_id, midtrans_payment_type, midtrans_snap_token, midtrans_response
- transaction_items.notes
- expenses.receipt_image, notes

### New Fields Added
**Products Table:**
- `code` (string, unique): Product code for identification
- `min_stock` (integer): Minimum stock level for alerts

**Transactions Table:**
- `payment_status` (enum): Payment processing status
- `subtotal_amount`, `discount_amount`, `tax_amount`, `total_amount`: New amount columns
- `midtrans_transaction_id`: Midtrans transaction reference
- `midtrans_payment_type`: Payment method from Midtrans
- `midtrans_snap_token`: Snap token for payment
- `midtrans_response` (JSON): Complete Midtrans response

**Expenses Table:**
- `notes` (text): Additional notes for expenses

## Business Rules

1. **Transaction Code Format**: TRX-YYYYMMDD-XXXX
2. **Stock Management**: Stock berkurang otomatis saat transaksi
3. **Loyalty Points**: Customer mendapat poin dari setiap transaksi
4. **Price Snapshot**: Harga produk disimpan di transaction_items untuk history
5. **Product Code**: Auto-generated dengan format PROD{id} jika tidak diisi
6. **Min Stock Alert**: Alert otomatis ketika stock <= min_stock
7. **Payment Integration**: Support Midtrans untuk digital payment
8. **Dual Amount Columns**: Backward compatibility dengan old/new amount columns
9. **Payment Status Tracking**: Separate status untuk payment processing
10. **Soft Delete**: Beberapa tabel menggunakan soft delete untuk audit
11. **Cascade Delete**: Relasi child akan terhapus saat parent dihapus

## Index Recommendations

```sql
-- Performance optimization indexes
CREATE INDEX idx_transactions_date ON transactions(transaction_date);
CREATE INDEX idx_transactions_user ON transactions(user_id);
CREATE INDEX idx_transactions_customer ON transactions(customer_id);
CREATE INDEX idx_transactions_payment_status ON transactions(payment_status);
CREATE INDEX idx_transactions_midtrans_id ON transactions(midtrans_transaction_id);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_available ON products(is_available);
CREATE INDEX idx_products_code ON products(code);
CREATE INDEX idx_products_stock_alert ON products(stock, min_stock);
CREATE INDEX idx_expenses_date ON expenses(expense_date);
CREATE INDEX idx_expenses_category ON expenses(category);
CREATE INDEX idx_transaction_items_transaction ON transaction_items(transaction_id);
CREATE INDEX idx_transaction_items_product ON transaction_items(product_id);
CREATE INDEX idx_customers_phone ON customers(phone);
CREATE INDEX idx_sessions_user ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);
```

## Database Schema Changes History

### Version 1.0 (Initial)
- Basic POS tables: users, categories, products, customers, transactions, transaction_items, expenses
- Laravel default tables: password_reset_tokens, sessions, cache, jobs

### Version 1.1 (December 19, 2025)
- Added `code` and `min_stock` to products table
- Made products.image nullable
- Added amount columns to transactions table
- Added `notes` to expenses table

### Version 1.2 (December 25, 2025)
- Added Midtrans integration fields to transactions
- Added 'digital' payment method
- Added payment_status enum
- Enhanced transaction tracking capabilities

---

**Total Tables**: 11 (7 main + 4 Laravel system tables)
**Total Relationships**: 7
**Database Type**: MySQL/SQLite (configurable)
**Laravel Version**: 11.x
**Last Updated**: December 26, 2025
