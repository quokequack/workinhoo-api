# Stage 1: Composer dependencies
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Stage 2: Application image
FROM php:8.4-fpm-alpine AS app

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
        libpq-dev \
        icu-dev \
        icu-libs \
        linux-headers \
    && docker-php-ext-install \
        pdo_pgsql \
        pgsql \
        pcntl \
        bcmath \
        intl \
        opcache \
    && apk del --no-cache linux-headers \
    && rm -rf /tmp/*

# Copy PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install Composer (needed at runtime for dev with volume mounts)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create non-root user and PHP log directory
RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www \
    && mkdir -p /var/log/php \
    && chown www:www /var/log/php

WORKDIR /var/www

# Copy vendor from stage 1
COPY --from=vendor /app/vendor ./vendor

# Copy application code
COPY --chown=www:www . .

# Set writable permissions on Laravel directories
RUN chown -R www:www storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www

EXPOSE 8000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
