FROM php:8.2-apache

# Install system dependencies for SQLite
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Redis extension and database extensions (MySQL + SQLite)
RUN pecl install redis && docker-php-ext-enable redis \
    && docker-php-ext-install mysqli pdo pdo_mysql pdo_sqlite

# Enable Apache modules
RUN a2enmod rewrite expires headers

# Set the document root to SkyBlue webroot
RUN sed -ri -e 's!/var/www/html!/var/www/webroot!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!/var/www/webroot!g' /etc/apache2/apache2.conf

# Update AllowOverride to allow .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www
