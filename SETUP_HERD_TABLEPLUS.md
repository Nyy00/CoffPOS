# 🚀 Quick Setup - Laravel Herd + TablePlus

**Setup CoffPOS dengan Laravel Herd dan TablePlus**

---

## 📋 Prerequisites

### 1. Install Laravel Herd
**Download**: https://herd.laravel.com/

**Features:**
- PHP 8.2+ (built-in)
- Nginx (built-in)
- MySQL/PostgreSQL support
- Automatic .test domain
- No configuration needed

**Installation:**
1. Download Herd for Windows/Mac
2. Run installer
3. Herd will start automatically
4. PHP and Nginx ready to use

---

### 2. Install TablePlus
**Download**: https://tableplus.com/

**Features:**
- SQLite support
- Beautiful UI
- Query editor
- Data export/import
- Multiple database connections

**Installation:**
1. Download TablePlus
2. Run installer
3. Launch TablePlus
4. Free version works great!

---

## 🎯 Setup CoffPOS with Herd

### Step 1: Clone/Move Project to Herd Directory

**Herd Default Directory:**
```
Windows: C:\Users\[YourName]\Herd
Mac: ~/Herd
```

**Move CoffPOS:**
```bash
# Move your project to Herd directory
# Example for Windows:
move C:\laragon\www\coffpos 

# or 

move C:\Users\[YourName]\Herd\coffpos

# Or clone fresh:
cd C:\Users\[YourName]\Herd
git clone [your-repo-url] coffpos
cd coffpos
```


### Step 2: Install Dependencies

```bash
# Navigate to project
cd C:\Users\[YourName]\Herd\coffpos

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

---

### Step 3: Environment Setup

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

**Edit `.env` file:**
```env
APP_NAME=CoffPOS
APP_URL=http://coffpos.test

DB_CONNECTION=sqlite
# Remove or comment out these lines:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

---

### Step 4: Database Setup

```bash
# Create SQLite database (if not exists)
type nul > database\database.sqlite

# Run migrations with seeders
php artisan migrate:fresh --seed

# Link storage
php artisan storage:link
```

---

### Step 5: Build Assets

```bash
# Build frontend assets
npm run build

# Or for development (watch mode)
npm run dev
```


### Step 6: Access Application

**Herd automatically creates `.test` domain:**

```
URL: http://coffpos.test
```

**Open in browser:**
- Home: http://coffpos.test
- Login: http://coffpos.test/login
- Dashboard: http://coffpos.test/dashboard

**No need to run `php artisan serve`!** 🎉

---

## 🗄️ Setup TablePlus for SQLite

### Step 1: Open TablePlus

1. Launch TablePlus
2. Click "Create a new connection"
3. Select **SQLite**

---

### Step 2: Configure Connection

**Connection Settings:**
```
Name: CoffPOS
Database Path: [Browse to your project]
  Windows: C:\Users\[YourName]\Herd\coffpos\database\database.sqlite
  Mac: ~/Herd/coffpos/database/database.sqlite
```

**Click "Test" then "Connect"**

---

### Step 3: Explore Database

**You should see:**
- 15 tables total
- 7 main tables (users, categories, products, customers, transactions, transaction_items, expenses)
- 8 Laravel default tables

**Sample Data:**
- 3 users
- 4 categories
- 12 products
- 3 customers

---

## 🎨 TablePlus Tips

### View Data
```sql
-- View all products
SELECT * FROM products;

-- View products with categories
SELECT p.*, c.name as category_name 
FROM products p 
JOIN categories c ON p.category_id = c.id;

-- View users with roles
SELECT name, email, role FROM users;
```


### Edit Data
1. Double-click any cell to edit
2. Press Enter to save
3. Click "Commit" button to apply changes

### Export Data
1. Right-click table
2. Select "Export"
3. Choose format (SQL, CSV, JSON)

### Import Data
1. Right-click database
2. Select "Import"
3. Choose file

---

## ⚙️ Herd Configuration

### Access Herd Settings

**Windows:**
- Right-click Herd icon in system tray
- Select "Settings"

**Mac:**
- Click Herd icon in menu bar
- Select "Preferences"

---

### Useful Herd Features

#### 1. PHP Version Switching
```
Herd > Settings > PHP Version
- Switch between PHP 8.1, 8.2, 8.3
- Per-site PHP version
```

#### 2. Site Management
```
Herd > Sites
- View all sites
- Open in browser
- Open in editor
- Secure with SSL
```

#### 3. Database Services
```
Herd > Services
- MySQL (optional)
- PostgreSQL (optional)
- Redis (optional)
```

