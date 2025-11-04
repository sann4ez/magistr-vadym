# ---------- Base Laravel image ----------
ARG PHP_VERSION=8.2-fpm
FROM php:${PHP_VERSION}

#####################################
# Set timezone
#####################################
ARG TZ=UTC
ENV TZ=${TZ}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#####################################
# Install system dependencies
#####################################
RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

#####################################
# Install composer
#####################################
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

#####################################
# Set working directory
#####################################
WORKDIR /var/www/html

#####################################
# Copy project files
#####################################
COPY . .

#####################################
# Install PHP dependencies
#####################################
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

#####################################
# Build frontend
#####################################
RUN npm ci --silent && npm run build

#####################################
# Set permissions
#####################################
RUN chown -R www-data:www-data storage bootstrap/cache

#####################################
# Expose port for Railway
#####################################
EXPOSE 8080

#####################################
# Run Laravel dev server (or php-fpm)
#####################################
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
