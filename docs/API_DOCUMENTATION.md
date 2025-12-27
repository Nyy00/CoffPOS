# 📚 CoffPOS API Documentation
## Comprehensive REST API Reference

---

## 📋 **Table of Contents**

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Base URLs & Headers](#base-urls--headers)
4. [Response Format](#response-format)
5. [Error Handling](#error-handling)
6. [Admin API Endpoints](#admin-api-endpoints)
7. [Cashier/POS API Endpoints](#cashierpos-api-endpoints)
8. [Frontend API Endpoints](#frontend-api-endpoints)
9. [Midtrans Integration](#midtrans-integration)
10. [Rate Limiting](#rate-limiting)
11. [Examples](#examples)

---

## 🎯 **Overview**

CoffPOS provides a comprehensive REST API for managing Point of Sale operations, inventory, customers, transactions, and reporting. The API is built with Laravel 11 and follows RESTful conventions with role-based access control.

### **API Features**
- **Role-based Authentication** (Admin, Manager, Cashier)
- **Real-time POS Operations**
- **Inventory Management**
- **Customer & Loyalty Management**
- **Transaction Processing**
- **Midtrans Payment Integration**
- **Comprehensive Reporting**
- **File Upload Support**

---

## 🔐 **Authentication**

### **Authentication Methods**
- **Session-based Authentication** (Web interface)
- **Sanctum Token Authentication** (API access)

### **User Roles**
- **Admin**: Full system access
- **Manager**: Business operations (no user management)
- **Cashier**: POS operations only

### **Login Endpoint**
```http
POST /login
Content-Type: application/json

{
    "email": "admin@coffpos.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@coffpos.com",
        "role": "admin"
    },
    "redirect": "/admin/dashboard"
}
```

---

## 🌐 **Base URLs & Headers**

### **Base URLs**
- **Production**: `https://your-domain.com`
- **Development**: `http://localhost:8000`

### **Required Headers**
```http
Content-Type: application/json
Accept: application/json
X-Requested-With: XMLHttpRequest
X-CSRF-TOKEN: {csrf_token}
```

### **Authentication Header (for API)**
```http
Authorization: Bearer {sanctum_token}
```

---

## 📊 **Response Format**

### **Success Response**
```json
{
    "success": true,
    "data": {},
    "message": "Operation completed successfully",
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    }
}
```

### **Error Response**
```json
{
    "success": false,
    "error": "Error message",
    "errors": {
        "field_name": ["Validation error message"]
    },
    "code": 400
}
```

---

## ⚠️ **Error Handling**

### **HTTP Status Codes**
- **200**: Success
- **201**: Created
- **400**: Bad Request
- **401**: Unauthorized
- **403**: Forbidden
- **404**: Not Found
- **422**: Validation Error
- **500**: Internal Server Error

### **Common Error Responses**

#### **Validation Error (422)**
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."],
        "email": ["The email must be a valid email address."]
    }
}
```

#### **Authentication Error (401)**
```json
{
    "success": false,
    "error": "Unauthenticated",
    "message": "Please login to access this resource"
}
```

#### **Authorization Error (403)**
```json
{
    "success": false,
    "error": "Forbidden",
    "message": "You don't have permission to access this resource"
}
```

---

## 👨‍💼 **Admin API Endpoints**

### **Dashboard & Analytics**

#### **Get Dashboard Statistics**
```http
GET /admin/dashboard/stats
```

**Response:**
```json
{
    "success": true,
    "data": {
        "today_revenue": 1250000,
        "today_transactions": 45,
        "today_items_sold": 120,
        "low_stock_count": 8,
        "new_customers": 12,
        "top_products": [
            {
                "id": 1,
                "name": "Espresso",
                "sales_count": 25,
                "revenue": 375000
            }
        ]
    }
}
```

#### **Get Chart Data**
```http
GET /admin/dashboard/charts/{type}
```

**Parameters:**
- `type`: `sales`, `products`, `customers`, `revenue`
- `period`: `daily`, `weekly`, `monthly`

---

### **Product Management**

#### **List Products**
```http
GET /admin/products
```

**Query Parameters:**
- `search`: Search by name or description
- `category_id`: Filter by category
- `is_available`: Filter by availability (true/false)
- `stock_filter`: `low`, `out`, `available`
- `sort_by`: `name`, `price`, `stock`, `created_at`
- `sort_order`: `asc`, `desc`
- `page`: Page number
- `per_page`: Items per page (default: 15)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "code": "PROD1",
            "name": "Espresso",
            "description": "Strong coffee shot",
            "price": 15000,
            "cost": 10500,
            "stock": 50,
            "min_stock": 10,
            "image": "products/espresso.jpg",
            "is_available": true,
            "category": {
                "id": 1,
                "name": "Coffee"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 45
    }
}
```

#### **Create Product**
```http
POST /admin/products
Content-Type: multipart/form-data
```

**Request Body:**
```json
{
    "category_id": 1,
    "name": "Cappuccino",
    "description": "Coffee with steamed milk",
    "price": 18000,
    "cost": 12600,
    "stock": 30,
    "min_stock": 5,
    "image": "file_upload",
    "is_available": true
}
```

#### **Update Product**
```http
PUT /admin/products/{id}
```

#### **Delete Product**
```http
DELETE /admin/products/{id}
```

#### **Search Products (API)**
```http
GET /admin/products/search/api?q={search_term}
```

#### **Update Product Stock**
```http
POST /admin/products/{id}/update-stock
```

**Request Body:**
```json
{
    "stock": 20,
    "action": "add|subtract|set"
}
```

---

### **Category Management**

#### **List Categories**
```http
GET /admin/categories
```

#### **Create Category**
```http
POST /admin/categories
```

**Request Body:**
```json
{
    "name": "Beverages",
    "description": "Hot and cold beverages",
    "image": "file_upload"
}
```

#### **Get Categories List (API)**
```http
GET /admin/categories/api/list
```

---

### **Customer Management**

#### **List Customers**
```http
GET /admin/customers
```

#### **Create Customer**
```http
POST /admin/customers
```

**Request Body:**
```json
{
    "name": "John Doe",
    "phone": "081234567890",
    "email": "john@example.com",
    "address": "Jakarta, Indonesia",
    "points": 0
}
```

#### **Search Customers (API)**
```http
GET /admin/customers/search/api?q={search_term}
```

#### **Get Customer Transaction History**
```http
GET /admin/customers/{id}/transactions
```

#### **Update Customer Points**
```http
POST /admin/customers/{id}/update-points
```

**Request Body:**
```json
{
    "points": 100,
    "action": "add|subtract|set",
    "reason": "Birthday bonus"
}
```

---

### **Transaction Management**

#### **List Transactions**
```http
GET /admin/transactions
```

**Query Parameters:**
- `search`: Search by transaction code
- `status`: Filter by status
- `payment_method`: Filter by payment method
- `date_from`: Start date (Y-m-d)
- `date_to`: End date (Y-m-d)
- `cashier_id`: Filter by cashier

#### **Get Transaction Details**
```http
GET /admin/transactions/{id}
```

#### **Void Transaction**
```http
POST /admin/transactions/{id}/void
```

**Request Body:**
```json
{
    "reason": "Customer request"
}
```

#### **Get Transaction Statistics**
```http
GET /admin/transactions/stats/api
```

---

### **Expense Management**

#### **List Expenses**
```http
GET /admin/expenses
```

#### **Create Expense**
```http
POST /admin/expenses
```

**Request Body:**
```json
{
    "category": "operational",
    "description": "Office supplies",
    "amount": 150000,
    "expense_date": "2025-12-26",
    "receipt_image": "file_upload",
    "notes": "Monthly office supplies purchase"
}
```

#### **Get Expense Statistics**
```http
GET /admin/expenses/stats/api
```

---

### **User Management (Admin Only)**

#### **List Users**
```http
GET /admin/users
```

#### **Create User**
```http
POST /admin/users
```

**Request Body:**
```json
{
    "name": "Jane Smith",
    "email": "jane@coffpos.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "cashier",
    "phone": "081234567891",
    "avatar": "file_upload"
}
```

#### **Reset User Password**
```http
POST /admin/users/{id}/reset-password
```

#### **Change User Role**
```http
POST /admin/users/{id}/change-role
```

**Request Body:**
```json
{
    "role": "manager"
}
```

---

### **Reports**

#### **Get Report Data**
```http
GET /admin/reports/data/{type}
```

**Types:** `daily`, `monthly`, `products`, `stock`, `profit-loss`, `customers`

**Query Parameters:**
- `date_from`: Start date
- `date_to`: End date
- `format`: `json`, `pdf`, `csv`

---

## 🏪 **Cashier/POS API Endpoints**

### **POS Operations**

#### **Get POS Interface Data**
```http
GET /cashier/pos
```

**Response:**
```json
{
    "success": true,
    "data": {
        "categories": [
            {
                "id": 1,
                "name": "Coffee",
                "products": [...]
            }
        ],
        "products": [...]
    }
}
```

#### **Search Products for POS**
```http
GET /cashier/pos/products/search?q={search_term}&category_id={id}
```

---

### **Cart Management**

#### **Add Item to Cart**
```http
POST /cashier/pos/cart/add
```

**Request Body:**
```json
{
    "product_id": 1,
    "quantity": 2
}
```

**Response:**
```json
{
    "success": true,
    "cart": {
        "1": {
            "product_id": 1,
            "name": "Espresso",
            "price": 15000,
            "quantity": 2,
            "image": "products/espresso.jpg"
        }
    },
    "total_items": 2
}
```

#### **Update Cart Item**
```http
POST /cashier/pos/cart/update
```

**Request Body:**
```json
{
    "product_id": 1,
    "quantity": 3
}
```

#### **Remove Item from Cart**
```http
POST /cashier/pos/cart/remove
```

**Request Body:**
```json
{
    "product_id": 1
}
```

#### **Clear Cart**
```http
POST /cashier/pos/cart/clear
```

#### **Get Cart Items**
```http
GET /cashier/pos/cart/items
```

#### **Get Cart Total**
```http
GET /cashier/pos/cart/total
```

---

### **Transaction Processing**

#### **Process Transaction**
```http
POST /cashier/pos/process-transaction
```

**Request Body:**
```json
{
    "customer_id": 1,
    "payment": {
        "method": "cash",
        "amount": 50000,
        "reference": null
    },
    "discount_amount": 0,
    "discount_percent": 0,
    "tax_percent": 10,
    "use_loyalty_points": false,
    "loyalty_points_used": 0,
    "notes": "Regular order"
}
```

**Response:**
```json
{
    "success": true,
    "transaction": {
        "id": 123,
        "transaction_code": "TRX-20251226-0001",
        "subtotal": 30000,
        "tax": 3000,
        "total": 33000,
        "payment_method": "cash",
        "status": "completed"
    },
    "receipt_data": {
        "store_info": {...},
        "transaction": {...},
        "items": [...]
    },
    "message": "Transaction processed successfully"
}
```

#### **Calculate Totals**
```http
POST /cashier/pos/calculate-totals
```

**Request Body:**
```json
{
    "discount_amount": 5000,
    "discount_percent": 0,
    "tax_percent": 10,
    "loyalty_points_used": 0,
    "customer_id": null
}
```

---

### **Hold Transactions**

#### **Hold Transaction**
```http
POST /cashier/pos/hold-transaction
```

**Request Body:**
```json
{
    "reason": "Customer stepped away",
    "customer_name": "John Doe",
    "discount_amount": 0,
    "notes": "Will return in 10 minutes"
}
```

#### **Get Held Transactions**
```http
GET /cashier/pos/held-transactions
```

#### **Resume Held Transaction**
```http
POST /cashier/pos/resume-transaction/{holdId}
```

#### **Delete Held Transaction**
```http
DELETE /cashier/pos/held-transaction/{holdId}
```

---

### **Customer Operations**

#### **Search Customers**
```http
GET /cashier/pos/customers/search?q={search_term}
```

#### **Quick Add Customer**
```http
POST /cashier/pos/customers/quick-add
```

**Request Body:**
```json
{
    "name": "New Customer",
    "phone": "081234567892",
    "email": "customer@example.com"
}
```

#### **Get Customer Loyalty Info**
```http
GET /cashier/pos/customer/{id}/loyalty
```

---

### **Receipt Management**

#### **Get Receipt Data**
```http
GET /cashier/pos/receipt-data/{transactionId}
```

#### **Print Receipt**
```http
GET /cashier/pos/receipt/{transactionId}
```

#### **Preview Receipt**
```http
GET /cashier/pos/receipt/{transactionId}/preview
```

---

## 💳 **Midtrans Integration**

### **Create Payment Token**
```http
POST /cashier/pos/midtrans/create-token
```

**Request Body:**
```json
{
    "customer_id": 1
}
```

**Response:**
```json
{
    "success": true,
    "snap_token": "66e4fa55-fdac-4ef9-91b5-733b97d1b862",
    "order_id": "ORDER-20251226-001",
    "total_amount": 33000,
    "client_key": "SB-Mid-client-xxx"
}
```

### **Process Midtrans Payment**
```http
POST /cashier/pos/midtrans/process-payment
```

**Request Body:**
```json
{
    "order_id": "ORDER-20251226-001",
    "transaction_status": "settlement",
    "transaction_id": "midtrans-txn-123",
    "payment_type": "qris",
    "customer_id": 1,
    "notes": "QRIS payment"
}
```

### **Midtrans Notification Webhook**
```http
POST /cashier/pos/midtrans/notification
```

**Request Body:** (Sent by Midtrans)
```json
{
    "transaction_time": "2025-12-26 10:30:00",
    "transaction_status": "settlement",
    "transaction_id": "midtrans-txn-123",
    "status_message": "midtrans payment notification",
    "status_code": "200",
    "signature_key": "signature",
    "payment_type": "qris",
    "order_id": "ORDER-20251226-001",
    "merchant_id": "merchant-id",
    "gross_amount": "33000.00",
    "fraud_status": "accept",
    "currency": "IDR"
}
```

### **Test Midtrans Configuration**
```http
GET /cashier/pos/midtrans/test
```

**Response:**
```json
{
    "server_key": "Set (SB-Mid-ser...)",
    "client_key": "Set (SB-Mid-cli...)",
    "is_production": false,
    "is_sanitized": true,
    "is_3ds": true
}
```

---

## 🌐 **Frontend API Endpoints**

### **Public Pages**

#### **Home Page**
```http
GET /
```

#### **Menu Page**
```http
GET /menu
```

#### **About Page**
```http
GET /about
```

#### **Contact Page**
```http
GET /contact
```

---

## 📁 **File Upload Endpoints**

### **Product Images**
```http
GET /storage/products/{filename}
```

### **Logo**
```http
GET /storage/logo.png
```

---

## ⚡ **Rate Limiting**

### **Default Limits**
- **API Requests**: 60 requests per minute per IP
- **Authentication**: 5 login attempts per minute per IP
- **File Uploads**: 10 uploads per minute per user

### **Rate Limit Headers**
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

---

## 📝 **Examples**

### **Complete POS Transaction Flow**

#### **1. Add Items to Cart**
```javascript
// Add first item
await fetch('/cashier/pos/cart/add', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        product_id: 1,
        quantity: 2
    })
});

// Add second item
await fetch('/cashier/pos/cart/add', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        product_id: 3,
        quantity: 1
    })
});
```

#### **2. Calculate Totals**
```javascript
const totalsResponse = await fetch('/cashier/pos/calculate-totals', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        discount_amount: 5000,
        tax_percent: 10,
        customer_id: 1
    })
});

const totals = await totalsResponse.json();
console.log(totals.totals); // { subtotal: 45000, tax: 4000, total: 44000 }
```

#### **3. Process Transaction**
```javascript
const transactionResponse = await fetch('/cashier/pos/process-transaction', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        customer_id: 1,
        payment: {
            method: 'cash',
            amount: 50000
        },
        discount_amount: 5000,
        tax_percent: 10,
        notes: 'Regular customer'
    })
});

const transaction = await transactionResponse.json();
if (transaction.success) {
    console.log('Transaction completed:', transaction.transaction.transaction_code);
    // Print receipt or show success message
}
```

### **Product Search with Filters**
```javascript
const searchResponse = await fetch('/admin/products/filter?' + new URLSearchParams({
    search: 'coffee',
    category_id: 1,
    is_available: true,
    price_min: 10000,
    price_max: 50000,
    sort_by: 'name',
    sort_order: 'asc',
    per_page: 20
}));

const products = await searchResponse.json();
console.log(products.data); // Array of filtered products
```

### **Customer Loyalty Management**
```javascript
// Get customer loyalty info
const loyaltyResponse = await fetch('/cashier/pos/customer/1/loyalty');
const loyalty = await loyaltyResponse.json();

console.log(`Customer has ${loyalty.customer.points} points`);
console.log(`Total spent: Rp ${loyalty.customer.total_spent}`);

// Update customer points
await fetch('/admin/customers/1/update-points', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        points: 100,
        action: 'add',
        reason: 'Purchase bonus'
    })
});
```

---

## 🔧 **SDK & Integration**

### **JavaScript SDK Example**
```javascript
class CoffPOSAPI {
    constructor(baseURL, csrfToken) {
        this.baseURL = baseURL;
        this.csrfToken = csrfToken;
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                ...options.headers
            },
            ...options
        };

        const response = await fetch(url, config);
        return await response.json();
    }

    // POS Methods
    async addToCart(productId, quantity) {
        return this.request('/cashier/pos/cart/add', {
            method: 'POST',
            body: JSON.stringify({ product_id: productId, quantity })
        });
    }

    async processTransaction(transactionData) {
        return this.request('/cashier/pos/process-transaction', {
            method: 'POST',
            body: JSON.stringify(transactionData)
        });
    }

    // Product Methods
    async searchProducts(query, filters = {}) {
        const params = new URLSearchParams({ q: query, ...filters });
        return this.request(`/admin/products/search/api?${params}`);
    }

    // Customer Methods
    async searchCustomers(query) {
        return this.request(`/cashier/pos/customers/search?q=${query}`);
    }
}

// Usage
const api = new CoffPOSAPI('http://localhost:8000', document.querySelector('meta[name="csrf-token"]').content);

// Add item to cart
await api.addToCart(1, 2);

// Search products
const products = await api.searchProducts('coffee', { category_id: 1 });
```

---

## 📊 **API Status & Health**

### **Health Check**
```http
GET /api/health
```

**Response:**
```json
{
    "status": "healthy",
    "timestamp": "2025-12-26T10:30:00Z",
    "version": "1.0.0",
    "database": "connected",
    "cache": "connected",
    "storage": "accessible"
}
```

---

## 🔍 **Debugging & Logging**

### **Debug Information**
- All API requests are logged in `storage/logs/laravel.log`
- Failed transactions are logged with full context
- Midtrans notifications are logged for debugging

### **Debug Endpoints** (Development Only)
```http
GET /cashier/pos/test-auth
```

**Response:**
```json
{
    "authenticated": true,
    "user": {
        "id": 1,
        "name": "Cashier User",
        "role": "cashier"
    },
    "role": "cashier"
}
```

---

## 📚 **Additional Resources**

### **Postman Collection**
Import the CoffPOS API collection: [Download Postman Collection](./postman/CoffPOS_API.postman_collection.json)

### **OpenAPI Specification**
View the complete OpenAPI 3.0 specification: [OpenAPI Spec](./openapi/coffpos-api.yaml)

### **Code Examples**
- [PHP SDK](./examples/php-sdk/)
- [JavaScript SDK](./examples/js-sdk/)
- [React Integration](./examples/react-integration/)

---

**Last Updated**: December 26, 2025  
**API Version**: 1.0.0  
**Laravel Version**: 11.x  
**Documentation Version**: 1.0.0

---

*For support and questions, please contact the development team or create an issue in the project repository.*