#### 4. Secure Sites (HTTPS)
```bash
# Enable HTTPS for your site
herd secure coffpos

# Access with:
https://coffpos.test
```

---

## 🔧 Troubleshooting

### Issue: Site not accessible

**Solution 1: Restart Herd**
```
Right-click Herd icon > Restart
```

**Solution 2: Check site is parked**
```bash
# List parked sites
herd sites

# If not listed, park manually
cd C:\Users\[YourName]\Herd\coffpos
herd link
```


### Issue: Database not found in TablePlus

**Solution:**
```bash
# Make sure database file exists
dir database\database.sqlite

# If not exists, create it
type nul > database\database.sqlite

# Run migrations
php artisan migrate:fresh --seed
```

### Issue: Permission denied

**Solution (Windows):**
```bash
# Run as Administrator
# Right-click Command Prompt > Run as Administrator
```

**Solution (Mac):**
```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
```

### Issue: Assets not loading

**Solution:**
```bash
# Rebuild assets
npm run build

# Clear cache
php artisan optimize:clear
```

---

## 📊 Quick Commands Reference

### Herd Commands
```bash
# Link current directory
herd link

# Unlink site
herd unlink

# List all sites
herd sites

# Open site in browser
herd open

# Secure site with HTTPS
herd secure

# View logs
herd logs
```

### Laravel Commands
```bash
# Reset database
php artisan migrate:fresh --seed

# Clear all cache
php artisan optimize:clear

# View routes
php artisan route:list

# Check database
php artisan db:show

# Interactive shell
php artisan tinker
```

### NPM Commands
```bash
# Development (watch mode)
npm run dev

# Production build
npm run build

# Install dependencies
npm install
```


---

## 🎯 Complete Setup Checklist

### Initial Setup
- [ ] Install Laravel Herd
- [ ] Install TablePlus
- [ ] Move/Clone project to Herd directory
- [ ] Run `composer install`
- [ ] Run `npm install`
- [ ] Copy `.env.example` to `.env`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Create SQLite database
- [ ] Run migrations: `php artisan migrate:fresh --seed`
- [ ] Link storage: `php artisan storage:link`
- [ ] Build assets: `npm run build`

### TablePlus Setup
- [ ] Open TablePlus
- [ ] Create SQLite connection
- [ ] Browse to database.sqlite
- [ ] Test connection
- [ ] Verify tables (15 tables)
- [ ] Check sample data

### Verification
- [ ] Access http://coffpos.test
- [ ] Login with demo credentials
- [ ] Check dashboard
- [ ] View database in TablePlus
- [ ] Test navigation

---

## 🔑 Login Credentials

```
Admin:
Email: admin@coffpos.com
Password: password

Manager:
Email: manager@coffpos.com
Password: password

Cashier:
Email: cashier@coffpos.com
Password: password
```

---

## 💡 Pro Tips

### 1. Multiple Projects
```bash
# Herd supports multiple projects
C:\Users\[YourName]\Herd\
├── coffpos (http://coffpos.test)
├── project2 (http://project2.test)
└── project3 (http://project3.test)
```

### 2. Custom Domain
```bash
# Use custom domain
herd link my-custom-name
# Access: http://my-custom-name.test
```

### 3. Share Site
```bash
# Share site publicly (ngrok-like)
herd share
# Get public URL
```

### 4. Database Backup
```sql
-- In TablePlus
Right-click database > Export > SQL
Save as: coffpos_backup_2025-12-05.sql
```

### 5. Quick Database Reset
```bash
# One command to reset everything
php artisan migrate:fresh --seed && php artisan optimize:clear
```

---

## 📚 Additional Resources

### Laravel Herd
- Documentation: https://herd.laravel.com/docs
- GitHub: https://github.com/laravel/herd

### TablePlus
- Documentation: https://docs.tableplus.com/
- Keyboard Shortcuts: https://tableplus.com/blog/2018/08/list-of-keyboard-shortcuts.html

### CoffPOS
- Main README: [README.md](README.md)
- Database ERD: [DATABASE_ERD.md](DATABASE_ERD.md)
- Quick Start: [QUICK_START_AUTHENTICATION.md](QUICK_START_AUTHENTICATION.md)

---

## ✅ Success!

If you can access http://coffpos.test and see your database in TablePlus, you're all set! 🎉

**Next Steps:**
1. Explore the application
2. Check database structure in TablePlus
3. Start development (Week 3-4: Backend CRUD)

---

**Happy Coding with Herd + TablePlus! ☕💻**

---

<p align="center">
<em>Last Updated: December 5, 2025</em>
</p>
