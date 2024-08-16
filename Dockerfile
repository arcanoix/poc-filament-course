# i want dockerfile with php 8.3 and nginx configuration with postgresql and composer

# Pull the PHP image
FROM php:8.3

# Install tools & libraries
RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev unixodbc-dev gnupg

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy the application code
COPY . /app

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Expose the port
EXPOSE 8181

CMD ["php", "-S", "0.0.0.0:8181", "-t", "public"]
