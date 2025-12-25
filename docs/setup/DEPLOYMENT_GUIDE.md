# 🚀 CoffPOS Deployment Guide

Panduan lengkap untuk deploy CoffPOS ke production server.

## 📋 **Pre-Deployment Checklist**

### **Server Requirements**
- [ ] Ubuntu 20.04+ atau CentOS 8+
- [ ] PHP 8.1+ dengan extensions yang diperlukan
- [ ] MySQL 8.0+ atau MariaDB 10.6+
- [ ] Nginx 1.18+ atau Apache 2.4+
- [ ] Node.js 16+ dan NPM 8+
- [ ] Composer 2.x
- [ ] SSL Certificate
- [ ] Domain name configured

### **Security Requirements**
- [ ] Firewall configured (UFW/iptables)
- [ ] SSH key-based authentication
- [ ] Non-root user dengan sudo privileges
- [ ] Database user dengan limited privileges
- [ ] Backup strategy in place

## 🖥️ **Server Setup**

### **1. Initial Server Configuration**

#### **Update System**
```bash
sudo apt update && sudo apt upgrade -y
sudo reboot
```

#### **Create Application User**
```bash
sudo adduser coffpos
sudo usermod -aG sudo coffpos
sudo su - coffpos
```

#### **Configure Firewall**
```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

### **2. Install Required Software**

#### **Install PHP 8.1 and Extensions**
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install php8.1-fpm php8.1-mysql php8.1-xml php8.1-gd php8.1-curl \
php8.1-mbstring php8.1-zip php8.1-bcmath php8.1-json php8.1-tokenizer \
php8.1-ctype php8.1-fileinfo php8.1-dom unzip -y
```

#### **Install MySQL**
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

#### **Install Nginx**
```bash
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx
```

#### **Install Node.js and NPM**
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y
node --version
npm --version
```

#### **Install Composer**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### **3. Database Setup**

#### **Create Database and User**
```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE coffpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'coffpos_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';
GRANT ALL PRIVILEGES ON coffpos.* TO 'coffpos_user'@'localhost';
FLUSH PRIVILEGES;
SHOW DATABASES;
EXIT;
```

#### **Configure MySQL (Optional)**
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Add/modify these settings:
```ini
[mysqld]
innodb_buffer_pool_size = 256M
max_connections = 100
query_cache_size = 32M
query_cache_limit = 2M
```

```bash
sudo systemctl restart mysql
```

## 📦 **Application Deployment**

### **1. Clone and Setup Application**

#### **Clone Repository**
```bash
cd /var/www
sudo git clone https://github.com/your-username/coffpos.git
sudo chown -R coffpos:coffpos /var/www/coffpos
cd /var/www/coffpos
```

#### **Install Dependencies**
```bash
# Install PHP dependencies (production)
composer install --optimize-autoloader --no-dev --no-interaction

# Install Node.js dependencies
npm ci --only=production
```

### **2. Environment Configuration**

#### **Setup Environment File**
```bash
cp .env.example .env
nano .env
```

**Production .env Configuration:**
```env
APP_NAME="CoffPOS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffpos
DB_USERNAME=coffpos_user
DB_PASSWORD=your_secure_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# PDF Configuration
DOMPDF_ENABLE_PHP=false
DOMPDF_ENABLE_REMOTE=false
DOMPDF_DEFAULT_PAPER_SIZE=a4
```

#### **Generate Application Key**
```bash
php artisan key:generate
```

### **3. Database Migration and Seeding**

#### **Run Migrations**
```bash
php artisan migrate --force
```

#### **Seed Database**
```bash
php artisan db:seed --force
```

#### **Create Storage Link**
```bash
php artisan storage:link
```

### **4. Build Assets**

#### **Build Production Assets**
```bash
npm run build
```

#### **Optimize Application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### **5. Set Permissions**

#### **Set Proper Ownership**
```bash
sudo chown -R coffpos:www-data /var/www/coffpos
```

#### **Set Directory Permissions**
```bash
sudo find /var/www/coffpos -type f -exec chmod 644 {} \;
sudo find /var/www/coffpos -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/coffpos/storage
sudo chmod -R 775 /var/www/coffpos/bootstrap/cache
```

## 🌐 **Web Server Configuration**

### **Nginx Configuration**

#### **Create Nginx Site Configuration**
```bash
sudo nano /etc/nginx/sites-available/coffpos
```

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/coffpos/public;
    index index.php index.html index.htm;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Deny access to sensitive files
    location ~ /\.(env|git) {
        deny all;
    }

    location /storage {
        alias /var/www/coffpos/storage/app/public;
    }

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    location /login {
        limit_req zone=login burst=5 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

#### **Enable Site**
```bash
sudo ln -s /etc/nginx/sites-available/coffpos /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### **PHP-FPM Configuration**

#### **Optimize PHP-FPM**
```bash
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

```ini
[www]
user = www-data
group = www-data
listen = /var/run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.process_idle_timeout = 10s
pm.max_requests = 500
```

#### **Optimize PHP Configuration**
```bash
sudo nano /etc/php/8.1/fpm/php.ini
```

```ini
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
max_input_vars = 3000
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

```bash
sudo systemctl restart php8.1-fpm
```

## 🔒 **SSL Certificate Setup**

### **Using Let's Encrypt (Certbot)**

#### **Install Certbot**
```bash
sudo apt install certbot python3-certbot-nginx -y
```

#### **Obtain SSL Certificate**
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

#### **Auto-renewal Setup**
```bash
sudo crontab -e
```

