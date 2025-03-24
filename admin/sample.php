<?php
header('Content-Type: application/json'); // Ensure JSON response

// ✅ Prevent PHP errors from displaying
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

$conn = new mysqli($host, $username, $dbname, $port);

if ($conn->connect_error) {
    error_log("Database Connection Error: " . $conn->connect_error);
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

// ✅ Handle Form Submission (AJAX)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['vehicleId'], $_POST['current_mileage'], $_POST['inspection_date'])) {
        echo json_encode(["error" => "Vehicle ID, mileage, and inspection date are required"]);
        exit;
    }

    $vehicleId = $_POST['vehicleId'];
    $selectedCar_current_mileage = $_POST['current_mileage'];
    $inspection_date = $_POST['inspection_date'];

    if (!is_numeric($selectedCar_current_mileage) || $selectedCar_current_mileage <= 0) {
        echo json_encode(["error" => "Invalid current mileage"]);
        exit;
    }

    // Validate Date Format (YYYY-MM-DD)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $inspection_date)) {
        echo json_encode(["error" => "Invalid date format. Use YYYY-MM-DD"]);
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

    $command = "$pythonExecutable $scriptPath $selectedCar_last_maintenance $selectedCar_current_mileage $vehicleId $inspection_date";
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
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Inspection</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Vehicle Inspection Form</h2>

    <form id="inspectionForm">
        <label for="vehicleId">Vehicle ID:</label>
        <input type="number" id="vehicleId" name="vehicleId" required><br><br>

        <label for="current_mileage">Current Mileage:</label>
        <input type="number" id="current_mileage" name="current_mileage" required><br><br>

        <label for="inspection_date">Inspection Date:</label>
        <input type="date" id="inspection_date" name="inspection_date" required><br><br>

        <button type="submit">Submit</button>
    </form>

    <h3>Response:</h3>
    <pre id="response"></pre>

    <script>
        $(document).ready(function () {
            $("#inspectionForm").submit(function (event) {
                event.preventDefault();

                $.ajax({
                    url: "",  // This will submit to the same PHP file
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {
                        $("#response").text(JSON.stringify(response, null, 2));
                    },
                    error: function () {
                        $("#response").text("Error occurred. Check server logs.");
                    }
                });
            });
        });
    </script>

</body>
</html>
