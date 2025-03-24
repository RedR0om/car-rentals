<?php
header('Content-Type: application/json');

// Prevent PHP errors from being displayed
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
error_log("PHP Error Log - Check railway logs or server logs");

// Database connection
$host = "ballast.proxy.rlwy.net";
$port = 35637;
$username = "root";
$password = "BobDdBAPBobrKyzYicQYaJhDpujZqoKa";
$dbname = "railway";

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    error_log("Database Connection Error: " . $conn->connect_error);
    die(json_encode(["error" => "Connection failed"]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['vehicleId']) || !isset($_POST['current_mileage'])) {
        die(json_encode(["error" => "Vehicle ID and mileage are required"]));
    }

    $vehicleId = $_POST['vehicleId'];
    $selectedCar_current_mileage = $_POST['current_mileage'];

    if (!is_numeric($selectedCar_current_mileage) || $selectedCar_current_mileage <= 0) {
        die(json_encode(["error" => "Invalid current mileage"]));
    }

    // Fetch Last Maintenance Data
    $sql = "SELECT outgoing_meter FROM tblinspections WHERE id = ? LIMIT 1";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $vehicleId);
        $stmt->execute();
        $stmt->bind_result($selectedCar_last_maintenance);
        
        if (!$stmt->fetch()) {
            error_log("No record found for vehicle ID: $vehicleId");
            die(json_encode(["error" => "No record found for vehicle ID"]));
        }
        
        $stmt->close();
    } else {
        error_log("SQL Error: " . $conn->error);
        die(json_encode(["error" => "SQL prepare failed"]));
    }

    // Execute Python Script
    $pythonExecutable = escapeshellcmd(PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3');
    $baseDir = realpath(__DIR__ . '/ai_models'); 
    $scriptPath = escapeshellarg($baseDir . "/mysql-logistic-regression-engine-model.py");

    $command = "$pythonExecutable $scriptPath $selectedCar_last_maintenance $selectedCar_current_mileage $vehicleId";
    error_log("Executing command: $command");

    exec($command . " 2>&1", $output, $status);
    error_log("Python Output: " . implode("\n", $output));
    error_log("Execution Status: $status");

    if ($status === 0) {
        $result = json_decode(implode("\n", $output), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Invalid JSON from Python script: " . json_last_error_msg());
            die(json_encode(["error" => "Invalid JSON from Python script"]));
        } else {
            die(json_encode($result));
        }
    } else {
        die(json_encode(["error" => "Python script execution failed", "details" => $output]));
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Inspection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        #response {
            margin-top: 20px;
            padding: 10px;
            background: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Check Vehicle Inspection</h2>
    <form id="vehicleForm">
        <label for="vehicleId">Vehicle ID:</label>
        <input type="number" id="vehicleId" name="vehicleId" required>

        <label for="currentMileage">Current Mileage:</label>
        <input type="number" id="currentMileage" name="current_mileage" required>

        <button type="submit">Submit</button>
    </form>

    <div id="response"></div>
</div>

<script>
    document.getElementById("vehicleForm").addEventListener("submit", function(event) {
        event.preventDefault();
        
        let formData = new FormData(this);

        fetch("index.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let responseDiv = document.getElementById("response");
            responseDiv.innerHTML = "<pre>" + JSON.stringify(data, null, 2) + "</pre>";
        })
        .catch(error => console.error("Error:", error));
    });
</script>

</body>
</html>
