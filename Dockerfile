# PHP 8.2 з FPM
FROM php:8.2-fpm

# Встановлюємо залежності
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    libonig-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Робоча директорія
WORKDIR /var/www

# Копіюємо файли проєкту
COPY . .

# Встановлюємо залежності Laravel
RUN composer install --no-dev --optimize-autoloader

# Права на storage та bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Порт
EXPOSE 8080

# Стартова команда
CMD php artisan serve --host=0.0.0.0 --port=8080
