<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Run the Python script
    $output = shell_exec("python3 admin/ai_models/sample.py 2>&1");

    // Debug: Print raw output (check for errors)
    if (!$output) {
        echo json_encode(["error" => "Python script did not return any output"]);
        exit;
    }

    // Try decoding JSON
    $data = json_decode($output, true);

    // Debug: Check if JSON decoding failed
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Invalid JSON", "raw" => $output]);
        exit;
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>
