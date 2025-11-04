# ---------- Base image ----------
FROM php:8.2-fpm

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
    libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip git curl nginx supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

#####################################
# Install Composer
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
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && chown -R www-data:www-data storage bootstrap/cache

#####################################
# Configure Nginx
#####################################
RUN rm /etc/nginx/sites-enabled/default
COPY ./docker/nginx.conf /etc/nginx/sites-enabled/laravel.conf

#####################################
# Supervisor config to run PHP-FPM + Nginx
#####################################
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#####################################
# Expose port
#####################################
EXPOSE 8080

#####################################
# Start supervisor
#####################################
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
