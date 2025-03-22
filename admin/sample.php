<?php
// Define the path to your Python script
$pythonScript = "ai_models/mysql-prophet-maintenance-forecasting.py";

// Run the Python script and capture output
$output = shell_exec("python3 " . escapeshellarg($pythonScript));

// Decode JSON response from Python script
$response = json_decode($output, true);

// Check if response is valid JSON
if (json_last_error() === JSON_ERROR_NONE) {
    // Send JSON response back to the client
    header("Content-Type: application/json");
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    // Handle error if Python script fails
    header("Content-Type: application/json");
    echo json_encode(["error" => "Failed to execute Python script or invalid JSON output"]);
}
?>  
