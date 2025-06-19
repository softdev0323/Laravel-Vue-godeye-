# Use PHP 8.2 FPM as base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gnupg2 curl unzip git zip libzip-dev libonig-dev sqlite3 libsqlite3-dev \
    supervisor nginx \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .
COPY .env.local .env
# Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies and build frontend
RUN npm install && npm run build

RUN mkdir -p storage/database && \
    touch storage/database/database.sqlite && \
    chmod -R 775 storage && \
    chmod 666 storage/database/database.sqlite

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

RUN php artisan key:generate && php artisan config:cache && php artisan migrate --force && php artisan storage:link && php artisan route:cache && php artisan view:cache
# NGINX site config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Supervisor config
COPY docker/supervisord.conf /etc/supervisord.conf

# Expose HTTP port
EXPOSE 80

# Start all services
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
