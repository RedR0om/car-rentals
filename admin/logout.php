<?php
session_start();
include('includes/config.php'); // Include database configuration

// Check if a user is logged in to log the action
if (isset($_SESSION['alogin'])) {
    $username = $_SESSION['alogin'];
    $name = $_SESSION['name']; // Assuming the name is stored in the session

    // Get the current IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Insert a logout action into the user_logs table
    $log_action = "Logout";
    $log_sql = "INSERT INTO user_logs (username, name, action, ip_address) 
                VALUES (:username, :name, :action, :ip_address)";
    $log_query = $dbh->prepare($log_sql);
    $log_query->bindParam(':username', $username, PDO::PARAM_STR);
    $log_query->bindParam(':name', $name, PDO::PARAM_STR);
    $log_query->bindParam(':action', $log_action, PDO::PARAM_STR);
    $log_query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
    $log_query->execute();
}

// Clear session data
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 60 * 60,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
unset($_SESSION['alogin']);
session_destroy(); // Destroy session

// Redirect to the login page
header("location:index.php");
exit();
