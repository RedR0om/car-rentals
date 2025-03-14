<?php
//database connection
try {
$dbc = new PDO('mysql:host=ballast.proxy.rlwy.net:35637; dbname=carrental', 'root', 'BobDdBAPBobrKyzYicQYaJhDpujZqoKa');
$dbc->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch(PDOException $e) {
	echo "Error: " . $e->getMessage();
}