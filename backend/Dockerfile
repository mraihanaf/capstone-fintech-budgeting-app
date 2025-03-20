# Use FrankenPHP as the base image
FROM dunglas/frankenphp:latest

# Set working directory
WORKDIR /app

# Install dependencies using apt-get (Debian-based)
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel directories
RUN chmod -R 775 storage bootstrap/cache

# Expose port for Laravel Octane
EXPOSE 80

# Command to run Laravel Octane with FrankenPHP and hot reload
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
