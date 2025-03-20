import json
import sys

# Ensure the script flushes output properly
sys.stdout.reconfigure(encoding='utf-8')

# Simulated JSON data
data = {
    "message": "Hello from Python!",
    "status": "success",
    "numbers": [1, 2, 3, 4, 5]
}

# Print JSON output
print(json.dumps(data))
sys.stdout.flush()  # Ensure Python flushes output immediately
