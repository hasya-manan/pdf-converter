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

# CRITICAL FIX: Clear old caches first
echo "Clearing old config and route caches..."
php artisan config:clear
php artisan route:clear 

echo "Caching new config..."
php artisan config:cache

echo "Caching new routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force