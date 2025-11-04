ARG PHP_VERSION=8.2-fpm
FROM php:${PHP_VERSION}

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
