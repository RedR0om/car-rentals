<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "carrental";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    if (isset($_POST['vehicleId']) && isset($_POST['current_mileage'])) {
        $vehicleId = $_POST['vehicleId'];
        $selectedCar_current_mileage = $_POST['current_mileage'];

        if (is_numeric($selectedCar_current_mileage) && $selectedCar_current_mileage > 0) {
            // SQL query to get last mileage for the specific vehicle
            $sql = "SELECT last_mileage FROM tblvehicle_maintenance 
                    WHERE vehicleId = ? 
                    ORDER BY STR_TO_DATE(next_scheduled_maintenance_date, '%m/%d/%Y') DESC 
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

            // Execute Python script with only last maintenance and current mileage
            $pythonScriptPath = './ai_models/mysql-logistic-regression-engine-model.py';
            $command = "python $pythonScriptPath $selectedCar_last_maintenance $selectedCar_current_mileage $vehicleId";

            exec($command, $output, $status);

            if ($status === 0) {
                $result = json_decode(implode("\n", $output), true);

                if ($result) {
                    echo '<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
                    echo '<tr><th colspan="2" style="background-color: #f2f2f2;">Car Maintenance Prediction Results</th></tr>';
                    echo '<tr><td><strong>Last Maintenance Mileage</strong></td><td>' . htmlspecialchars($result['selectedCar_last_maintenance']) . '</td></tr>';
                    echo '<tr><td><strong>Current Mileage</strong></td><td>' . htmlspecialchars($result['selectedCar_current_mileage']) . '</td></tr>';

                    foreach ($result['inspection_results'] as $inspection => $data) {
                        echo '<tr><td colspan="2" style="background-color: #e0e0e0;"><strong>' . ucfirst(str_replace('_', ' ', $inspection)) . '</strong></td></tr>';
                        if (isset($data['error'])) {
                            echo '<tr><td colspan="2" style="color: red;">' . htmlspecialchars($data['error']) . '</td></tr>';
                        } else {
                            echo '<tr><td><strong>Maintenance Prediction</strong></td><td>' . htmlspecialchars($data['maintenance_prediction']) . '</td></tr>';
                            echo '<tr><td><strong>Model Accuracy</strong></td><td>' . htmlspecialchars($data['model_metrics']['accuracy']) . '</td></tr>';
                            echo '<tr><td><strong>Precision</strong></td><td>' . htmlspecialchars($data['model_metrics']['precision']) . '</td></tr>';
                            echo '<tr><td><strong>Recall</strong></td><td>' . htmlspecialchars($data['model_metrics']['recall']) . '</td></tr>';
                            echo '<tr><td><strong>F1 Score</strong></td><td>' . htmlspecialchars($data['model_metrics']['f1_score']) . '</td></tr>';
                        }
                    }

                    echo '</table>';
                } else {
                    echo json_encode(["error" => "Error decoding JSON output"]);
                }
            } else {
                echo json_encode(["error" => "Python script execution failed"]);
            }
        } else {
            echo json_encode(["error" => "Please enter a valid current mileage"]);
        }
    } else {
        echo json_encode(["error" => "Vehicle ID and current mileage are required"]);
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Maintenance Prediction</title>
</head>
<body>
    <form method="post" action="">
        <label for="vehicleId">Enter Vehicle ID:</label>
        <input type="text" id="vehicleId" name="vehicleId" required>
        
        <label for="current_mileage">Enter Current Mileage (in km):</label>
        <input type="text" id="current_mileage" name="current_mileage" required>
        
        <button type="submit" name="submit">Predict Maintenance</button>
    </form>
</body>
</html>