# Базовий образ PHP 8.2 з FPM
FROM php:8.2-cli

# Встановлюємо залежності
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Створюємо робочу директорію
WORKDIR /var/www

# Копіюємо проект
COPY . .

# Встановлюємо права на storage і bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Встановлюємо залежності PHP
RUN composer install --no-dev --optimize-autoloader

# Виставляємо порт, який слухає контейнер
EXPOSE 8080

# Стартовий сервер Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
