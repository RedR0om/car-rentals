# Use the official PHP image as a base
FROM php:8.0-apache

# Install Python 3.10 and pip
RUN apt-get update && apt-get install -y \
    python3.10 \
    python3.10-pip \
    python3.10-dev \
    && apt-get clean

# Install dependencies
COPY requirements.txt /tmp/
RUN python3.10 -m pip install -r /tmp/requirements.txt

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy files into container
COPY . /var/www/html/

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port for Railway
EXPOSE 8080

# Update Apache to listen on port 8080
RUN echo "Listen 8080" >> /etc/apache2/ports.conf
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf

# Start Apache
CMD ["apache2-foreground"]
