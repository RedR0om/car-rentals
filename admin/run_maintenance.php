<?php
header('Content-Type: application/json'); // Ensure JSON response
$host = "localhost";
$username = "root";
$password = "";
$dbname = "carrental";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if (isset($_POST['vehicleId']) && isset($_POST['current_mileage'])) {
    $vehicleId = $_POST['vehicleId'];
    $selectedCar_current_mileage = $_POST['current_mileage'];

    if (is_numeric($selectedCar_current_mileage) && $selectedCar_current_mileage > 0) {
        $sql = "SELECT outgoing_meter FROM carrental.tblinspections where id = ?
                LIMIT 1";

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

        $pythonExecutable = escapeshellcmd(PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3');
        $baseDir = realpath(__DIR__ . '/ai_models'); 
        $scriptPath = escapeshellarg($baseDir . "/mysql-logistic-regression-engine-model.py");

        $command = "$pythonExecutable $scriptPath $selectedCar_last_maintenance $selectedCar_current_mileage $vehicleId";

        exec($command, $output, $status);
        
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

$conn->close();
?>
