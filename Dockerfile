# базовий образ
FROM php:8.2-fpm

# встановлення системних залежностей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# встановити Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# встановити Node.js (для ваших npm/asset build)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

WORKDIR /var/www/html

# копіюємо всі файли
COPY . .

# встановлюємо залежності та будимо
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# artisan команди перед запуском
# (мigrate, storage:link тощо — можна робити через entrypoint)
RUN php artisan migrate --force \
    && php artisan storage:link

# запускаємо PHP-FPM (або artisan serve) — Railway очікує, що буде служба на $PORT
ENV PORT 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=$PORT"]
