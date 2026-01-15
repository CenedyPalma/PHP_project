FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy all files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Set permissions for cache/logs
RUN mkdir -p var/cache var/log && chmod -R 777 var/

# Expose port (Railway sets PORT env variable)
EXPOSE 8080

# Simple start command - just start the server immediately
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
