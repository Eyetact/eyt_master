#!/bin/sh
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down ) || true
    # Update codebase
    git pull origin main

    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Migrate database
    php artisan migrate --force


# Exit maintenance mode
php artisan up

echo "Application deployed!"
