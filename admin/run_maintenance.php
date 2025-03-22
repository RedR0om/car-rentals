<?php
header('Content-Type: application/json'); // Ensure JSON response

// ✅ Correct database connection settings
$host = "ballast.proxy.rlwy.net";  // ✅ Hostname only (NO port in host)
$port = 35637; // ✅ Define port separately
$username = "root";
$password = "BobDdBAPBobrKyzYicQYaJhDpujZqoKa";
$dbname = "railway";

// ✅ Connect to MySQL with separate host & port
$conn = new mysqli($host, $username, $password, $dbname, $port);

// ✅ Check for database connection errors
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// ✅ Check if required parameters are present
if (isset($_POST['vehicleId']) && isset($_POST['current_mileage'])) {
    $vehicleId = $_POST['vehicleId'];
    $selectedCar_current_mileage = $_POST['current_mileage'];

    // ✅ Validate mileage input
    if (is_numeric($selectedCar_current_mileage) && $selectedCar_current_mileage > 0) {
        $sql = "SELECT outgoing_meter FROM carrental.tblinspections WHERE id = ? LIMIT 1";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $vehicleId);
            $stmt->execute();
            $stmt->bind_result($selectedCar_last_maintenance);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo json_encode(["error" => "Error preparing SQL query"]);
            exit;
        }

        // ✅ Ensure Python environment is correctly detected
        $pythonExecutable = escapeshellcmd('python3'); // Use `python3` explicitly for Linux (Railway)
        $baseDir = realpath(__DIR__ . '/ai_models'); 
        $scriptPath = escapeshellarg($baseDir . "/mysql-logistic-regression-engine-model.py");

        // ✅ Properly escape shell arguments to avoid security issues
        $arg1 = escapeshellarg($selectedCar_last_maintenance);
        $arg2 = escapeshellarg($selectedCar_current_mileage);
        $arg3 = escapeshellarg($vehicleId);

        // ✅ Build and execute command
        $command = "$pythonExecutable $scriptPath $arg1 $arg2 $arg3";
        exec($command, $output, $status);

        // ✅ Check execution result
        if ($status === 0) {
            $result = json_decode(implode("\n", $output), true);
            echo json_encode($result);
        } else {
            echo json_encode(["error" => "Python script execution failed"]);
        }
    } else {
        echo json_encode(["error" => "Invalid current mileage"]);
    }
} else {
    echo json_encode(["error" => "Vehicle ID and mileage are required"]);
}

// ✅ Close database connection
$conn->close();
?>
