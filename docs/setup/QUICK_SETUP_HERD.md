# ⚡ Quick Setup - Herd + TablePlus (5 Minutes)

**Setup CoffPOS dalam 5 menit dengan Laravel Herd dan TablePlus**

---

## 📥 Step 1: Install Tools (2 minutes)

### Laravel Herd
```
Download: https://herd.laravel.com/
Install: Double-click installer
Done: Herd icon appears in system tray
```

### TablePlus
```
Download: https://tableplus.com/
Install: Double-click installer
Done: Launch TablePlus
```

---

## 📁 Step 2: Move Project (1 minute)

```bash
# Move to Herd directory
# Windows:
move C:\laragon\www\CoffPOS\coffpos C:\Users\[YourName]\Herd\coffpos

# Mac:
mv ~/Sites/coffpos ~/Herd/coffpos
```

**Or clone fresh:**
```bash
cd C:\Users\[YourName]\Herd
git clone [your-repo] coffpos
cd coffpos
```

---

## ⚙️ Step 3: Setup Project (2 minutes)

```bash
# Install dependencies
composer install
npm install

# Environment
copy .env.example .env
php artisan key:generate

# Database
type nul > database\database.sqlite
php artisan migrate:fresh --seed
php artisan storage:link

# Assets
npm run build
```

**Done!** Access: http://coffpos.test

---

## 🗄️ Step 4: Connect TablePlus (30 seconds)

1. Open TablePlus
2. Click "Create a new connection"
3. Select **SQLite**
4. **Name**: CoffPOS
5. **Path**: Browse to `C:\Users\[YourName]\Herd\coffpos\database\database.sqlite`
6. Click **Connect**

**Done!** You can now view and edit database.

---

## ✅ Verification

### Check Application
```
✓ Open: http://coffpos.test
✓ Login: admin@coffpos.com / password
✓ Dashboard loads
```

### Check Database
```
✓ TablePlus shows 15 tables
✓ 3 users in users table
✓ 12 products in products table
```

---

## 🎯 That's It!

**You're ready to develop!**

- Application: http://coffpos.test
- Database: TablePlus
- No `php artisan serve` needed
- No Apache/Nginx config needed

---

## 💡 Quick Tips

### Herd Commands
```bash
herd sites          # List all sites
herd open           # Open in browser
herd secure         # Enable HTTPS
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Rebuild Assets
```bash
npm run build
```

---

## 🐛 Troubleshooting

**Site not accessible?**
```bash
# Restart Herd
Right-click Herd icon > Restart
```

**Database not found?**
```bash
# Create database
type nul > database\database.sqlite
php artisan migrate:fresh --seed
```

**Assets not loading?**
```bash
npm run build
php artisan optimize:clear
```

---

## 📚 Full Guide

For detailed setup: [SETUP_HERD_TABLEPLUS.md](SETUP_HERD_TABLEPLUS.md)

---

