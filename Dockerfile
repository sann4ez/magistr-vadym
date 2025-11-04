# ---------- Base PHP-FPM ----------
ARG PHP_VERSION=8.2-fpm
FROM php:${PHP_VERSION}

# ---------- Timezone ----------
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# ---------- Install PHP extensions ----------
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# ---------- Install Composer ----------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---------- Set working directory ----------
WORKDIR /var/www/html

# ---------- Copy project ----------
COPY . .

# ---------- Install PHP dependencies ----------
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && chown -R www-data:www-data storage bootstrap/cache

# ---------- Expose PHP-FPM port ----------
EXPOSE 9000

# ---------- Start PHP-FPM ----------
CMD ["php-fpm"]
