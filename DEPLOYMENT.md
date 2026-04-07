# VexaMart POS - Deployment Guide

## Production Checklist

### 1. Server Requirements
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Composer 2.5+
- Redis (optional for caching)

### 2. Environment Setup
```bash
# Clone repository
git clone <repository-url>
cd VexaMart

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install
npm run build

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vexamart
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --class=UserSeeder

# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache