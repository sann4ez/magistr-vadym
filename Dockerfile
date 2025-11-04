# ---------- Stage 1: Build ----------
FROM php:8.2-fpm AS build

# Встановлюємо системні залежності для Laravel та Node
RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Встановлюємо Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копіюємо тільки залежності Laravel для кешування
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Копіюємо весь проект
COPY . .

# Збираємо фронтенд
RUN npm install && npm run build

# Кешуємо Laravel (config, route, view)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# ---------- Stage 2: Runtime ----------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Копіюємо зібраний проект та всі кеші
COPY --from=build /var/www/html /var/www/html

# Встановлюємо мінімальні runtime-залежності
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Виставляємо права на storage і bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Порт для php-fpm (Nginx/Apache буде проксувати)
EXPOSE 9000

# Запуск php-fpm
CMD ["php-fpm"]
