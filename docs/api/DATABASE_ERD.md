# CoffPOS - Entity Relationship Diagram (ERD)

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
│     total               │      │     created_at          │
│     payment_method      │      │     updated_at          │
│     payment_amount      │      └─────────────────────────┘
│     change_amount       │
│     status (enum)       │
│     notes               │
│     transaction_date    │
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
│     name                │
│     description         │
│     price               │
│     cost                │
│     stock               │
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

## Data Types & Constraints

### Enum Types
- **users.role**: 'admin', 'cashier', 'manager'
- **transactions.payment_method**: 'cash', 'debit', 'credit', 'ewallet', 'qris'
- **transactions.status**: 'completed', 'cancelled'
- **expenses.category**: 'inventory', 'operational', 'salary', 'utilities', 'other'

### Unique Constraints
- users.email
- customers.phone
- transactions.transaction_code

### Decimal Fields (10,2)
- products.price, products.cost
- transactions.subtotal, discount, tax, total, payment_amount, change_amount
- transaction_items.price, subtotal
- expenses.amount

### Boolean Fields
- products.is_available

### Nullable Fields
- users.avatar, email_verified_at
- categories.description, image
- products.description
- customers.email, address
- transactions.customer_id, discount, notes
- transaction_items.notes
- expenses.receipt_image

## Business Rules

1. **Transaction Code Format**: TRX-YYYYMMDD-XXXX
2. **Stock Management**: Stock berkurang otomatis saat transaksi
3. **Loyalty Points**: Customer mendapat poin dari setiap transaksi
4. **Price Snapshot**: Harga produk disimpan di transaction_items untuk history
5. **Soft Delete**: Beberapa tabel menggunakan soft delete untuk audit
6. **Cascade Delete**: Relasi child akan terhapus saat parent dihapus

## Index Recommendations

```sql
-- Performance optimization indexes
CREATE INDEX idx_transactions_date ON transactions(transaction_date);
CREATE INDEX idx_transactions_user ON transactions(user_id);
CREATE INDEX idx_transactions_customer ON transactions(customer_id);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_available ON products(is_available);
CREATE INDEX idx_expenses_date ON expenses(expense_date);
```

---

**Total Tables**: 7
**Total Relationships**: 6
**Database Type**: SQLite
