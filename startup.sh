#!/bin/bash

# Go to the app directory
cd /home/site/wwwroot

# Ensure PHP dependencies are installed
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Cache Laravel configuration and routes
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations (optional — remove if you manage DB externally)
echo "Running migrations..."
php artisan migrate --force

# Install and build frontend assets
echo "Installing Node modules and building assets..."
npm install
npm run build

# Done! Azure will serve your app via Apache/Nginx with PHP
echo "Startup complete. Laravel app is ready!"
