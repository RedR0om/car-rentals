<?php 
// DB credentials.
define('DB_HOST','mysql.railway.internal');
define('DB_USER','root');
define('DB_PASS','BobDdBAPBobrKyzYicQYaJhDpujZqoKa');
define('DB_NAME','railway');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
$con=mysqli_connect("ballast.proxy.rlwy.net:35637", "root", "BobDdBAPBobrKyzYicQYaJhDpujZqoKa", "railway");
if(mysqli_connect_errno()){
echo "Connection Fail".mysqli_connect_error();
}
?>