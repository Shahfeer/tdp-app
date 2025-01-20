# Use an official PHP image with Apache
FROM php:7.4-apache

# Install necessary PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite
######## Add the GD extension for PHP
RUN apt-get update && apt-get install -y libpng-dev \
    && docker-php-ext-install gd

# Install Composer
COPY composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

###### Set the working directory inside the container
WORKDIR /var/www/html

# Copy the project files into the container
COPY . .
COPY .env /var/www/html/.env
###environment variables
ENV DB_CONNECTION=mysql
ENV DB_HOST=db
ENV DB_PORT=3306
ENV DB_DATABASE=tdp_call
ENV DB_USERNAME=admin
ENV DB_PASSWORD=Password@123

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader

################### Set file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for the web server
EXPOSE 80
### Start Apache server ok
CMD ["apache2-foreground"]

