# Use Bitnami Laravel development image
FROM bitnami/laravel:latest

# Set the working directory
WORKDIR /app

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install dependencies using Composer
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Set up Laravel
RUN cp .env.example .env \
    && php artisan key:generate \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R daemon:daemon storage bootstrap/cache

# Expose port 8000 for the Laravel server
EXPOSE 8000
