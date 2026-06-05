FROM php:8.4-fpm-alpine

# Install sistem dependencies & ekstensi PHP yang dibutuhkan Laravel
RUN apk add --no-cache nginx wget supervisor bzip2 build-base nodejs npm postgresql-dev
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy semua file backend ke dalam container
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Atur izin folder storage Laravel
RUN chmod -R 777 storage bootstrap/cache

# Copy konfigurasi nginx (jika ada, atau gunakan default)
EXPOSE 80

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=80
