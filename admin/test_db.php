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

echo json_encode(["success" => "Database connected successfully!"]);
$conn->close();
?>
