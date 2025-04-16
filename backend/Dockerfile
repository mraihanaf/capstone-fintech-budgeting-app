FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl \
    && apt-get clean

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel project
COPY . .

# Create SQLite database file
RUN touch database/database.sqlite

# Set correct permissions for the database file
RUN chown -R www-data:www-data /var/www/database && chmod -R 775 /var/www/database

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate

# Run migrations to set up the database schema
RUN php artisan migrate --force

# Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy Supervisor config to run Nginx and PHP-FPM
COPY docker/supervisord.conf /etc/supervisord.conf

# Expose port 80
EXPOSE 8000

# Command to run Supervisor to manage PHP-FPM and Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
