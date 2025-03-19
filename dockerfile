# Use the official PHP image as a base
FROM php:8.0-apache

# Install Python (and pip) alongside PHP
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    && apt-get clean

# Install any Python dependencies you need (e.g., prophet, pandas, etc.)
COPY requirements.txt /tmp/
RUN pip3 install -r /tmp/requirements.txt

# Enable Apache mod_rewrite if you need URL rewrites
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy your PHP and Python files into the container
COPY . /var/www/html/

# Set file permissions (adjust as needed)
RUN chown -R www-data:www-data /var/www/html

# Expose port 8080 for Railway
EXPOSE 8080

# Update Apache to listen on port 8080
RUN echo "Listen 8080" >> /etc/apache2/ports.conf

# Update Apache virtual host to listen on port 8080
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf

# Start Apache in the foreground
CMD ["apache2-foreground"]
