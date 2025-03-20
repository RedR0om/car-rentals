# Use the PHP-FPM image based on Debian Bookworm (which includes GLIBC 2.38)
FROM php:8.0-fpm-bookworm

# Install Nginx and Python 3.10
RUN apt-get update && apt-get install -y \
    nginx \
    python3.10 \
    python3.10-pip \
    python3.10-dev \
    && apt-get clean

# Set MPLCONFIGDIR to a writable location
ENV MPLCONFIGDIR=/tmp/matplotlib
RUN mkdir -p /tmp/matplotlib


# Create the MPLCONFIGDIR directory
RUN mkdir -p /tmp/matplotlib

# Install Python dependencies from requirements.txt
COPY requirements.txt /tmp/
RUN python3.10 -m pip install -r /tmp/requirements.txt

# Set the working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Copy Nginx configuration file
COPY default.conf /etc/nginx/sites-available/default

# Expose port 8080 for Railway
EXPOSE 8080

# Start both PHP-FPM and Nginx
CMD service php8.0-fpm start && nginx -g "daemon off;"
