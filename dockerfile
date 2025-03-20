# Use the PHP-FPM image based on Debian Bookworm
FROM php:8.0-fpm-bookworm

# Install system dependencies for Python, pystan, and Prophet
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    python3.10 \
    python3.10-pip \
    python3.10-dev \
    build-essential \
    gfortran \
    libatlas-base-dev \
    liblapack-dev \
    libblas-dev \
    wget \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Manually install GLIBC 2.38 (if required)
RUN wget http://ftp.gnu.org/gnu/libc/glibc-2.38.tar.gz && \
    tar -xvzf glibc-2.38.tar.gz && \
    cd glibc-2.38 && \
    mkdir build && cd build && \
    ../configure --prefix=/opt/glibc-2.38 && \
    make -j$(nproc) && \
    make install && \
    cd / && rm -rf glibc-2.38 glibc-2.38.tar.gz

# Set environment to use the new GLIBC version
ENV LD_LIBRARY_PATH=/opt/glibc-2.38/lib:$LD_LIBRARY_PATH

# Set MPLCONFIGDIR to a writable location
ENV MPLCONFIGDIR=/tmp/matplotlib
RUN mkdir -p /tmp/matplotlib && chmod -R 777 /tmp/matplotlib

# Set up Python virtual environment
RUN python3.10 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"
RUN python3.10 -m pip install --upgrade pip setuptools wheel

# Install numpy separately before Prophet to avoid conflicts
RUN python3.10 -m pip install --no-cache-dir numpy==1.26.4

# Install pystan separately to ensure it compiles correctly
RUN python3.10 -m pip install --no-cache-dir pystan==2.19.1.1

# Copy requirements.txt
COPY requirements.txt /tmp/

# Install remaining Python dependencies
RUN python3.10 -m pip install --no-cache-dir -r /tmp/requirements.txt

# Set the working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Copy and enable Nginx configuration
COPY default.conf /etc/nginx/sites-enabled/default

# Expose port 8080 for Railway
EXPOSE 8080

# Start PHP-FPM and Nginx properly using a process manager
CMD service php8.0-fpm start && nginx -g "daemon off;"
