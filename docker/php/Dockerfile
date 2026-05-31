FROM php:8.4-fpm-alpine

# Dipendenze di sistema
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Estensioni PHP
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    pcntl \
    opcache

# Redis extension
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia sorgenti
COPY . .

# Installa dipendenze PHP e JS
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm ci && npm run build && rm -rf node_modules

# Permessi storage
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]