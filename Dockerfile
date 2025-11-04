# ---------- Stage 1: Build ----------
FROM php:8.2-fpm AS build

# Встановлюємо системні пакети
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Встановлюємо composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копіюємо файли проекту
COPY . .

# Встановлюємо PHP-залежності
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Встановлюємо Node.js (для build frontend)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && apt-get install -y nodejs

# Будуємо assets
RUN npm install && npm run build

# ---------- Stage 2: Runtime ----------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Копіюємо лише готовий код з першого етапу
COPY --from=build /var/www/html /var/www/html

# Кешування конфігів і маршрутів
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Railway автоматично створює змінну PORT, тому слухаємо її
EXPOSE ${PORT}

# Запускаємо Laravel через вбудований сервер
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
