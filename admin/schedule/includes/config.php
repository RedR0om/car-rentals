<?php 
// DB credentials.
define('DB_HOST','ballast.proxy.rlwy.net:35637');
define('DB_USER','root');
define('DB_PASS','BobDdBAPBobrKyzYicQYaJhDpujZqoKa');
define('DB_NAME','railway');

$conn = mysqli_connect("ballast.proxy.rlwy.net:35637", "root", "BobDdBAPBobrKyzYicQYaJhDpujZqoKa", "railway");
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>