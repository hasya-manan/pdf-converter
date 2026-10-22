#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Creating SQLite database..."
touch /var/www/html/database/database.sqlite

echo "Fixing permissions..."
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/database

echo "Linking storage..."
php artisan storage:link

# Use optimize:clear to wipe EVERYTHING
echo "Clearing all caches..."
php artisan optimize:clear

echo "Caching new config..."
php artisan config:cache

echo "================ ROUTE LIST ================"
php artisan route:list
echo "============================================"

echo "Caching new routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force