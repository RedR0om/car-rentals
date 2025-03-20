<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Run the Python script and capture JSON output
    $output = shell_exec("python3 admin/ai_models/sample.py 2>&1");
    
    // Decode JSON response
    $data = json_decode($output, true);
    
    // Send JSON response to frontend
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
    <title>PHP + Python Shell_exec</title>
    <script>
        function fetchData() {
            fetch("sample.php", { method: "POST" }) // <-- FIXED
                .then(response => response.json())
                .then(data => {
                    document.getElementById("output").innerHTML = 
                        `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                })
                .catch(error => console.error("Error:", error));
        }
    </script>
</head>
<body>
    <h1>PHP + Python Shell_exec</h1>
    <button onclick="fetchData()">Run Python</button>
    <div id="output"></div>
</body>
</html>
