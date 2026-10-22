#!/usr/bin/env sh
set -e

echo "Clearing old caches..."
php artisan optimize:clear

echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Creating SQLite database if it doesn't exist..."
touch /var/www/html/database/database.sqlite

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
php artisan storage:link

chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/public

echo "Starting Supervisor (Nginx + PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisord.conf