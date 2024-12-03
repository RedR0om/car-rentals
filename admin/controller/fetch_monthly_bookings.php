<?php
$con = mysqli_connect("localhost", "root", "", "carrental");
if (!$con) {
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$monthlyBookingsQuery = "SELECT MONTH(PostingDate) AS month, COUNT(*) AS totalBookings 
                         FROM tblbooking 
                         WHERE YEAR(PostingDate) = $year 
                         GROUP BY MONTH(PostingDate)";

$monthlyBookingsResult = mysqli_query($con, $monthlyBookingsQuery);

$monthlyData = array_fill(0, 12, 0); // Initialize all months to zero

while ($row = mysqli_fetch_assoc($monthlyBookingsResult)) {
    $monthlyData[$row['month'] - 1] = $row['totalBookings']; // -1 for zero-index
}

echo json_encode(['monthlyData' => $monthlyData]);
