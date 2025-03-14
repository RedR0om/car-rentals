<?php
  $hostname = "ballast.proxy.rlwy.net:35637";
  $username = "root";
  $password = "BobDdBAPBobrKyzYicQYaJhDpujZqoKa";
  $dbname = "railway";

  $conn = mysqli_connect($hostname, $username, $password, $dbname);
  if(!$conn){
    echo "Database connection error".mysqli_connect_error();
  }
?>
