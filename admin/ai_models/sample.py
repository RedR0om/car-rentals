import json

# Simulated data
data = {
    "message": "Hello from Python!",
    "status": "success",
    "numbers": [1, 2, 3, 4, 5]
}

# Print JSON output for PHP to read
print(json.dumps(data))
