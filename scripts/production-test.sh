#!/bin/bash

# CoffPOS Production Readiness Test Script
# This script tests if the application is ready for production deployment

set -e

echo "🧪 Starting CoffPOS Production Readiness Tests..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Counters
TESTS_PASSED=0
TESTS_FAILED=0
TESTS_TOTAL=0

# Function to print colored output
print_status() {
    echo -e "${GREEN}[PASS]${NC} $1"
    ((TESTS_PASSED++))
    ((TESTS_TOTAL++))
}

print_fail() {
    echo -e "${RED}[FAIL]${NC} $1"
    ((TESTS_FAILED++))
    ((TESTS_TOTAL++))
}

print_warning() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_section() {
    echo ""
    echo -e "${BLUE}=== $1 ===${NC}"
}

# Get the directory where the script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
APP_DIR="$(dirname "$SCRIPT_DIR")"

print_info "Application directory: $APP_DIR"
cd "$APP_DIR"

print_section "Environment Configuration Tests"

# Test 1: Check .env file exists
if [ -f ".env" ]; then
    print_status ".env file exists"
else
    print_fail ".env file missing"
fi

# Test 2: Check APP_ENV is production
if grep -q "APP_ENV=production" .env; then
    print_status "APP_ENV set to production"
else
    print_fail "APP_ENV not set to production"
fi

# Test 3: Check APP_DEBUG is false
if grep -q "APP_DEBUG=false" .env; then
    print_status "APP_DEBUG set to false"
else
    print_fail "APP_DEBUG not set to false"
fi

# Test 4: Check APP_KEY is set
if grep -q "APP_KEY=base64:" .env; then
    print_status "APP_KEY is properly set"
else
    print_fail "APP_KEY not properly set"
fi

# Test 5: Check APP_URL is set
if grep -q "APP_URL=https://" .env; then
    print_status "APP_URL uses HTTPS"
else
    print_fail "APP_URL should use HTTPS in production"
fi

print_section "Database Configuration Tests"

# Test 6: Check database connection
if php artisan migrate:status > /dev/null 2>&1; then
    print_status "Database connection successful"
else
    print_fail "Database connection failed"
fi

# Test 7: Check all migrations are run
PENDING_MIGRATIONS=$(php artisan migrate:status | grep -c "Pending" || true)
if [ "$PENDING_MIGRATIONS" -eq 0 ]; then
    print_status "All database migrations are up to date"
else
    print_fail "$PENDING_MIGRATIONS pending migrations found"
fi

print_section "File System Tests"

# Test 8: Check storage directory is writable
if [ -w "storage/logs" ]; then
    print_status "Storage logs directory is writable"
else
    print_fail "Storage logs directory is not writable"
fi

# Test 9: Check bootstrap/cache is writable
if [ -w "bootstrap/cache" ]; then
    print_status "Bootstrap cache directory is writable"
else
    print_fail "Bootstrap cache directory is not writable"
fi

# Test 10: Check storage link exists
if [ -L "public/storage" ]; then
    print_status "Storage link exists"
else
    print_fail "Storage link missing (run: php artisan storage:link)"
fi

# Test 11: Check critical directories exist
CRITICAL_DIRS=("storage/app/public/images/products" "storage/app/public/images/categories" "storage/app/public/images/users" "storage/app/public/images/receipts")
for dir in "${CRITICAL_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        print_status "Directory $dir exists"
    else
        print_fail "Directory $dir missing"
        mkdir -p "$dir" 2>/dev/null || true
    fi
done

print_section "Security Tests"

# Test 12: Check .env file permissions
ENV_PERMS=$(stat -c "%a" .env 2>/dev/null || echo "unknown")
if [ "$ENV_PERMS" = "600" ] || [ "$ENV_PERMS" = "644" ]; then
    print_status ".env file has secure permissions ($ENV_PERMS)"
else
    print_warning ".env file permissions should be 600 or 644 (current: $ENV_PERMS)"
fi

# Test 13: Check sensitive files are not publicly accessible
SENSITIVE_FILES=(".env" "composer.json" "package.json")
for file in "${SENSITIVE_FILES[@]}"; do
    if [ -f "public/$file" ]; then
        print_fail "Sensitive file $file found in public directory"
    else
        print_status "Sensitive file $file not in public directory"
    fi
done

print_section "Performance Tests"

# Test 14: Check if caches are enabled
if [ -f "bootstrap/cache/config.php" ]; then
    print_status "Configuration cache enabled"
else
    print_fail "Configuration cache not enabled (run: php artisan config:cache)"
fi

if [ -f "bootstrap/cache/routes-v7.php" ]; then
    print_status "Route cache enabled"
else
    print_fail "Route cache not enabled (run: php artisan route:cache)"
fi

if [ -f "bootstrap/cache/compiled.php" ]; then
    print_status "View cache enabled"
else
    print_warning "View cache not enabled (run: php artisan view:cache)"
fi

# Test 15: Check Composer autoloader optimization
if grep -q "optimized" vendor/composer/autoload_real.php 2>/dev/null; then
    print_status "Composer autoloader is optimized"
