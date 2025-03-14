<?php
$host     = 'ballast.proxy.rlwy.net:35637';
$username = 'root';
$password = 'BobDdBAPBobrKyzYicQYaJhDpujZqoKa';
$dbname   ='railway';

$conn = new mysqli($host, $username, $password, $dbname);
if(!$conn){
    die("Cannot connect to the database.". $conn->error);
}