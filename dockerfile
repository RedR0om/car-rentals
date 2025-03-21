# Use an official Python image
FROM python:3.10

# Set the working directory
WORKDIR /app

# Copy requirements file and install dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application files
COPY . .

# Expose port 8080 (required by Railway)
EXPOSE 8080

# Run Python app (make sure your app runs on 0.0.0.0:8080)
CMD ["python", "app.py"]