else
    print_fail "Composer autoloader not optimized (run: composer install --optimize-autoloader)"
fi

print_section "Application Tests"

# Test 16: Check if application can boot
if php artisan --version > /dev/null 2>&1; then
    print_status "Application boots successfully"
else
    print_fail "Application fails to boot"
fi

# Test 17: Check routes are loaded
ROUTE_COUNT=$(php artisan route:list --json 2>/dev/null | jq length 2>/dev/null || echo "0")
if [ "$ROUTE_COUNT" -gt 0 ]; then
    print_status "Application routes loaded ($ROUTE_COUNT routes)"
else
    print_fail "No application routes loaded"
fi

# Test 18: Check models can be loaded
if php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); echo 'Models loaded successfully';" > /dev/null 2>&1; then
    print_status "Models load successfully"
else
    print_fail "Models fail to load"
fi

print_section "Asset Tests"

# Test 19: Check if assets are built
if [ -f "public/build/manifest.json" ]; then
    print_status "Production assets are built"
else
    print_fail "Production assets not built (run: npm run build)"
fi

# Test 20: Check critical CSS/JS files
ASSET_FILES=("public/build/assets/app.css" "public/build/assets/app.js")
for file in "${ASSET_FILES[@]}"; do
    if ls $file* > /dev/null 2>&1; then
        print_status "Asset file exists: $(basename $file)"
    else
        print_fail "Asset file missing: $(basename $file)"
    fi
done

print_section "Dependency Tests"

# Test 21: Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
if php -r "exit(version_compare(PHP_VERSION, '8.1.0', '>=') ? 0 : 1);"; then
    print_status "PHP version is compatible ($PHP_VERSION)"
else
    print_fail "PHP version too old ($PHP_VERSION). Requires 8.1.0+"
fi

# Test 22: Check required PHP extensions
REQUIRED_EXTENSIONS=("bcmath" "ctype" "curl" "dom" "fileinfo" "json" "mbstring" "openssl" "pcre" "pdo" "tokenizer" "xml" "gd" "zip")
for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -q "^$ext$"; then
        print_status "PHP extension $ext is loaded"
    else
        print_fail "PHP extension $ext is missing"
    fi
done

# Test 23: Check Composer dependencies
if [ -f "vendor/autoload.php" ]; then
    print_status "Composer dependencies installed"
else
    print_fail "Composer dependencies not installed"
fi

# Test 24: Check Node.js dependencies
if [ -d "node_modules" ]; then
    print_status "Node.js dependencies installed"
else
    print_fail "Node.js dependencies not installed"
fi

print_section "Business Logic Tests"

# Test 25: Test database seeding (check if admin user exists)
if php artisan tinker --execute="echo App\Models\User::where('role', 'admin')->exists() ? 'true' : 'false';" 2>/dev/null | grep -q "true"; then
    print_status "Admin user exists in database"
else
    print_warning "Admin user not found (run: php artisan db:seed)"
fi

# Test 26: Test basic model relationships
if php artisan tinker --execute="echo App\Models\Product::with('category')->first() ? 'true' : 'false';" 2>/dev/null | grep -q "true"; then
    print_status "Model relationships working"
else
    print_warning "Model relationships may have issues"
fi

print_section "Configuration Tests"

# Test 27: Check timezone configuration
TIMEZONE=$(php -r "echo date_default_timezone_get();")
print_info "Application timezone: $TIMEZONE"

# Test 28: Check memory limit
MEMORY_LIMIT=$(php -r "echo ini_get('memory_limit');")
print_info "PHP memory limit: $MEMORY_LIMIT"

# Test 29: Check upload limits
UPLOAD_LIMIT=$(php -r "echo ini_get('upload_max_filesize');")
POST_LIMIT=$(php -r "echo ini_get('post_max_size');")
print_info "Upload limits - File: $UPLOAD_LIMIT, Post: $POST_LIMIT"

print_section "Test Summary"

echo ""
echo "📊 Test Results:"
echo "   Total Tests: $TESTS_TOTAL"
echo "   Passed: $TESTS_PASSED"
echo "   Failed: $TESTS_FAILED"
echo ""

if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All tests passed! Your application is ready for production.${NC}"
    echo ""
    print_info "Next steps:"
    print_info "1. Configure your web server (Nginx/Apache)"
    print_info "2. Set up SSL certificate"
    print_info "3. Configure monitoring and logging"
    print_info "4. Set up automated backups"
    print_info "5. Perform final security audit"
    echo ""
    exit 0
else
    echo -e "${RED}❌ $TESTS_FAILED test(s) failed. Please fix the issues before deploying to production.${NC}"
    echo ""
    print_info "Common fixes:"
    print_info "- Run: php artisan optimize"
    print_info "- Run: composer install --optimize-autoloader --no-dev"
    print_info "- Run: npm run build"
    print_info "- Check file permissions: chmod -R 775 storage bootstrap/cache"
    print_info "- Verify .env configuration"
    echo ""
    exit 1
fi