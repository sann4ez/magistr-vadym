FROM php:8.2-fpm

# Встановлюємо залежності
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean

WORKDIR /var/www

COPY . .

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

# Виставляємо порт FPM
EXPOSE 9000

# Запускаємо php-fpm
CMD ["php-fpm"]
