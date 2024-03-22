# Use the official PHP image from Docker Hub
FROM php:7.4-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP files into the container
COPY . /var/www/html/

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose port 80 (default HTTP port)
EXPOSE 80
