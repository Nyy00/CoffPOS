# Fix: Google Login Redirect Loop Issue

## Problem
Setelah login dengan Google, user selalu di-redirect kembali ke halaman login, bukan ke dashboard.

## Possible Causes & Solutions

### 1. Session Configuration Issues

**Problem**: Session tidak tersimpan dengan benar di production.

**Check Railway Environment Variables:**
```bash
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

**Solution**: Pastikan tabel `sessions` ada di database:
```bash
php artisan migrate
```

### 2. Database Connection Issues

**Problem**: User tidak bisa dibuat/diupdate karena masalah database.

**Check**: Pastikan environment variables database benar:
```bash
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}
```

### 3. Missing google_id Column

**Problem**: Kolom `google_id` tidak ada di tabel users.

**Solution**: Jalankan migration:
```bash
php artisan migrate
```

### 4. Exception in Callback

**Problem**: Ada error dalam proses callback yang tidak terlihat.

**Debug Steps:**

1. **Check Railway Logs:**
   - Buka Railway Dashboard → Deployments → View Logs
   - Cari error saat callback Google OAuth

2. **Use Debug Routes:**
   ```
   https://your-domain.com/debug/google-oauth
   https://your-domain.com/debug/session
   ```

3. **Check Laravel Logs:**
   - Logs akan muncul di Railway deployment logs

### 5. APP_KEY Issues

**Problem**: APP_KEY tidak di-set atau berubah, menyebabkan session tidak valid.

**Solution**: Pastikan APP_KEY di-set di Railway:
```bash
php artisan key:generate --show
```
Copy output ke Railway environment variable `APP_KEY`.

### 6. Cookie Domain Issues

**Problem**: Cookie tidak bisa di-set karena domain mismatch.

**Check Railway Variables:**
```bash
APP_URL=https://coffpos-develop.up.railway.app
SESSION_DOMAIN=null
```

### 7. HTTPS/Security Issues

**Problem**: Session cookies tidak bisa di-set karena security policy.

**Railway Environment Variables:**
```bash
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

## Debug Process

### Step 1: Check Configuration
Visit: `https://your-domain.com/debug/google-oauth`

Expected response:
```json
{
  "google_client_id": "Set (1234567890...)",
  "google_client_secret": "Set",
  "google_redirect_url": "https://your-domain.com/auth/google/callback",
  "app_url": "https://your-domain.com",
  "app_env": "production",
  "socialite_installed": true,
  "session_driver": "database",
  "session_lifetime": 120,
  "auth_user": null,
  "database_connection": "pgsql",
  "session_table_exists": true
}
```

### Step 2: Test Session
Visit: `https://your-domain.com/debug/session`

Should show session data and CSRF token.

### Step 3: Check Railway Logs
1. Go to Railway Dashboard
2. Click on your deployment
3. View logs during Google login attempt
4. Look for errors or exceptions

### Step 4: Test Google Login Flow
1. Visit login page
2. Click "Sign in with Google"
3. Complete Google OAuth
4. Check Railway logs for any errors
5. Check if user is created in database

## Common Error Messages in Logs

### "SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "sessions" does not exist"
**Solution**: Run migrations
```bash
php artisan migrate
```

### "SQLSTATE[23502]: Not null violation: 7 ERROR: null value in column "password" violates not-null constraint"
**Solution**: Migration untuk nullable password belum jalan
```bash
php artisan migrate
```

### "Session store not set on request"
**Solution**: Session configuration issue
```bash
SESSION_DRIVER=database
```

### "The MAC is invalid"
**Solution**: APP_KEY issue
```bash
# Generate new key and set in Railway
php artisan key:generate --show
```

## Quick Fix Checklist

- [ ] Check Railway environment variables
- [ ] Verify APP_KEY is set
- [ ] Confirm database connection works
- [ ] Run migrations (`php artisan migrate`)
- [ ] Check session table exists
- [ ] Verify Google OAuth credentials
- [ ] Check Railway deployment logs
- [ ] Test debug routes
- [ ] Clear browser cache/cookies

## Updated Railway Environment Variables

```bash
# Application
APP_NAME="CoffPOS"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key-here
APP_URL=https://coffpos-develop.up.railway.app

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL=https://coffpos-develop.up.railway.app/auth/google/callback

# Database (Railway auto-injects)
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

# Cache
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## If Still Not Working

1. **Check specific error in Railway logs**
2. **Try creating a test user manually in database**
3. **Verify all migrations ran successfully**
4. **Test with a different Google account**
5. **Check if the issue happens with regular email/password login too**

The most common cause is session configuration or missing migrations. Check Railway logs for the exact error message.