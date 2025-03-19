<?php
// index.php

// If required GET parameters are not provided, display an HTML form.
if (!isset($_GET['last_maintenance']) || !isset($_GET['current_mileage']) || !isset($_GET['vehicleId'])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Forecast Input</title>
</head>
<body>
    <h1>Enter Maintenance Details</h1>
    <form method="get" action="">
        <label for="last_maintenance">Last Maintenance Mileage:</label>
        <input type="text" id="last_maintenance" name="last_maintenance" required><br><br>

        <label for="current_mileage">Current Mileage:</label>
        <input type="text" id="current_mileage" name="current_mileage" required><br><br>

        <label for="vehicleId">Vehicle ID:</label>
        <input type="text" id="vehicleId" name="vehicleId" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
<?php
exit;
endif;

// If parameters are provided, retrieve and sanitize them.
$lastMaintenance = escapeshellarg($_GET['last_maintenance']);
$currentMileage  = escapeshellarg($_GET['current_mileage']);
$vehicleId       = escapeshellarg($_GET['vehicleId']);

// Build the command to run your Python script.
// Adjust "python3" to "python" if needed, and update the script name if necessary.
$command = "python3 prophet-maintenance-forecasting.py $lastMaintenance $currentMileage $vehicleId";

// Execute the command and capture the output.
$output = shell_exec($command);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Forecast Results</title>
</head>
<body>
    <h1>Maintenance Forecast Results</h1>
    <pre><?php echo htmlspecialchars($output); ?></pre>
    <br>
    <a href="index.php">Try Again</a>
</body>
</html>
