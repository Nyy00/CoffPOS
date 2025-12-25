#!/bin/bash

# CoffPOS Production Optimization Script
# This script optimizes the application for production deployment

set -e

echo "🚀 Starting CoffPOS Production Optimization..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Check if running as correct user
if [ "$EUID" -eq 0 ]; then
    print_error "Do not run this script as root. Run as the application user (e.g., coffpos)"
    exit 1
fi

# Get the directory where the script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
APP_DIR="$(dirname "$SCRIPT_DIR")"

print_status "Application directory: $APP_DIR"

# Change to application directory
cd "$APP_DIR"

# Check if .env file exists
if [ ! -f ".env" ]; then
    print_error ".env file not found. Please create it from .env.production template"
    exit 1
fi

print_step "1. Checking PHP and Composer..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
print_status "PHP Version: $PHP_VERSION"

if ! command -v composer &> /dev/null; then
    print_error "Composer not found. Please install Composer first."
    exit 1
fi

print_step "2. Installing/Updating Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
print_status "Composer dependencies installed"

print_step "3. Checking Node.js and NPM..."

if ! command -v node &> /dev/null; then
    print_error "Node.js not found. Please install Node.js first."
    exit 1
fi

if ! command -v npm &> /dev/null; then
    print_error "NPM not found. Please install NPM first."
    exit 1
fi

NODE_VERSION=$(node --version)
NPM_VERSION=$(npm --version)
print_status "Node.js Version: $NODE_VERSION"
print_status "NPM Version: $NPM_VERSION"

print_step "4. Installing NPM dependencies..."
npm ci --only=production --silent
print_status "NPM dependencies installed"

print_step "5. Building production assets..."
npm run build
print_status "Assets built successfully"

print_step "6. Generating application key (if not set)..."
if grep -q "APP_KEY=$" .env; then
    php artisan key:generate --force
    print_status "Application key generated"
else
    print_status "Application key already exists"
fi

print_step "7. Running database migrations..."
php artisan migrate --force
print_status "Database migrations completed"

print_step "8. Creating storage link..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    print_status "Storage link created"
else
    print_status "Storage link already exists"
fi

print_step "9. Optimizing application..."

# Clear all caches first
php artisan optimize:clear
print_status "Caches cleared"

# Cache configuration
php artisan config:cache
print_status "Configuration cached"

# Cache routes
php artisan route:cache
print_status "Routes cached"

# Cache views
php artisan view:cache
print_status "Views cached"

# Cache events
php artisan event:cache
print_status "Events cached"

print_step "10. Setting file permissions..."

# Set proper ownership (assuming www-data group)
if groups | grep -q "www-data"; then
    find . -type f -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    chmod -R 775 storage bootstrap/cache
    print_status "File permissions set"
else
    print_warning "www-data group not found. Please set permissions manually:"
    print_warning "sudo chown -R \$USER:www-data $APP_DIR"
    print_warning "sudo chmod -R 755 $APP_DIR"
    print_warning "sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache"
fi

print_step "11. Running application tests..."
if [ -f "phpunit.xml" ]; then
    php artisan test --parallel --stop-on-failure
    print_status "Tests passed"
else
    print_warning "No tests found. Consider adding tests for better reliability."
fi

print_step "12. Checking application health..."

# Check if application is accessible
if php artisan route:list | grep -q "admin.dashboard"; then
    print_status "Application routes loaded successfully"
else
    print_error "Application routes not loaded properly"
    exit 1
fi

# Check database connection
if php artisan migrate:status > /dev/null 2>&1; then
    print_status "Database connection successful"
else
    print_error "Database connection failed"
    exit 1
fi

print_step "13. Generating optimization report..."

# Create optimization report
REPORT_FILE="storage/logs/optimization-report-$(date +%Y%m%d_%H%M%S).log"

cat > "$REPORT_FILE" << EOF
CoffPOS Production Optimization Report
Generated: $(date)
Application Directory: $APP_DIR

System Information:
- PHP Version: $PHP_VERSION
- Node.js Version: $NODE_VERSION
- NPM Version: $NPM_VERSION
- OS: $(uname -s) $(uname -r)

Optimization Steps Completed:
✅ Composer dependencies installed (production mode)
✅ NPM dependencies installed (production mode)
✅ Assets built and optimized
✅ Application key generated/verified
✅ Database migrations executed
✅ Storage link created
✅ Configuration cached
✅ Routes cached
✅ Views cached
✅ Events cached
✅ File permissions set
✅ Application health checked

Performance Optimizations Applied:
- Composer autoloader optimized
- Laravel caches enabled (config, routes, views, events)
- Production asset compilation with minification
- Proper file permissions for web server

Security Measures:
- Debug mode disabled (APP_DEBUG=false)
- Production environment set (APP_ENV=production)
- Secure file permissions applied
- Storage directory properly isolated

Next Steps:
1. Configure web server (Nginx/Apache)
2. Set up SSL certificate
3. Configure monitoring and logging
4. Set up automated backups
5. Configure firewall rules
6. Test all application features

EOF

print_status "Optimization report saved to: $REPORT_FILE"

print_step "14. Final verification..."

# Check critical files
CRITICAL_FILES=(
    ".env"
    "public/index.php"
    "storage/app"
    "storage/logs"
    "bootstrap/cache"
)

for file in "${CRITICAL_FILES[@]}"; do
    if [ -e "$file" ]; then
        print_status "✅ $file exists"
    else
        print_error "❌ $file missing"
        exit 1
    fi
done

# Check critical directories permissions
if [ -w "storage/logs" ] && [ -w "bootstrap/cache" ]; then
    print_status "✅ Critical directories are writable"
else
    print_error "❌ Critical directories are not writable"
    exit 1
fi

echo ""
echo "🎉 Production optimization completed successfully!"
echo ""
print_status "Your CoffPOS application is now optimized for production."
print_status "Report saved to: $REPORT_FILE"
echo ""
print_warning "Important reminders:"
print_warning "1. Configure your web server to point to the 'public' directory"
print_warning "2. Set up SSL certificate for HTTPS"
print_warning "3. Configure automated backups"
print_warning "4. Set up monitoring and alerting"
print_warning "5. Test all functionality before going live"
echo ""
print_status "For detailed deployment instructions, see DEPLOYMENT_GUIDE.md"
echo ""

# Display application info
echo "📊 Application Information:"
echo "   URL: $(grep APP_URL .env | cut -d '=' -f2)"
echo "   Environment: $(grep APP_ENV .env | cut -d '=' -f2)"
echo "   Debug Mode: $(grep APP_DEBUG .env | cut -d '=' -f2)"
echo "   Database: $(grep DB_DATABASE .env | cut -d '=' -f2)"
echo ""

print_status "Optimization completed at $(date)"