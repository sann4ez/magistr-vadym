# Базовий образ PHP-FPM
FROM php:8.2-fpm

# Встановлюємо системні залежності та PHP-розширення
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean

# Встановлюємо Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Робоча директорія
WORKDIR /var/www

# Копіюємо проект
COPY . .

# Встановлюємо права на storage і bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Встановлюємо PHP залежності
RUN composer install --no-dev --optimize-autoloader

# Експортуємо порт FPM
EXPOSE 9000

# Виконуємо міграції перед стартом FPM
CMD php artisan migrate --force && php-fpm
