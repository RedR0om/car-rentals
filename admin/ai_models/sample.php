<?php
// index.php

// Check if the required GET parameters are provided.
if (!isset($_GET['last_maintenance']) || !isset($_GET['current_mileage']) || !isset($_GET['vehicleId'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Missing parameters. Please provide last_maintenance, current_mileage, and vehicleId.']);
    exit;
}

// Retrieve and sanitize the parameters.
$lastMaintenance = escapeshellarg($_GET['last_maintenance']);
$currentMileage = escapeshellarg($_GET['current_mileage']);
$vehicleId = escapeshellarg($_GET['vehicleId']);

// Build the command to run your Python script.
// Adjust "python3" to "python" if needed, depending on your server's configuration.
$command = "prophet-maintenance-forecasting.py $lastMaintenance $currentMileage $vehicleId";

// Execute the command and capture the output.
$output = shell_exec($command);

// Set the content type header to JSON.
header('Content-Type: application/json');

// Output the result from the Python script.
echo $output;
?>
