# Use the official PHP image as a base
FROM php:8.0-apache

# Install Python 3.10 and pip3
RUN apt-get update && apt-get install -y \
    python3.10 \
    python3.10-pip \
    python3.10-dev \
    && apt-get clean

# Install Python dependencies from requirements.txt
COPY requirements.txt /tmp/
RUN python3.10 -m pip install -r /tmp/requirements.txt

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy all files from your local directory into the container
COPY . /var/www/html/

# Set file permissions so Apache can read/write
RUN chown -R www-data:www-data /var/www/html

# Expose port 8080 for Railway
EXPOSE 8080

# Update Apache to listen on port 8080
RUN echo "Listen 8080" >> /etc/apache2/ports.conf
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf

# Start Apache in the foreground
CMD ["apache2-foreground"]
