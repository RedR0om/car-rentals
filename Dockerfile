# Step 1: Use a base image that has PHP and Apache installed
FROM php:7.4-apache

# Step 2: Install dependencies for Python (including pip and libraries needed for your Python models)
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-dev \
    libmariadb-dev-compat \
    libmariadb-dev \
    && rm -rf /var/lib/apt/lists/*

# Step 3: Install Python dependencies by copying the 'requirements.txt' and running pip
# Since requirements.txt is now in the same directory as the Dockerfile, update the path
COPY requirements.txt /requirements.txt
RUN pip install -r /requirements.txt

# Step 4: Copy the PHP files into the Apache directory
COPY admin/php /var/www/html/

# Step 5: Copy the Python files (your models) into the image
COPY admin/ai_models /admin/ai_models

# Step 6: Expose port 80 (default for Apache)
EXPOSE 80

# Step 7: Start Apache and keep the container running
CMD ["apache2-foreground"]
