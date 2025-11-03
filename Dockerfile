# ---------- Stage 1: Build ----------
FROM php:8.2-fpm AS build

# Install system deps
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

# ---------- Stage 2: Runtime ----------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Copy from build
COPY --from=build /var/www/html /var/www/html

# Expose port
EXPOSE 8080

# Serve using PHP built-in server
CMD php -S 0.0.0.0:8080 -t public
