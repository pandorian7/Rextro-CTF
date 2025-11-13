FROM php:8.1-apache

# Enable mysqli
RUN docker-php-ext-install mysqli

# Copy app
COPY ./www /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
