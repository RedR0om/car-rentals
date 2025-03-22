<?php
header('Content-Type: application/json'); // Ensure JSON response

// ✅ Prevents PHP errors from being shown in JSON response
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
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

// ✅ Validate Input
if (!isset($_POST['vehicleId']) || !isset($_POST['current_mileage'])) {
    echo json_encode(["error" => "Vehicle ID and mileage are required"]);
    exit;
}

$vehicleId = $_POST['vehicleId'];
$selectedCar_current_mileage = $_POST['current_mileage'];

if (!is_numeric($selectedCar_current_mileage) || $selectedCar_current_mileage <= 0) {
    echo json_encode(["error" => "Invalid current mileage"]);
    exit;
}

// ✅ Fetch Last Maintenance Data
$sql = "SELECT outgoing_meter FROM tblinspections WHERE id = ? LIMIT 1";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $stmt->bind_result($selectedCar_last_maintenance);
    
    if (!$stmt->fetch()) {
        error_log("No record found for vehicle ID: $vehicleId");
        echo json_encode(["error" => "No record found for vehicle ID"]);
        exit;
    }
    
    $stmt->close();
} else {
    error_log("SQL Error: " . $conn->error);
    echo json_encode(["error" => "SQL prepare failed"]);
    exit;
}

// ✅ Execute Python Script
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
        echo json_encode(["error" => "Invalid JSON from Python script"]);
    } else {
        echo json_encode($result);
    }
} else {
    echo json_encode(["error" => "Python script execution failed", "details" => $output]);
}

$conn->close();
?>
