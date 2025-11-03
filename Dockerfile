FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js 22 (щоб npm не падав)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

EXPOSE 8080

CMD php -S 0.0.0.0:${PORT:-8080} -t public
