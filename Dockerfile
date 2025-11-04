# Базовий PHP 8.4 з FPM
FROM php:8.4-fpm

# Системні залежності
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath exif \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Робоча директорія
WORKDIR /var/www/html

# Копіюємо код
COPY . .

# Встановлюємо залежності
RUN composer install --optimize-autoloader --no-dev

# Права на storage і cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Виставляємо порт для Railway
EXPOSE 8080

# Вбудований PHP сервер (прямий доступ через Railway edge)
CMD php -S 0.0.0.0:8080 -t public
