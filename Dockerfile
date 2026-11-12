# Ubah ke 8.2 sesuai pesan commit kamu sebelumnya agar aman dengan composer.json
FROM php:8.2-apache

# Ekstensi yang dibutuhkan Laravel + Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin kode, install dependency (tanpa dev, tanpa script yang butuh .env)
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Apache harus mengarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Buat struktur direktori storage dan cache secara paksa agar tidak error missing folder
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache

# Izin folder storage dan cache
RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]