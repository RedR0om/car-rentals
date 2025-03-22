<?php
$host = "ballast.proxy.rlwy.net";
$port = 35637;
$username = "root";
$password = "BobDdBAPBobrKyzYicQYaJhDpujZqoKa";
$dbname = "railway";

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Get Database Name
$dbQuery = "SELECT DATABASE()";
$dbResult = $conn->query($dbQuery);
$dbName = ($dbResult->num_rows > 0) ? $dbResult->fetch_row()[0] : "Unknown";

// Get Tables
$tablesQuery = "SHOW TABLES";
$tablesResult = $conn->query($tablesQuery);
$tables = [];

while ($row = $tablesResult->fetch_array()) {
    $tables[] = $row[0];
}

// Return JSON Response
$response = [
    "database_name" => $dbName,
    "tables" => $tables
];

echo json_encode($response, JSON_PRETTY_PRINT);

$conn->close();
?>
