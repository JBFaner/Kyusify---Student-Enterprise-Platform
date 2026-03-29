#!/bin/bash
set -e


# Run migrations
php artisan migrate --force --no-interaction

# Seed admin user
php artisan db:seed --class=AdminSeeder --force --no-interaction

# Cache config/routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground
