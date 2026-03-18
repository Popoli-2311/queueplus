# Use official PHP + Apache image
FROM php:8.3-apache

# Install dependencies (if needed, e.g., mysqli, curl for SMS)
RUN docker-php-ext-install mysqli pdo_mysql

# Enable Apache mod_rewrite (common for PHP apps)
RUN a2enmod rewrite

# Copy your app files
COPY . /var/www/html/

# Set permissions (optional but good practice)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
