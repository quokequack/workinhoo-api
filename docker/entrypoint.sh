#!/bin/sh
set -e

if [ ! -f "vendor/autoload.php" ]; then
    echo "[entrypoint] Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

echo "[entrypoint] Generating app key if missing..."
php artisan key:generate --force

echo "[entrypoint] Running migrations..."
php artisan migrate --force

echo "[entrypoint] Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[entrypoint] Ready."

exec "$@"
