# Use the official PHP-FPM image
FROM php:8.0-fpm

# Set environment variables
ENV GLIBC_VERSION=2.38

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    python3.10 \
    python3.10-pip \
    python3.10-dev \
    wget \
    build-essential \
    manpages-dev \
    && apt-get clean

# Download and install glibc
RUN wget http://ftp.gnu.org/gnu/libc/glibc-${GLIBC_VERSION}.tar.gz && \
    tar -xvzf glibc-${GLIBC_VERSION}.tar.gz && \
    cd glibc-${GLIBC_VERSION} && \
    mkdir build && cd build && \
    ../configure --prefix=/opt/glibc-${GLIBC_VERSION} && \
    make -j$(nproc) && \
    make install && \
    cd / && rm -rf glibc-${GLIBC_VERSION}*

# Set library path to use the new glibc
ENV LD_LIBRARY_PATH=/opt/glibc-${GLIBC_VERSION}/lib:$LD_LIBRARY_PATH

# Install Python dependencies from requirements.txt
COPY requirements.txt /tmp/
RUN python3.10 -m pip install --no-cache-dir -r /tmp/requirements.txt

# Install CmdStan
RUN python3.10 -m pip install cmdstanpy && \
    python3.10 -c "import cmdstanpy; cmdstanpy.install_cmdstan(dir='/tmp/cmdstan', overwrite=True)"

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
