#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

# === New: Build frontend assets ===
echo "Installing Node dependencies..."
npm install

echo "Building assets..."
npm run build
# ==============================

echo "Clearing old config cache..."
php artisan config:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force