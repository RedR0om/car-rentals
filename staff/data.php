<?php
include_once("db_connect.php");
// initilize all variables
// initialize all variables
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;

// define index of columns
$columns = array( 
	0 => 'id',
	1 => 'userEmail', 
	2 => 'VehicleId',
	3 => 'VehicleName', // add a new column for vehicle name
	4 => 'FromDate',
	5 => 'ToDate',
	6 => 'Status',
	7 => 'payment',
	8 => 'BookingNumber',  
	9 => 'rating'	
);

$where = $sqlTot = $sqlRec = "";

// getting total number records from table without any search
$sql = "SELECT b.id, b.userEmail, b.VehicleId, v.VehiclesTitle AS VehicleName, b.FromDate, b.ToDate, b.Status, b.payment, b.BookingNumber, b.rating 
		FROM `tblbooking` AS b
		LEFT JOIN `tblvehicles` AS v ON b.VehicleId = v.id";

$sqlTot .= $sql;
$sqlRec .= $sql;
$sqlRec .=  " ORDER BY b.id";

$queryTot = mysqli_query($conn, $sqlTot) or die("database error:". mysqli_error($conn));
$totalRecords = mysqli_num_rows($queryTot);

$queryRecords = mysqli_query($conn, $sqlRec) or die("error to fetch booking data");

// iterate on results row and create new index array of data
while( $row = mysqli_fetch_row($queryRecords) ) { 
	$data[] = $row;
}	

$json_data = array(
		"draw"            => 1,   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

// send data as json format
echo json_encode($json_data); 
?>