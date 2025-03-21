# Use an official Python image
FROM python:3.10

# Set the working directory
WORKDIR /app

# Copy and install Python dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Install Nginx
RUN apt-get update && apt-get install -y nginx

# Remove default Nginx site and copy custom config
RUN rm /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/nginx.conf

# Create required log directory
RUN mkdir -p /var/log/nginx && touch /var/log/nginx/error.log /var/log/nginx/access.log

# Expose port 8080
EXPOSE 8080

# Start Nginx
CMD ["nginx", "-g", "daemon off;"]
