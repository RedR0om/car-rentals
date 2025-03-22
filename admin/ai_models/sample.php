<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastMaintenance = escapeshellarg($_POST['last_maintenance']);
    $currentMileage = escapeshellarg($_POST['current_mileage']);
    $vehicleId = escapeshellarg($_POST['vehicle_id']);

    // Run the Python script with arguments
    $command = "python3 mysql-logistic-regression-engine-model.py $lastMaintenance $currentMileage $vehicleId 2>&1";
    $output = shell_exec($command);

    // Decode JSON output
    $data = json_decode($output, true);

    // If JSON decoding fails, set an error response
    if ($data === null) {
        $data = ["error" => "Failed to execute script", "message" => htmlspecialchars($output)];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Maintenance Prediction</title>
</head>
<body>
    <h2>Vehicle Maintenance Prediction</h2>

    <form method="POST">
        <label for="last_maintenance">Last Maintenance Mileage:</label>
        <input type="number" step="0.01" name="last_maintenance" required><br><br>

        <label for="current_mileage">Current Mileage:</label>
        <input type="number" step="0.01" name="current_mileage" required><br><br>

        <label for="vehicle_id">Vehicle ID:</label>
        <input type="number" name="vehicle_id" required><br><br>

        <button type="submit">Predict Maintenance</button>
    </form>

    <?php if (!empty($data)): ?>
        <h3>Prediction Result:</h3>
        <pre><?php echo json_encode($data, JSON_PRETTY_PRINT); ?></pre>
    <?php endif; ?>
</body>
</html>
