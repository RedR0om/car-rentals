import json
import sys
import platform
import subprocess

# Get Python version
python_version = platform.python_version()

# Get installed Python packages
try:
    installed_packages = subprocess.check_output([sys.executable, "-m", "pip", "freeze"]).decode("utf-8").split("\n")
except Exception as e:
    installed_packages = [str(e)]

# Prepare JSON response
data = {
    "python_version": python_version,
    "installed_packages": installed_packages,
    "message": "Hello from Python!",
    "status": "success"
}

# Print JSON output
print(json.dumps(data))
sys.stdout.flush()
