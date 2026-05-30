#!/bin/sh
set -e

if [ ! -f "vendor/autoload.php" ]; then
    echo "[entrypoint] Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

echo "[entrypoint] Running migrations..."
php artisan migrate --force

echo "[entrypoint] Ready."

exec "$@"
