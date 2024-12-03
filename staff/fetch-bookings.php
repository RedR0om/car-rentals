<?php
session_start();
include('includes/config.php');

// Prepare the SQL to fetch bookings
$sql = "SELECT tblusers.FullName, tblvehicles.VehiclesTitle, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.Status, tblbooking.id FROM tblbooking 
        JOIN tblvehicles ON tblvehicles.id = tblbooking.VehicleId 
        JOIN tblusers ON tblusers.EmailId = tblbooking.userEmail";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// Prepare data to be returned as JSON
$bookings = [];

foreach ($results as $result) {
    $booking = [
        'id' => $result->id,
        'name' => $result->FullName,
        'vehicle' => $result->VehiclesTitle,
        'start_date' => $result->FromDate,
        'end_date' => $result->ToDate,
        'message' => $result->message,
        'status' => getStatusText($result->Status) // A function to return status text
    ];
    $bookings[] = $booking;
}

// Return data as JSON
echo json_encode($bookings);

// Function to get status text based on status code
function getStatusText($status)
{
    switch ($status) {
        case 0:
            return "Not Confirmed yet";
        case 1:
            return "Confirmed";
        case 3:
            return "On-Going";
        case 4:
            return "Done";
        case 5:
            return "Rejected";
        case 6:
            return "Car Returned";
        default:
            return "Unknown Status";
    }
}
?>