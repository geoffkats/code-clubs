# Deployment Guide

## 🚀 Production Deployment

This guide will help you deploy the CodeClub Management System to a production environment.

## Prerequisites

- **Server**: Ubuntu 20.04+ or CentOS 8+
- **PHP**: 8.2 or higher
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Nginx or Apache
- **SSL Certificate**: Let's Encrypt or commercial certificate
- **Domain**: Your domain name pointing to the server

## Server Setup

### 1. Update System
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install PHP 8.2
```bash
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl
```

### 3. Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 4. Install Node.js
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 5. Install Database
```bash
# MySQL
sudo apt install mysql-server
sudo mysql_secure_installation

# Or PostgreSQL
sudo apt install postgresql postgresql-contrib
```

### 6. Install Nginx
```bash
sudo apt install nginx
sudo systemctl enable nginx
sudo systemctl start nginx
```

## Application Deployment

### 1. Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/yourusername/codeclub-system.git
sudo chown -R www-data:www-data codeclub-system
cd codeclub-system
```

### 2. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 3. Environment Configuration
```bash
cp .env.example .env
nano .env
```

**Production .env Configuration:**
```env
APP_NAME="CodeClub Management System"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=codeclub_production
DB_USERNAME=codeclub_user
DB_PASSWORD=your-secure-password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="CodeClub System"
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Database Setup
```bash
php artisan migrate --force
php artisan db:seed
```

### 6. Storage Setup
```bash
php artisan storage:link
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
```

### 7. Cache Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Nginx Configuration

### 1. Create Site Configuration
```bash
sudo nano /etc/nginx/sites-available/codeclub
```

**Nginx Configuration:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/codeclub-system/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Security headers
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Robots-Tag "noindex, nofollow";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
}
```

### 2. Enable Site
```bash
sudo ln -s /etc/nginx/sites-available/codeclub /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## SSL Certificate (Let's Encrypt)

### 1. Install Certbot
```bash
sudo apt install certbot python3-certbot-nginx
```

### 2. Obtain Certificate
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 3. Auto-renewal
```bash
sudo crontab -e
# Add this line:
0 12 * * * /usr/bin/certbot renew --quiet
```

## Redis Installation

### 1. Install Redis
```bash
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

### 2. Configure Redis
```bash
sudo nano /etc/redis/redis.conf
# Set: requirepass your-redis-password
sudo systemctl restart redis-server
```

## Queue Worker Setup

### 1. Create Systemd Service
```bash
sudo nano /etc/systemd/system/codeclub-worker.service
```

**Service Configuration:**
```ini
[Unit]
Description=CodeClub Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/codeclub-system/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
WorkingDirectory=/var/www/codeclub-system

[Install]
WantedBy=multi-user.target
```

### 2. Enable and Start Service
```bash
sudo systemctl enable codeclub-worker
sudo systemctl start codeclub-worker
```

## Monitoring & Logs

### 1. Log Rotation
```bash
sudo nano /etc/logrotate.d/codeclub
```

**Log Rotation Configuration:**
```
/var/www/codeclub-system/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
    postrotate
        /bin/kill -USR1 `cat /var/run/php8.2-fpm.pid 2> /dev/null` 2> /dev/null || true
    endscript
}
```

### 2. Health Check Script
```bash
nano /var/www/codeclub-system/health-check.sh
```

**Health Check Script:**
```bash
#!/bin/bash
# Health check for CodeClub System

# Check if application is responding
if curl -f -s https://yourdomain.com/health > /dev/null; then
    echo "✅ Application is healthy"
    exit 0
else
    echo "❌ Application is not responding"
    exit 1
fi
```

```bash
chmod +x /var/www/codeclub-system/health-check.sh
```

## Backup Strategy

### 1. Database Backup Script
```bash
nano /var/www/codeclub-system/backup-db.sh
```

**Database Backup Script:**
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/codeclub"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="codeclub_production"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u codeclub_user -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Keep only last 7 days of backups
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete

echo "Database backup completed: db_backup_$DATE.sql.gz"
```

### 2. File Backup Script
```bash
nano /var/www/codeclub-system/backup-files.sh
```

**File Backup Script:**
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/codeclub"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup storage directory
tar -czf $BACKUP_DIR/storage_backup_$DATE.tar.gz -C /var/www/codeclub-system storage/

# Keep only last 7 days of backups
find $BACKUP_DIR -name "storage_backup_*.tar.gz" -mtime +7 -delete

echo "File backup completed: storage_backup_$DATE.tar.gz"
```

### 3. Schedule Backups
```bash
sudo crontab -e
# Add these lines:
0 2 * * * /var/www/codeclub-system/backup-db.sh
0 3 * * * /var/www/codeclub-system/backup-files.sh
```

## Security Hardening

### 1. Firewall Configuration
```bash
sudo ufw enable
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw allow 3306/tcp  # MySQL (if external access needed)
```

### 2. PHP Security
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

**Key PHP Settings:**
```ini
expose_php = Off
allow_url_fopen = Off
allow_url_include = Off
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 30
memory_limit = 256M
```

### 3. File Permissions
```bash
sudo chown -R www-data:www-data /var/www/codeclub-system
sudo find /var/www/codeclub-system -type f -exec chmod 644 {} \;
sudo find /var/www/codeclub-system -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/codeclub-system/storage
sudo chmod -R 775 /var/www/codeclub-system/bootstrap/cache
```

## Performance Optimization

### 1. OPcache Configuration
```bash
sudo nano /etc/php/8.2/fpm/conf.d/10-opcache.ini
```

**OPcache Settings:**
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### 2. Nginx Caching
Add to your Nginx configuration:
```nginx
# Cache static assets
location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}

# Cache API responses
location /api/ {
    proxy_cache api_cache;
    proxy_cache_valid 200 1h;
    proxy_cache_use_stale error timeout updating http_500 http_502 http_503 http_504;
}
```

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/codeclub-system
   sudo chmod -R 775 storage bootstrap/cache
   ```

2. **Queue Not Processing**
   ```bash
   sudo systemctl restart codeclub-worker
   sudo systemctl status codeclub-worker
   ```

3. **Cache Issues**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Storage Link Issues**
   ```bash
   php artisan storage:link
   ```

## Maintenance

### Daily Tasks
- Monitor application logs
- Check queue worker status
- Verify backup completion

### Weekly Tasks
- Review security logs
- Update dependencies
- Performance monitoring

### Monthly Tasks
- Security updates
- Database optimization
- Log cleanup

---

**Deployment completed successfully! 🎉**

Your CodeClub Management System is now running in production with:
- ✅ SSL encryption
- ✅ Database backups
- ✅ Queue processing
- ✅ Performance optimization
- ✅ Security hardening
- ✅ Monitoring setup
