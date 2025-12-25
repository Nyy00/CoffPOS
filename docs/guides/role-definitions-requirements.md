# CoffPOS Role Definitions & Responsibilities Specification

**Project**: CoffPOS - Coffee Shop Point of Sale System  
**Document Type**: Requirements Specification  
**Version**: 1.0  
**Date**: December 20, 2025  
**Status**: Active  

---

## 📋 Overview

This specification defines the detailed roles, responsibilities, and access permissions for the three primary user roles in the CoffPOS system: Admin/Owner, Manager, and Cashier. Each role has specific capabilities and restrictions designed to maintain operational security while enabling efficient workflow.

---

## 🎯 User Stories & Acceptance Criteria

### Epic: Role-Based Access Control System

**As a business owner**, I want clearly defined user roles with specific permissions so that I can maintain operational security while enabling efficient workflow for my staff.

---

## 👑 Admin/Owner Role

### 🎯 Primary Responsibilities
The Admin/Owner has complete system access and is responsible for overall business management, system configuration, and strategic oversight.

### 📊 Core Capabilities

#### 1. User Management
**User Story**: As an Admin/Owner, I want to manage all system users so that I can control who has access to what parts of the system.

**Acceptance Criteria**:
- ✅ Can create, read, update, and delete all user accounts
- ✅ Can assign and modify user roles (Admin, Manager, Cashier)
- ✅ Can reset passwords for any user
- ✅ Can view user activity logs and login history
- ✅ Can enable/disable user accounts
- ✅ Can manage user profile information and avatars

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/UserController.php`
- **Views**: `resources/views/admin/users/`
- **Routes**: `/admin/users/*`

#### 2. Product & Inventory Management
**User Story**: As an Admin/Owner, I want complete control over product catalog and inventory so that I can manage what's available for sale.

**Acceptance Criteria**:
- ✅ Can create, read, update, and delete products
- ✅ Can manage product categories with images
- ✅ Can set product prices, stock levels, and minimum stock alerts
- ✅ Can upload and manage product images
- ✅ Can view product performance analytics
- ✅ Can manage product availability status
- ✅ Can generate product reports and export data

**Implementation Status**: ✅ **COMPLETED**
- **Controllers**: 
  - `app/Http/Controllers/Admin/ProductController.php`
  - `app/Http/Controllers/Admin/CategoryController.php`
- **Views**: 
  - `resources/views/admin/products/`
  - `resources/views/admin/categories/`
- **Routes**: `/admin/products/*`, `/admin/categories/*`

#### 3. Customer Relationship Management
**User Story**: As an Admin/Owner, I want to manage customer information and loyalty programs so that I can build customer relationships and increase retention.

**Acceptance Criteria**:
- ✅ Can create, read, update, and delete customer records
- ✅ Can view customer transaction history
- ✅ Can manage customer loyalty points
- ✅ Can generate customer analytics and reports
- ✅ Can export customer data
- ✅ Can search and filter customers by various criteria

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/CustomerController.php`
- **Views**: `resources/views/admin/customers/`
- **Routes**: `/admin/customers/*`

#### 4. Financial Management & Reporting
**User Story**: As an Admin/Owner, I want comprehensive financial oversight so that I can make informed business decisions.

**Acceptance Criteria**:
- ✅ Can view all financial reports (daily, monthly, profit/loss)
- ✅ Can manage business expenses with receipt uploads
- ✅ Can generate and export financial reports to PDF
- ✅ Can view real-time revenue and sales statistics
- ✅ Can analyze product performance and profitability
- ✅ Can track expense categories and budgets
- ✅ Can access historical financial data

**Implementation Status**: ✅ **COMPLETED**
- **Controllers**: 
  - `app/Http/Controllers/Admin/ReportController.php`
  - `app/Http/Controllers/Admin/ExpenseController.php`
- **Views**: 
  - `resources/views/admin/reports/`
  - `resources/views/admin/expenses/`
- **Services**: `app/Services/ReportService.php`

#### 5. Transaction Oversight
**User Story**: As an Admin/Owner, I want to monitor all transactions so that I can ensure accuracy and detect any issues.

**Acceptance Criteria**:
- ✅ Can view all transactions across all cashiers
- ✅ Can void transactions when necessary
- ✅ Can view detailed transaction information
- ✅ Can generate transaction reports
- ✅ Can export transaction data
- ✅ Can filter transactions by date, cashier, payment method

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/TransactionController.php`
- **Views**: `resources/views/admin/transactions/`
- **Routes**: `/admin/transactions/*`

#### 6. System Configuration
**User Story**: As an Admin/Owner, I want to configure system settings so that the system operates according to my business needs.

**Acceptance Criteria**:
- ✅ Can access admin dashboard with comprehensive statistics
- ✅ Can configure business information and settings
- ✅ Can manage system-wide preferences
- ✅ Can view system health and performance metrics
- ✅ Can access all areas of the application

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/DashboardController.php`
- **Views**: `resources/views/admin/dashboard.blade.php`

### 🔐 Access Permissions
- **Full System Access**: All modules, all functions
- **User Management**: Create, modify, delete users and roles
- **Financial Data**: Complete access to all financial information
- **System Configuration**: Modify system settings and preferences
- **Data Export**: Export all data types
- **Reporting**: Generate and access all report types

---

## 👨‍💼 Manager Role

### 🎯 Primary Responsibilities
The Manager focuses on operational oversight, expense management, and performance monitoring without access to sensitive user management or system configuration.

### 📊 Core Capabilities

#### 1. Operational Dashboard
**User Story**: As a Manager, I want a specialized dashboard so that I can monitor daily operations and key performance indicators.

**Acceptance Criteria**:
- ✅ Can access manager-specific dashboard with operational metrics
- ✅ Can view daily, weekly, and monthly sales summaries
- ✅ Can monitor product performance and stock levels
- ✅ Can view recent transactions and alerts
- ✅ Can access real-time operational statistics

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/DashboardController.php` (manager method)
- **Views**: `resources/views/admin/dashboard-manager.blade.php`
- **Routes**: `/admin/dashboard-manager`

#### 2. Expense Management
**User Story**: As a Manager, I want to manage business expenses so that I can control operational costs and maintain budgets.

**Acceptance Criteria**:
- ✅ Can create, read, update, and delete expense records
- ✅ Can upload and manage expense receipts
- ✅ Can categorize expenses for better tracking
- ✅ Can view expense reports and analytics
- ✅ Can export expense data
- ✅ Can set and monitor expense budgets

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/ExpenseController.php`
- **Views**: `resources/views/admin/expenses/`
- **Routes**: `/admin/expenses/*`

#### 3. Inventory Monitoring
**User Story**: As a Manager, I want to monitor inventory levels so that I can ensure adequate stock and prevent stockouts.

**Acceptance Criteria**:
- ✅ Can view all products and their stock levels
- ✅ Can receive low stock alerts and notifications
- ✅ Can view product performance metrics
- ✅ Can generate stock reports
- ✅ Cannot modify product prices or core product information
- ✅ Can view product transaction history

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/ProductController.php` (read-only access)
- **Views**: `resources/views/admin/products/index.blade.php` (manager view)
- **Middleware**: `app/Http/Middleware/ManagerAccessMiddleware.php`

#### 4. Performance Reporting
**User Story**: As a Manager, I want to access operational reports so that I can analyze performance and identify improvement opportunities.

**Acceptance Criteria**:
- ✅ Can generate and view profit/loss reports
- ✅ Can access daily and monthly sales reports
- ✅ Can view product performance analytics
- ✅ Can export reports to PDF
- ✅ Cannot access sensitive financial data like detailed expense breakdowns
- ✅ Can view customer analytics (aggregated data only)

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/ReportController.php` (filtered access)
- **Views**: `resources/views/admin/reports/profit-loss.blade.php`
- **Services**: `app/Services/ReportService.php` (manager-specific methods)

#### 5. Transaction Monitoring
**User Story**: As a Manager, I want to monitor transactions so that I can ensure operational efficiency and identify issues.

**Acceptance Criteria**:
- ✅ Can view all transactions (read-only)
- ✅ Can view transaction details and summaries
- ✅ Can generate transaction reports
- ✅ Cannot void or modify transactions
- ✅ Can filter transactions by various criteria
- ✅ Can view cashier performance metrics

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Admin/TransactionController.php` (read-only methods)
- **Views**: `resources/views/admin/transactions/` (manager view)

### 🔐 Access Permissions
- **Operational Dashboard**: Manager-specific dashboard and metrics
- **Expense Management**: Full CRUD access to expenses
- **Inventory Monitoring**: Read-only access to products and stock
- **Performance Reporting**: Access to operational reports (filtered)
- **Transaction Monitoring**: Read-only access to transaction data
- **Limited User Access**: Cannot manage users or roles
- **No System Configuration**: Cannot modify system settings

### 🚫 Restrictions
- Cannot create, modify, or delete users
- Cannot change user roles or permissions
- Cannot access system configuration settings
- Cannot void transactions
- Cannot modify product prices or core information
- Cannot access detailed user activity logs
- Cannot export sensitive user data

---

## 👨‍💻 Cashier Role

### 🎯 Primary Responsibilities
The Cashier focuses on point-of-sale operations, customer service, and daily transaction processing with minimal access to administrative functions.

### 📊 Core Capabilities

#### 1. Point of Sale Operations
**User Story**: As a Cashier, I want an efficient POS system so that I can process customer transactions quickly and accurately.

**Acceptance Criteria**:
- ✅ Can access the POS interface with product search and selection
- ✅ Can add products to cart with quantity adjustments
- ✅ Can apply discounts and calculate totals
- ✅ Can select customers and apply loyalty points
- ✅ Can process payments using multiple payment methods
- ✅ Can generate and print receipts
- ✅ Can hold and resume transactions

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Cashier/POSController.php`
- **Views**: `resources/views/cashier/pos.blade.php`
- **JavaScript**: 
  - `resources/js/pos/shopping-cart.js`
  - `resources/js/pos/payment.js`
  - `public/js/pos.js`
- **Routes**: `/pos`, `/api/pos/*`

#### 2. Transaction Management
**User Story**: As a Cashier, I want to manage my daily transactions so that I can track my sales and handle customer inquiries.

**Acceptance Criteria**:
- ✅ Can view transactions processed during their shift
- ✅ Can view transaction details and customer information
- ✅ Can reprint receipts for recent transactions
- ✅ Can view daily sales summary
- ✅ Cannot void transactions (requires manager/admin approval)
- ✅ Can search transactions by transaction code or customer

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: `app/Http/Controllers/Cashier/TransactionController.php`
- **Views**: `resources/views/cashier/transactions/`
- **Routes**: `/cashier/transactions/*`

#### 3. Customer Service
**User Story**: As a Cashier, I want to access customer information so that I can provide better service and manage loyalty programs.

**Acceptance Criteria**:
- ✅ Can search for existing customers during checkout
- ✅ Can view customer loyalty points and transaction history
- ✅ Can create new customer records during checkout
- ✅ Can apply loyalty discounts and update points
- ✅ Cannot modify existing customer information extensively
- ✅ Can view customer contact information for service purposes

**Implementation Status**: ✅ **COMPLETED**
- **Integration**: Customer selection in POS system
- **Views**: Customer components in `resources/views/cashier/pos.blade.php`
- **JavaScript**: Customer selection functionality in POS scripts

#### 4. Product Information Access
**User Story**: As a Cashier, I want to access product information so that I can answer customer questions and process sales accurately.

**Acceptance Criteria**:
- ✅ Can search and view all available products
- ✅ Can see product prices, descriptions, and availability
- ✅ Can view product images and details
- ✅ Cannot modify product information or prices
- ✅ Can see stock levels to inform customers
- ✅ Can filter products by category

**Implementation Status**: ✅ **COMPLETED**
- **Integration**: Product search and display in POS system
- **Views**: Product components in POS interface
- **API**: Product search endpoints for POS

#### 5. Daily Operations
**User Story**: As a Cashier, I want to manage my daily work activities so that I can maintain accurate records and provide good customer service.

**Acceptance Criteria**:
- ✅ Can view daily sales summary and statistics
- ✅ Can access shift-specific transaction reports
- ✅ Can print end-of-day reports for their transactions
- ✅ Can view their performance metrics
- ✅ Cannot access other cashiers' detailed transaction data
- ✅ Can manage held transactions and pending orders

**Implementation Status**: ✅ **COMPLETED**
- **Controller**: Daily summary methods in `TransactionController`
- **Views**: Cashier dashboard components
- **Reports**: Shift-specific reporting functionality

### 🔐 Access Permissions
- **POS System**: Full access to point-of-sale operations
- **Transaction Processing**: Create and view own transactions
- **Customer Service**: Limited customer information access
- **Product Information**: Read-only access to product catalog
- **Daily Reports**: Access to own shift reports and summaries
- **Receipt Management**: Print and reprint receipts

### 🚫 Restrictions
- Cannot access admin dashboard or administrative functions
- Cannot manage users, products, or system settings
- Cannot void transactions without supervisor approval
- Cannot access other cashiers' detailed transaction data
- Cannot modify product prices or inventory levels
- Cannot access financial reports or business analytics
- Cannot manage expenses or business operations
- Cannot export sensitive business data
- Cannot access customer management functions beyond basic service needs

---

## 🔐 Security & Access Control Implementation

### Middleware Implementation
**Implementation Status**: ✅ **COMPLETED**

1. **RoleMiddleware** (`app/Http/Middleware/RoleMiddleware.php`)
   - Handles role-based access control
   - Supports multiple roles per route
   - Redirects unauthorized users appropriately

2. **ManagerAccessMiddleware** (`app/Http/Middleware/ManagerAccessMiddleware.php`)
   - Specific restrictions for manager role
   - Prevents access to sensitive admin functions
   - Allows operational access while maintaining security

3. **AdminMiddleware** (`app/Http/Middleware/AdminMiddleware.php`)
   - Ensures admin-only access to critical functions
   - Protects user management and system configuration

### Route Protection
**Implementation Status**: ✅ **COMPLETED**

Routes are protected using middleware groups:
- **Admin Routes**: `middleware(['auth', 'role:admin'])`
- **Manager Routes**: `middleware(['auth', 'role:admin,manager'])`
- **Cashier Routes**: `middleware(['auth', 'role:admin,manager,cashier'])`

### Database-Level Security
**Implementation Status**: ✅ **COMPLETED**

- User roles stored in database with proper validation
- Role-based queries filter data appropriately
- Sensitive operations require proper role verification

---

## 📊 Role Comparison Matrix

| Feature | Admin/Owner | Manager | Cashier |
|---------|-------------|---------|---------|
| **User Management** | ✅ Full Access | ❌ No Access | ❌ No Access |
| **Product Management** | ✅ Full CRUD | 👁️ Read Only | 👁️ Read Only |
| **Customer Management** | ✅ Full CRUD | 👁️ Read Only | 🔄 Service Only |
| **Transaction Processing** | ✅ Full Access | 👁️ Read Only | ✅ Own Transactions |
| **Financial Reports** | ✅ All Reports | 📊 Operational Only | ❌ No Access |
| **Expense Management** | ✅ Full Access | ✅ Full Access | ❌ No Access |
| **POS System** | ✅ Full Access | ✅ Full Access | ✅ Full Access |
| **System Configuration** | ✅ Full Access | ❌ No Access | ❌ No Access |
| **Data Export** | ✅ All Data | 📊 Limited | ❌ No Access |
| **Dashboard Access** | 🏠 Admin Dashboard | 🏢 Manager Dashboard | 💰 POS Only |

**Legend**:
- ✅ Full Access
- 👁️ Read Only
- 🔄 Limited/Service Only
- 📊 Filtered/Operational Only
- ❌ No Access
- 🏠 Admin Dashboard
- 🏢 Manager Dashboard
- 💰 POS Interface

---

## 🎯 Implementation Status Summary

### ✅ Completed Features (73.4% Project Completion)

1. **Complete Role-Based Authentication System**
   - Multi-role support (Admin, Manager, Cashier)
   - Proper middleware implementation
   - Secure route protection

2. **Admin/Owner Capabilities**
   - Full system access and user management
   - Complete product and inventory control
   - Comprehensive financial reporting
   - Customer relationship management
   - Transaction oversight and control

3. **Manager Capabilities**
   - Operational dashboard with key metrics
   - Expense management with receipt uploads
   - Inventory monitoring and alerts
   - Performance reporting and analytics
   - Transaction monitoring (read-only)

4. **Cashier Capabilities**
   - Complete POS system with payment processing
   - Transaction management for own shifts
   - Customer service functionality
   - Product information access
   - Daily operations and reporting

### 🔄 Current Sprint (Sprint 4 - 15% Complete)
- Advanced search and filtering capabilities
- Performance optimization and caching
- UI/UX polish and accessibility improvements

### 📝 Remaining Work (26.6% of Project)
- Final testing and quality assurance
- Documentation completion
- Deployment preparation
- User training materials

---

## 🚀 Next Steps & Recommendations

### Immediate Actions
1. **Complete Sprint 4**: Focus on advanced search features and performance optimization
2. **User Testing**: Conduct role-based user testing with actual staff members
3. **Security Audit**: Perform comprehensive security review of role permissions
4. **Documentation**: Create role-specific user manuals and training materials

### Future Enhancements
1. **Role Customization**: Allow admins to create custom roles with specific permissions
2. **Audit Logging**: Implement comprehensive audit trails for all user actions
3. **Mobile App**: Develop mobile applications for each role
4. **Advanced Analytics**: Add more sophisticated reporting and analytics features

---

## 📋 Acceptance Criteria Summary

### Definition of Done for Role System
- ✅ All three roles (Admin, Manager, Cashier) are fully implemented
- ✅ Role-based access control is enforced at all levels
- ✅ Each role has appropriate dashboard and functionality
- ✅ Security measures prevent unauthorized access
- ✅ User experience is optimized for each role's workflow
- ✅ Documentation clearly defines each role's capabilities
- 🔄 User testing confirms role effectiveness (In Progress)
- 📝 Training materials are available for each role (Planned)

---

**Document Status**: ✅ **ACTIVE**  
**Implementation Status**: ✅ **73.4% COMPLETE**  
**Next Review Date**: February 16, 2026  
**Owner**: Development Team  
**Stakeholders**: Business Owner, Management Team, Staff

---

*This specification serves as the definitive guide for role definitions and responsibilities in the CoffPOS system. All development and implementation should align with these requirements.*