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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP + Python Debug UI</title>
    <script>
        function fetchData() {
            fetch("sample.php", { method: "POST" })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("output").innerHTML = 
                        `<h3>Python Version:</h3> <p>${data.python_version || "N/A"}</p>
                        <h3>Installed Packages:</h3> <pre>${data.installed_packages ? data.installed_packages.join("\n") : "N/A"}</pre>
                        <h3>Message:</h3> <p>${data.message || "N/A"}</p>
                        <h3>Status:</h3> <p>${data.status || "N/A"}</p>
                        <h3>Errors (if any):</h3> <pre>${data.error ? data.error + "\n" + data.raw : "No errors"}</pre>`;
                })
                .catch(error => document.getElementById("output").innerHTML = `<p>Error: ${error}</p>`);
        }
    </script>
</head>
<body>
    <h1>Run Python Script</h1>
    <button onclick="fetchData()">Run Python</button>
    <div id="output"></div>
</body>
</html>
