# Use PHP-FPM base image (Debian-based)
FROM php:8.0-fpm

# Install required dependencies in one step
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    python3.10 \
    python3.10-venv \
    python3-pip \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set up Python virtual environment
RUN python3.10 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Upgrade pip and install required Python packages
COPY requirements.txt /tmp/
RUN pip install --no-cache-dir --upgrade pip && \
    pip install --no-cache-dir -r /tmp/requirements.txt

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Set correct file permissions
RUN chown -R www-data:www-data /var/www/html

# Copy and enable Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Expose port 8080 for external access
EXPOSE 8080

# Start both PHP-FPM and Nginx using a process manager
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