Add this line:
```bash
0 12 * * * /usr/bin/certbot renew --quiet
```

#### **Test Auto-renewal**
```bash
sudo certbot renew --dry-run
```

## 📊 **Monitoring & Maintenance**

### **1. Log Monitoring**

#### **Setup Log Rotation**
```bash
sudo nano /etc/logrotate.d/coffpos
```

```
/var/www/coffpos/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 coffpos coffpos
    postrotate
        php /var/www/coffpos/artisan config:cache
    endscript
}
```

#### **Monitor Application Logs**
```bash
tail -f /var/www/coffpos/storage/logs/laravel.log
```

### **2. Database Backup**

#### **Create Backup Script**
```bash
nano /home/coffpos/backup-db.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/coffpos/backups"
DB_NAME="coffpos"
DB_USER="coffpos_user"
DB_PASS="your_secure_password_here"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/coffpos_$DATE.sql

# Keep only last 7 days of backups
find $BACKUP_DIR -name "coffpos_*.sql" -mtime +7 -delete

echo "Database backup completed: coffpos_$DATE.sql"
```

```bash
chmod +x /home/coffpos/backup-db.sh
```

#### **Schedule Daily Backups**
```bash
crontab -e
```

Add:
```bash
0 2 * * * /home/coffpos/backup-db.sh
```

### **3. Performance Monitoring**

#### **Install System Monitoring Tools**
```bash
sudo apt install htop iotop nethogs -y
```

#### **Monitor Application Performance**
```bash
# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# Check Nginx status
sudo systemctl status nginx

# Check MySQL status
sudo systemctl status mysql

# Monitor system resources
htop
```

## 🔧 **Troubleshooting**

### **Common Issues**

#### **Permission Errors**
```bash
sudo chown -R coffpos:www-data /var/www/coffpos
sudo chmod -R 775 /var/www/coffpos/storage
sudo chmod -R 775 /var/www/coffpos/bootstrap/cache
```

#### **Database Connection Issues**
```bash
# Test database connection
mysql -u coffpos_user -p coffpos
```

#### **PHP-FPM Issues**
```bash
# Check PHP-FPM logs
sudo tail -f /var/log/php8.1-fpm.log

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

#### **Nginx Issues**
```bash
# Check Nginx configuration
sudo nginx -t

# Check Nginx logs
sudo tail -f /var/log/nginx/error.log

# Restart Nginx
sudo systemctl restart nginx
```

#### **Clear Application Cache**
```bash
cd /var/www/coffpos
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### **Performance Issues**

#### **Optimize Database**
```sql
-- Run these queries in MySQL
OPTIMIZE TABLE products;
OPTIMIZE TABLE transactions;
OPTIMIZE TABLE transaction_items;
ANALYZE TABLE products;
ANALYZE TABLE transactions;
```

#### **Monitor Slow Queries**
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Add:
```ini
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

## 📈 **Post-Deployment Verification**

### **1. Functionality Tests**

#### **Test Authentication**
- [ ] Admin login works
- [ ] Manager login works  
- [ ] Cashier login works
- [ ] Password reset works

#### **Test Core Features**
- [ ] Dashboard loads correctly
- [ ] POS system functional
- [ ] Product management works
- [ ] Customer management works
- [ ] Reports generate correctly
- [ ] PDF export works
- [ ] Image uploads work

#### **Test Performance**
- [ ] Page load times < 3 seconds
- [ ] Database queries optimized
- [ ] Images load quickly
- [ ] PDF generation works

### **2. Security Verification**

#### **Security Checklist**
- [ ] HTTPS enabled and working
- [ ] Security headers configured
- [ ] File permissions correct
- [ ] Database user has minimal privileges
- [ ] Sensitive files protected
- [ ] Error pages don't reveal sensitive info

#### **Security Tests**
```bash
# Test file permissions
ls -la /var/www/coffpos/.env

# Test database access
mysql -u coffpos_user -p

# Test HTTPS redirect
curl -I http://your-domain.com
```

## 🚀 **Go Live Checklist**

### **Final Steps**
- [ ] All tests passed
- [ ] Backup created
- [ ] Monitoring configured
- [ ] SSL certificate installed
- [ ] DNS configured
- [ ] Performance optimized
- [ ] Security verified
- [ ] Documentation updated
- [ ] Team trained
- [ ] Support contacts ready

### **Launch Day**
1. **Final backup** of database and files
2. **Monitor logs** for any errors
3. **Test all critical functions**
4. **Monitor performance** metrics
5. **Be ready for support** requests

## 📞 **Support & Maintenance**

### **Regular Maintenance Tasks**

#### **Daily**
- Monitor application logs
- Check system resources
- Verify backups completed

#### **Weekly**
- Review performance metrics
- Check for security updates
- Clean up old log files

#### **Monthly**
- Update system packages
- Review database performance
- Test backup restoration
- Security audit

### **Emergency Contacts**
- **System Administrator**: your-admin@company.com
- **Database Administrator**: your-dba@company.com
- **Application Developer**: your-dev@company.com

---

## 📊 **Deployment Summary**

**Deployment completed successfully!** 🎉

Your CoffPOS application is now running in production with:
- ✅ Secure HTTPS configuration
- ✅ Optimized performance settings
- ✅ Automated backups
- ✅ Monitoring and logging
- ✅ Security hardening

**Application URL**: https://your-domain.com  
**Admin Panel**: https://your-domain.com/admin  
**POS System**: https://your-domain.com/pos

---

*Last Updated: December 2025*  
*Version: 1.0.0*