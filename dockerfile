# Use the PHP-FPM image based on Debian Bookworm
FROM php:8.0-fpm-bookworm

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    python3.10 \
    python3.10-pip \
    python3.10-dev \
    build-essential \
    wget \
    curl \
    && apt-get clean

# Manually install GLIBC 2.38
RUN wget http://ftp.gnu.org/gnu/libc/glibc-2.38.tar.gz && \
    tar -xvzf glibc-2.38.tar.gz && \
    cd glibc-2.38 && \
    mkdir build && cd build && \
    ../configure --prefix=/opt/glibc-2.38 && \
    make -j$(nproc) && \
    make install

# Set environment to use the new GLIBC version
ENV LD_LIBRARY_PATH=/opt/glibc-2.38/lib:$LD_LIBRARY_PATH

# Set MPLCONFIGDIR to a writable location
ENV MPLCONFIGDIR=/var/www/.matplotlib
RUN mkdir -p /var/www/.matplotlib && chmod -R 777 /var/www/.matplotlib

# Set up Python virtual environment
RUN python3.10 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"
RUN python3.10 -m pip install --upgrade pip

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
