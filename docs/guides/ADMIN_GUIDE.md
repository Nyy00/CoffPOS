# 🔧 CoffPOS Admin Guide

**Panduan Administrasi Sistem untuk Admin dan IT Support**

---

## 📋 **Daftar Isi**

1. [System Overview](#system-overview)
2. [User Management](#user-management)
3. [Role & Permissions](#role--permissions)
4. [System Configuration](#system-configuration)
5. [Database Management](#database-management)
6. [Backup & Recovery](#backup--recovery)
7. [Security Management](#security-management)
8. [Performance Monitoring](#performance-monitoring)
9. [Troubleshooting](#troubleshooting)
10. [Maintenance Procedures](#maintenance-procedures)

---

## 🏗️ **System Overview**

### **Architecture**

CoffPOS dibangun dengan arsitektur modern:

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │    Backend      │    │    Database     │
│                 │    │                 │    │                 │
│ • Tailwind CSS  │◄──►│ • Laravel 10.x  │◄──►│ • MySQL 8.0     │
│ • Alpine.js     │    │ • PHP 8.1+      │    │ • InnoDB Engine │
│ • Chart.js      │    │ • Eloquent ORM  │    │ • UTF8MB4       │
│ • Vite          │    │ • Laravel Breeze│    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### **System Requirements**

#### **Production Server**
- **OS**: Ubuntu 20.04+ / CentOS 8+
- **Web Server**: Nginx 1.18+ / Apache 2.4+
- **PHP**: 8.1+ dengan extensions
- **Database**: MySQL 8.0+ / MariaDB 10.6+
- **Memory**: 2GB+ RAM
- **Storage**: 10GB+ SSD
- **SSL**: Required untuk production

#### **Development Environment**
- **PHP**: 8.1+ dengan Composer 2.x
- **Node.js**: 16+ dengan NPM 8+
- **Database**: MySQL 5.7+ / SQLite (untuk testing)
- **Git**: Version control

### **Directory Structure**

```
coffpos/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/              # Admin controllers
│   │   ├── Cashier/            # Cashier controllers
│   │   └── Frontend/           # Public controllers
│   ├── Models/                 # Eloquent models
│   ├── Services/               # Business logic
│   └── Providers/              # Service providers
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database schema
│   ├── seeders/               # Sample data
│   └── factories/             # Test data factories
├── resources/
│   ├── views/                 # Blade templates
│   ├── js/                    # JavaScript files
│   └── css/                   # Stylesheets
├── routes/                    # Route definitions
├── storage/
│   ├── app/public/images/     # Uploaded images
│   └── logs/                  # Application logs
└── vendor/                    # Composer dependencies
```

---

## 👥 **User Management**

### **User Roles Overview**

| Role | Access Level | Permissions |
|------|-------------|-------------|
| **Admin** | Full System | All features, user management, system config |
| **Manager** | Business Operations | Dashboard, reports, inventory, customers |
| **Cashier** | Point of Sale | POS system, basic transactions |

### **User Administration**

#### **Create New User**

**Via Admin Panel:**
1. Login sebagai Admin
2. Navigate ke **Users → Add User**
3. Fill required information
4. Assign appropriate role
5. Set initial password
6. Send credentials to user

**Via Artisan Command:**
```bash
php artisan make:user
# Interactive command untuk create user
```

#### **User Data Structure**

```sql
users table:
- id (Primary Key)
- name (Full Name)
- email (Unique)
- email_verified_at
- password (Hashed)
- role (admin/manager/cashier)
- phone
- avatar
- is_active (Boolean)
- created_at
- updated_at
```

#### **Bulk User Operations**

**Import Users from CSV:**
```bash
php artisan users:import users.csv
```

**Export Users:**
```bash
php artisan users:export --format=csv
```

### **Password Management**

#### **Password Policy**
- **Minimum length**: 8 characters
- **Complexity**: Letters, numbers, symbols
- **Expiry**: 90 days (configurable)
- **History**: Cannot reuse last 5 passwords

#### **Reset User Password**

**Via Admin Panel:**
1. Go to **Users → Edit User**
2. Click **"Reset Password"**
3. Generate new password
4. Send to user via secure channel

**Via Command Line:**
```bash
php artisan user:reset-password user@email.com
```

#### **Force Password Change**
```bash
php artisan user:force-password-change user@email.com
```

---

## 🔐 **Role & Permissions**

### **Permission Matrix**

| Feature | Admin | Manager | Cashier |
|---------|-------|---------|---------|
| **Dashboard** | ✅ Full | ✅ Business | ✅ Basic |
| **POS System** | ✅ | ✅ | ✅ |
| **Products** | ✅ CRUD | ✅ CRUD | ❌ |
| **Categories** | ✅ CRUD | ✅ CRUD | ❌ |
| **Customers** | ✅ CRUD | ✅ CRUD | ✅ Add Only |
| **Transactions** | ✅ All + Void | ✅ View + Void | ✅ Own Only |
| **Expenses** | ✅ CRUD | ✅ CRUD | ❌ |
| **Reports** | ✅ All | ✅ All | ✅ Basic |
| **Users** | ✅ CRUD | ❌ | ❌ |
| **Settings** | ✅ | ❌ | ❌ |

### **Role Implementation**

#### **Middleware Configuration**

```php
// routes/admin.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('reports', ReportController::class);
});

Route::middleware(['auth', 'role:admin,manager,cashier'])->group(function () {
    Route::get('/pos', [POSController::class, 'index']);
});
```

#### **Blade Template Permissions**

```blade
@can('manage-users')
    <a href="{{ route('admin.users.index') }}">Manage Users</a>
@endcan

@role('admin')
    <button class="admin-only-button">Admin Function</button>
@endrole

@hasanyrole('admin|manager')
    <div class="management-panel">...</div>
@endhasanyrole
```

#### **Controller Authorization**

```php
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,manager');
    }
    
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        // Delete logic
    }
}
```

### **Custom Permissions**

#### **Define Custom Permissions**

```php
// database/seeders/PermissionSeeder.php
$permissions = [
    'view-dashboard',
    'manage-products',
    'manage-customers',
    'process-transactions',
    'void-transactions',
    'view-reports',
    'export-data',
    'manage-users',
    'system-settings'
];
```

#### **Assign Permissions to Roles**

```php
// Assign permissions to roles
$adminRole->givePermissionTo([
    'view-dashboard', 'manage-products', 'manage-customers',
    'process-transactions', 'void-transactions', 'view-reports',
    'export-data', 'manage-users', 'system-settings'
]);

$managerRole->givePermissionTo([
    'view-dashboard', 'manage-products', 'manage-customers',
    'process-transactions', 'void-transactions', 'view-reports',
    'export-data'
]);

$cashierRole->givePermissionTo([
    'view-dashboard', 'process-transactions', 'view-reports'
]);
```

---

## ⚙️ **System Configuration**

### **Environment Configuration**

#### **Production .env Settings**

```env
# Application
APP_NAME="CoffPOS"
APP_ENV=production
APP_KEY=base64:generated_key_here
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffpos_prod
DB_USERNAME=coffpos_user
DB_PASSWORD=secure_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# File Storage
FILESYSTEM_DISK=public
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=

# PDF Configuration
DOMPDF_ENABLE_PHP=false
DOMPDF_ENABLE_REMOTE=false
```

### **Application Settings**

#### **Company Information**

Edit `app/Services/ReportService.php`:

```php
private function getCompanyInfo()
{
    return [
        'name' => config('app.company_name', 'Your Company'),
        'address' => config('app.company_address', 'Your Address'),
        'phone' => config('app.company_phone', 'Your Phone'),
        'email' => config('app.company_email', 'your@email.com'),
        'website' => config('app.company_website', 'www.yoursite.com'),
        'tax_id' => config('app.company_tax_id', 'TAX-ID'),
    ];
}
```

Add to `.env`:
```env
COMPANY_NAME="Your Coffee Shop"
COMPANY_ADDRESS="Jl. Example No. 123, Jakarta"
COMPANY_PHONE="+62-21-1234-5678"
COMPANY_EMAIL="info@yourcoffeeshop.com"
COMPANY_WEBSITE="www.yourcoffeeshop.com"
COMPANY_TAX_ID="12.345.678.9-012.000"
```

#### **Business Settings**

```php
// config/business.php
return [
    'currency' => [
        'code' => 'IDR',
        'symbol' => 'Rp',
        'decimal_places' => 0,
    ],
    
    'tax' => [
        'rate' => 10, // 10%
        'included' => false,
    ],
    
    'loyalty' => [
        'points_per_rupiah' => 0.001, // 1 point per Rp 1,000
        'redemption_rate' => 0.1, // 1 point = Rp 0.1
        'minimum_redemption' => 100, // Minimum 100 points
    ],
    
    'receipt' => [
        'footer_text' => 'Thank you for your visit!',
        'show_tax_breakdown' => true,
        'show_loyalty_points' => true,
    ],
];
```

### **Feature Toggles**

```php
// config/features.php
return [
    'loyalty_program' => env('FEATURE_LOYALTY', true),
    'multi_currency' => env('FEATURE_MULTI_CURRENCY', false),
    'inventory_tracking' => env('FEATURE_INVENTORY', true),
    'advanced_reporting' => env('FEATURE_ADVANCED_REPORTS', true),
    'email_receipts' => env('FEATURE_EMAIL_RECEIPTS', false),
    'sms_notifications' => env('FEATURE_SMS', false),
];
```

---

## 🗄️ **Database Management**

### **Database Schema**

#### **Core Tables**

```sql
-- Users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'cashier') DEFAULT 'cashier',
    phone VARCHAR(20),
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Products table
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category_id BIGINT UNSIGNED,
    price DECIMAL(10,2) NOT NULL,
    cost DECIMAL(10,2) DEFAULT 0,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Transactions table
CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transaction_code VARCHAR(50) UNIQUE NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    customer_id BIGINT UNSIGNED NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'debit_card', 'credit_card', 'e_wallet', 'qris') NOT NULL,
    payment_amount DECIMAL(10,2) NOT NULL,
    change_amount DECIMAL(10,2) DEFAULT 0,
    points_earned INT DEFAULT 0,
    status ENUM('pending', 'completed', 'voided') DEFAULT 'completed',
    void_reason TEXT NULL,
    voided_by BIGINT UNSIGNED NULL,
    voided_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (voided_by) REFERENCES users(id)
);
```

### **Database Maintenance**

#### **Regular Maintenance Tasks**

**Daily:**
```bash
# Optimize tables
php artisan db:optimize

# Clean old logs
php artisan log:clear --days=30

# Update statistics
php artisan db:analyze
```

**Weekly:**
```bash
# Full database optimization
php artisan db:maintenance

# Check database integrity
php artisan db:check

# Generate database report
php artisan db:report
```

#### **Index Optimization**

```sql
-- Performance indexes
CREATE INDEX idx_transactions_date ON transactions(created_at);
CREATE INDEX idx_transactions_user ON transactions(user_id);
CREATE INDEX idx_transactions_customer ON transactions(customer_id);
CREATE INDEX idx_transaction_items_product ON transaction_items(product_id);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_available ON products(is_available);

-- Composite indexes
CREATE INDEX idx_transactions_date_status ON transactions(created_at, status);
CREATE INDEX idx_products_category_available ON products(category_id, is_available);
```

#### **Database Monitoring**

```sql
-- Check table sizes
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.tables 
WHERE table_schema = 'coffpos_prod'
ORDER BY (data_length + index_length) DESC;

-- Check slow queries
SELECT * FROM mysql.slow_log 
WHERE start_time > DATE_SUB(NOW(), INTERVAL 1 DAY)
ORDER BY query_time DESC;

-- Monitor connections
SHOW PROCESSLIST;
```

---

## 💾 **Backup & Recovery**

### **Backup Strategy**

#### **Automated Daily Backup**

```bash
#!/bin/bash
# /home/coffpos/scripts/backup-daily.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/coffpos/backups/daily"
DB_NAME="coffpos_prod"
DB_USER="coffpos_user"
DB_PASS="secure_password"
APP_DIR="/var/www/coffpos"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Application files backup (excluding vendor and node_modules)
tar -czf $BACKUP_DIR/app_$DATE.tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs' \
    --exclude='storage/framework/cache' \
    -C /var/www coffpos

# Storage backup (uploaded files)
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C $APP_DIR storage/app/public

# Keep only last 7 days
find $BACKUP_DIR -name "*.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

#### **Weekly Full Backup**

```bash
#!/bin/bash
# /home/coffpos/scripts/backup-weekly.sh

DATE=$(date +%Y%m%d)
BACKUP_DIR="/home/coffpos/backups/weekly"
REMOTE_BACKUP="/mnt/remote-backup" # Mount point for remote storage

# Create full system backup
tar -czf $BACKUP_DIR/full_backup_$DATE.tar.gz \
    /var/www/coffpos \
    /home/coffpos/backups/daily \
    /etc/nginx/sites-available/coffpos \
    /etc/php/8.1/fpm/pool.d/www.conf

# Copy to remote storage
if [ -d "$REMOTE_BACKUP" ]; then
    cp $BACKUP_DIR/full_backup_$DATE.tar.gz $REMOTE_BACKUP/
    echo "Backup copied to remote storage"
fi

# Keep only last 4 weeks
find $BACKUP_DIR -name "full_backup_*.tar.gz" -mtime +28 -delete
```

#### **Cron Jobs Setup**

```bash
# Edit crontab
crontab -e

# Add backup schedules
0 2 * * * /home/coffpos/scripts/backup-daily.sh >> /home/coffpos/logs/backup.log 2>&1
0 3 * * 0 /home/coffpos/scripts/backup-weekly.sh >> /home/coffpos/logs/backup.log 2>&1
```

### **Recovery Procedures**

#### **Database Recovery**

```bash
# Stop application
sudo systemctl stop nginx php8.1-fpm

# Restore database
gunzip < /home/coffpos/backups/daily/db_20251219_020000.sql.gz | mysql -u coffpos_user -p coffpos_prod

# Clear application cache
cd /var/www/coffpos
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Restart services
sudo systemctl start php8.1-fpm nginx
```

#### **Full System Recovery**

```bash
# Extract application backup
cd /var/www
sudo rm -rf coffpos
sudo tar -xzf /home/coffpos/backups/weekly/full_backup_20251219.tar.gz

# Set permissions
sudo chown -R coffpos:www-data /var/www/coffpos
sudo chmod -R 755 /var/www/coffpos
sudo chmod -R 775 /var/www/coffpos/storage /var/www/coffpos/bootstrap/cache

# Restore database
# (same as above)

# Rebuild application
cd /var/www/coffpos
composer install --no-dev --optimize-autoloader
npm ci --only=production
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔒 **Security Management**

### **Security Checklist**

#### **Server Security**

- [ ] **Firewall configured** (UFW/iptables)
- [ ] **SSH key-based authentication**
- [ ] **Disable root login**
- [ ] **Regular security updates**
- [ ] **Fail2ban installed** untuk brute force protection
- [ ] **SSL certificate** installed dan auto-renewal
- [ ] **Security headers** configured di web server

#### **Application Security**

- [ ] **Environment variables** properly secured
- [ ] **Database credentials** dengan limited privileges
- [ ] **File permissions** correctly set
- [ ] **CSRF protection** enabled
- [ ] **XSS protection** implemented
- [ ] **SQL injection** prevention via ORM
- [ ] **File upload** validation dan sanitization

#### **Data Security**

- [ ] **Database encryption** at rest
- [ ] **Backup encryption**
- [ ] **Sensitive data** hashing (passwords)
- [ ] **PII data** protection
- [ ] **Audit logging** enabled
- [ ] **Data retention** policies

### **Security Monitoring**

#### **Log Monitoring**

```bash
# Monitor authentication attempts
tail -f /var/log/auth.log | grep "Failed password"

# Monitor application errors
tail -f /var/www/coffpos/storage/logs/laravel.log

# Monitor web server access
tail -f /var/log/nginx/access.log | grep -E "(POST|PUT|DELETE)"
```

#### **Security Alerts**

```bash
# Setup log monitoring with logwatch
sudo apt install logwatch
sudo logwatch --detail high --mailto admin@company.com --service all
```

#### **Intrusion Detection**

```bash
# Install and configure AIDE
sudo apt install aide
sudo aideinit
sudo mv /var/lib/aide/aide.db.new /var/lib/aide/aide.db

# Daily integrity check
echo "0 6 * * * /usr/bin/aide --check" | sudo crontab -
```

### **Security Incident Response**

#### **Incident Response Plan**

1. **Detection**: Monitor logs dan alerts
2. **Assessment**: Evaluate severity dan impact
3. **Containment**: Isolate affected systems
4. **Eradication**: Remove threats
5. **Recovery**: Restore normal operations
6. **Lessons Learned**: Document dan improve

#### **Emergency Procedures**

**Suspected Breach:**
```bash
# Immediately change all passwords
php artisan user:reset-all-passwords

# Disable all user accounts except admin
php artisan user:disable-all --except-admin

# Enable maintenance mode
php artisan down --secret="emergency-access-token"

# Create forensic backup
tar -czf /tmp/forensic-backup-$(date +%Y%m%d_%H%M%S).tar.gz /var/www/coffpos /var/log
```

---

## 📊 **Performance Monitoring**

### **System Monitoring**

#### **Server Metrics**

```bash
# CPU and Memory usage
htop

# Disk usage
df -h
du -sh /var/www/coffpos/*

# Network connections
netstat -tulpn | grep :80
netstat -tulpn | grep :443

# Process monitoring
ps aux | grep php-fpm
ps aux | grep nginx
ps aux | grep mysql
```

#### **Application Performance**

```bash
# PHP-FPM status
curl http://localhost/php-fpm-status

# Nginx status
curl http://localhost/nginx_status

# Database performance
mysql -e "SHOW PROCESSLIST;"
mysql -e "SHOW ENGINE INNODB STATUS\G"
```

### **Performance Optimization**

#### **PHP Optimization**

```ini
# /etc/php/8.1/fpm/php.ini
memory_limit = 256M
max_execution_time = 300
max_input_vars = 3000
upload_max_filesize = 10M
post_max_size = 10M

# OPcache settings
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

#### **Database Optimization**

```ini
# /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_size = 64M
query_cache_limit = 4M
max_connections = 100
```

#### **Web Server Optimization**

```nginx
# /etc/nginx/sites-available/coffpos
server {
    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;
    
    # Browser caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # Rate limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
    location /api/ {
        limit_req zone=api burst=20 nodelay;
    }
}
```

### **Monitoring Tools**

#### **Application Monitoring**

```bash
# Install monitoring tools
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# Enable query logging
php artisan tinker
DB::enableQueryLog();
// Run operations
DB::getQueryLog();
```

#### **Server Monitoring**

```bash
# Install system monitoring
sudo apt install htop iotop nethogs

# Setup monitoring dashboard
sudo apt install netdata
sudo systemctl enable netdata
sudo systemctl start netdata
# Access via http://server-ip:19999
```

---

## 🔧 **Troubleshooting**

### **Common Issues**

#### **Application Issues**

**Issue**: 500 Internal Server Error
```bash
# Check application logs
tail -f /var/www/coffpos/storage/logs/laravel.log

# Check web server logs
tail -f /var/log/nginx/error.log

# Check PHP-FPM logs
tail -f /var/log/php8.1-fpm.log

# Clear application cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Issue**: Database connection error
```bash
# Test database connection
mysql -u coffpos_user -p coffpos_prod

# Check database service
sudo systemctl status mysql

# Check database configuration
grep DB_ /var/www/coffpos/.env
```

**Issue**: Permission errors
```bash
# Fix file permissions
sudo chown -R coffpos:www-data /var/www/coffpos
sudo chmod -R 755 /var/www/coffpos
sudo chmod -R 775 /var/www/coffpos/storage /var/www/coffpos/bootstrap/cache
```

#### **Performance Issues**

**Issue**: Slow page loading
```bash
# Check server resources
htop
iotop
nethogs

# Optimize database
mysql -e "OPTIMIZE TABLE products, transactions, transaction_items;"

# Clear and rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Issue**: High memory usage
```bash
# Check PHP-FPM processes
ps aux | grep php-fpm | wc -l

# Adjust PHP-FPM settings
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
# Reduce pm.max_children if needed

sudo systemctl restart php8.1-fpm
```

### **Diagnostic Commands**

#### **System Diagnostics**

```bash
# System information
uname -a
lsb_release -a
df -h
free -h
uptime

# Service status
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql

# Network connectivity
ping google.com
curl -I https://your-domain.com
```

#### **Application Diagnostics**

```bash
# Laravel diagnostics
php artisan about
php artisan config:show
php artisan route:list
php artisan queue:work --once

# Database diagnostics
php artisan migrate:status
php artisan db:show
```

---

## 🛠️ **Maintenance Procedures**

### **Regular Maintenance Schedule**

#### **Daily Tasks**
- [ ] Monitor system resources
- [ ] Check application logs
- [ ] Verify backup completion
- [ ] Review security alerts

#### **Weekly Tasks**
- [ ] Update system packages
- [ ] Optimize database tables
- [ ] Clean old log files
- [ ] Review performance metrics
- [ ] Test backup restoration

#### **Monthly Tasks**
- [ ] Security audit
- [ ] Performance review
- [ ] Update documentation
- [ ] Review user accounts
- [ ] Capacity planning

#### **Quarterly Tasks**
- [ ] Full security assessment
- [ ] Disaster recovery test
- [ ] Performance optimization
- [ ] System architecture review

### **Maintenance Commands**

#### **System Updates**

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Update Composer dependencies
cd /var/www/coffpos
composer update --no-dev

# Update NPM packages
npm update
npm audit fix
npm run build

# Clear all caches
php artisan optimize:clear
php artisan optimize
```

#### **Database Maintenance**

```bash
# Optimize database
php artisan db:optimize

# Clean old data
php artisan data:cleanup --days=365

# Rebuild indexes
mysql -e "ANALYZE TABLE products, transactions, transaction_items;"
```

#### **Log Maintenance**

```bash
# Clean application logs
php artisan log:clear --days=30

# Clean system logs
sudo journalctl --vacuum-time=30d

# Rotate logs
sudo logrotate -f /etc/logrotate.conf
```

### **Emergency Maintenance**

#### **Maintenance Mode**

```bash
# Enable maintenance mode
php artisan down --secret="maintenance-token-123"

# Perform maintenance tasks
# ...

# Disable maintenance mode
php artisan up
```

#### **Emergency Procedures**

**High CPU Usage:**
```bash
# Identify resource-heavy processes
top -c
ps aux --sort=-%cpu | head -10

# Restart services if needed
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx
```

**Disk Space Full:**
```bash
# Find large files
du -sh /* | sort -rh | head -10
find /var/log -name "*.log" -size +100M

# Clean up space
php artisan log:clear --days=7
sudo apt autoremove
sudo apt autoclean
```

---

## 📞 **Support & Escalation**

### **Support Levels**

#### **Level 1: Basic Support**
- User account issues
- Basic functionality questions
- Password resets
- Simple configuration changes

#### **Level 2: Technical Support**
- Application errors
- Performance issues
- Database problems
- Integration issues

#### **Level 3: Expert Support**
- Security incidents
- System architecture changes
- Custom development
- Disaster recovery

### **Escalation Procedures**

#### **Contact Information**
- **Level 1 Support**: support@coffpos.com
- **Level 2 Support**: tech@coffpos.com
- **Level 3 Support**: expert@coffpos.com
- **Emergency**: +62-21-1234-5678

#### **Escalation Criteria**
- **Immediate**: Security breach, system down
- **High**: Data corruption, major functionality broken
- **Medium**: Performance degradation, minor bugs
- **Low**: Feature requests, documentation updates

---

## 📋 **Admin Checklist**

### **New Installation Checklist**
- [ ] Server requirements verified
- [ ] Application deployed successfully
- [ ] Database migrated and seeded
- [ ] SSL certificate installed
- [ ] Backup system configured
- [ ] Monitoring tools setup
- [ ] Security hardening completed
- [ ] User accounts created
- [ ] Documentation updated
- [ ] Training completed

### **Monthly Review Checklist**
- [ ] Security logs reviewed
- [ ] Performance metrics analyzed
- [ ] Backup integrity verified
- [ ] User access reviewed
- [ ] System updates applied
- [ ] Documentation updated
- [ ] Capacity planning reviewed
- [ ] Incident reports analyzed

---

**CoffPOS Admin Guide v1.0**  
*Last Updated: December 2025*  
*© 2025 CoffPOS. All rights reserved.*