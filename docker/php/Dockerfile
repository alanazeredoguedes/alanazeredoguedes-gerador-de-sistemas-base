FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set TimeZone
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN sed -ri -e 's!;date.timezone =!date.timezone = "America/Sao_Paulo"!g' /usr/local/etc/php/php.ini

# Set working directory
#WORKDIR /var/www/html/