#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Creating SQLite database if it doesn't exist..."
touch /var/www/html/database/database.sqlite

echo "Clearing old config cache..."
php artisan config:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force