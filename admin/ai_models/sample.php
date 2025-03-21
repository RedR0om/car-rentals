<?php
// Execute Python script and capture output
$output = shell_exec("python3 sample.py 2>&1");

// Decode JSON output
$data = json_decode($output, true);

// If JSON decoding fails, set an error response
if ($data === null) {
    $data = ["status" => "error", "message" => "Failed to execute script"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Python Script Output</title>
</head>
<body>
    <h2>Python Script Output</h2>

    <?php if ($data['status'] === "success"): ?>
        <h3>Python Version: <?php echo htmlspecialchars($data['python_version']); ?></h3>
        <h3>Message: <?php echo htmlspecialchars($data['message']); ?></h3>
        <h4>Installed Packages:</h4>
        <pre><?php echo htmlspecialchars(implode("\n", $data['installed_packages'])); ?></pre>
    <?php else: ?>
        <h3 style="color: red;">Error: <?php echo htmlspecialchars($data['message']); ?></h3>
    <?php endif; ?>
</body>
</html>
