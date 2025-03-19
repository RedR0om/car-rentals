<?php
// No need for full paths for Python executable on Railway, use python3 or python
$pythonExecutable = 'python3';  // Or use 'python' if that's the default version
$baseDir = realpath(__DIR__ . '/admin/ai_models');  // Correcting the relative path to the script
$scriptPath = escapeshellarg($baseDir . "/sample.py");  // Make sure the path to your Python script is correct

// Construct the command to run the Python script
$command = "$pythonExecutable $scriptPath 2>&1";  // Capture both stdout and stderr

// Execute the Python script and capture the output
$output = shell_exec($command);

// Check if there is any output or error
if ($output === null) {
    echo "<p>Error: Failed to execute the Python script. Please check the server logs.</p>";
} else {
    // Decode the JSON output from the Python script
    $data = json_decode($output, true);

    // If JSON decoding fails, display the raw output for debugging
    if ($data === null) {
        echo "<p>Error: Failed to decode the Python script output as JSON. Raw output: </p><pre>$output</pre>";
    } else {
        // HTML layout and display
        echo "<div class='container'>";
        echo "<h1 class='title'>Sales Forecast Results</h1>";

        // Display data or error if JSON is invalid
        if ($data) {
            echo "<p><strong>Next Maintenance Date:</strong> " . $data['next_maintenance_date'] . "</p>";
            echo "<p><strong>Predicted Sales:</strong> " . $data['predicted_sales'] . "</p>";
        } else {
            echo "<p>Error: Could not retrieve data from Python script.</p>";
        }

        // Display metrics
        echo "<div class='metrics'>";
        echo "<h2>Metrics</h2>";
        if ($data) {
            echo "<p><strong>Mean Absolute Error (MAE):</strong> " . $data['metrics']['mae'] . "</p>";
            echo "<p><strong>Mean Squared Error (MSE):</strong> " . $data['metrics']['mse'] . "</p>";
            echo "<p><strong>Root Mean Squared Error (RMSE):</strong> " . $data['metrics']['rmse'] . "</p>";
            echo "<p><strong>R-squared:</strong> " . $data['metrics']['r2'] . "</p>";
        }
        echo "</div>";
        echo "</div>";
    }
}
?>
