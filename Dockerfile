# ==============================================================================
# Multi-Stage Production Dockerfile for Laravel 13 + Inertia.js + Vite
# ==============================================================================

# ------------------------------------------------------------------------------
# Stage 1: Build Frontend Assets (Vite + Vue 3)
# ------------------------------------------------------------------------------
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copy package definitions first for layer caching
COPY package*.json ./
RUN npm ci --prefer-offline --no-audit

# Copy source code and build Vite assets
COPY . .
RUN npm run build

# ------------------------------------------------------------------------------
# Stage 2: Production PHP Runtime (PHP 8.3 + Nginx / PHP-FPM)
# ------------------------------------------------------------------------------
FROM php:8.3-fpm-alpine

# Set environment
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    NODE_ENV=production

# Install required system packages & libraries
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    sqlite-dev \
    nginx \
    supervisor

# Install required PHP extensions for Laravel 13 & SQLite
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_sqlite \
        mbstring \
        xml \
        bcmath \
        zip \
        pcntl \
        opcache \
        gd

# Install latest Composer 2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1. Copy composer definitions first
COPY composer.json composer.lock ./

# 2. Install dependencies without scripts (prevents package:discover failing before code is copied)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

# 3. Copy application codebase
COPY . .

# 4. Copy pre-built frontend assets from Node stage
COPY --from=node_builder /app/public/build ./public/build

# 5. Generate optimized autoloader and run Laravel package discovery
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative \
    && php artisan package:discover --ansi

# 6. Ensure required directories and SQLite file exist with proper permissions
RUN mkdir -p storage/framework/sessions \
             storage/framework/views \
             storage/framework/cache \
             storage/logs \
             bootstrap/cache \
             database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

# Expose HTTP port
EXPOSE 80

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
