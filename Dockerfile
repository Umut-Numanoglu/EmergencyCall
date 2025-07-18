FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create www-data user with proper UID/GID
RUN usermod -u 1001 www-data && groupmod -g 1001 www-data

# Set working directory
WORKDIR /var/www/html

# Create composer cache directory with proper permissions
RUN mkdir -p /var/www/.composer/cache && chown -R www-data:www-data /var/www/.composer

# Copy existing application directory contents
COPY . /var/www/html

# Set proper ownership
RUN chown -R www-data:www-data /var/www/html

# Configure git to trust the directory
RUN git config --system --add safe.directory /var/www/html

# Change current user to www-data
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"] 