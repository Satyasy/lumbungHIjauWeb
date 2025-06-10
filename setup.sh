#!/bin/bash

echo "🚀 Setup Admin Laravel 12 Docker Application"

# Membuat direktori yang diperlukan
echo "📁 Membuat direktori konfigurasi..."
mkdir -p docker/nginx
mkdir -p docker/supervisor
mkdir -p docker/redis
mkdir -p storage/logs

# Copy file .env jika belum ada
if [ ! -f .env ]; then
    echo "📝 Menyalin file .env..."
    cp .env.example .env
fi

# Build dan jalankan container
echo "🐳 Building Docker containers..."
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Tunggu beberapa detik untuk service startup
echo "⏳ Menunggu services startup..."
sleep 10

# Setup Laravel
echo "🔧 Setup Laravel application..."
docker exec laravel_app composer install --optimize-autoloader --no-dev
docker exec laravel_app php artisan key:generate
docker exec laravel_app php artisan config:cache
docker exec laravel_app php artisan route:cache
docker exec laravel_app php artisan view:cache

# Jalankan migrasi
echo "📊 Menjalankan database migrations..."
docker exec laravel_app php artisan migrate --force

# Set permissions
echo "🔐 Mengatur permissions..."
docker exec laravel_app chown -R www-data:www-data /var/html
docker exec laravel_app chmod -R 755 /var/www/storage
docker exec laravel_app chmod -R 755 /var/www/bootstrap/cache

echo "✅ Setup completed!"
echo ""
echo "📱 Aplikasi berjalan di:"
echo "   - Laravel App: http://localhost:8000"
echo "   - phpMyAdmin: http://localhost:8080"
echo "   - MySQL: localhost:3306"
echo "   - Redis: localhost:6379"
echo ""
echo "🔍 Untuk melihat logs:"
echo "   docker-compose logs -f laravel-app"
echo ""
echo "🛑 Untuk stop aplikasi:"
echo "   docker-compose down"