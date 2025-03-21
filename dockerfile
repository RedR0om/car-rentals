# Use official PHP with FPM
FROM php:8.2-fpm

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    && docker-php-ext-install pcntl

# Remove default Nginx config and copy our custom one
RUN rm /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/nginx.conf

# Copy your PHP app into the container
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# No need to copy supervisord.conf

CMD ["php-fpm", "-F"]
