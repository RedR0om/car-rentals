<?php

session_start();
require_once 'includes/config.php'; // Including the configuration file where $dbh is set

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['fromdatetime']) || empty($_POST['todatetime']) || empty($_POST['vhid'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
        exit;
    }

    $fromdatetime = date('Y-m-d H:i:s', strtotime($_POST['fromdatetime']));
    $todatetime = date('Y-m-d H:i:s', strtotime($_POST['todatetime']));
    $vhid = $_POST['vhid'];

    $stmt = $dbh->prepare("SELECT PricePerDay, SeatingCapacity, CarType, Segment FROM tblvehicles WHERE id = :vhid AND status = 1");
    $stmt->execute([':vhid' => $vhid]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$vehicle) {
        echo json_encode(['success' => false, 'message' => 'Vehicle not found.']);
        exit;
    }

    $selectedPrice = $vehicle['PricePerDay'];
    $selectedSeating = $vehicle['SeatingCapacity'];
    $selectedCarType = $vehicle['CarType'];
    $selectedSegment = $vehicle['Segment'];

    $minPrice = $selectedPrice * 0.5;
    $maxPrice = $selectedPrice * 2;

    function findVehicles($dbh, $minPrice, $maxPrice, $seatingRange = null, $carType = null, $segment = null) {
        $sql = "SELECT id FROM tblvehicles WHERE PricePerDay BETWEEN :minPrice AND :maxPrice AND status = 1";
        if ($seatingRange !== null) {
            $sql .= " AND SeatingCapacity BETWEEN :minSeating AND :maxSeating";
        }
        if ($carType !== null) {
            $sql .= " AND CarType = :carType";
        }
        if ($segment !== null) {
            $sql .= " AND Segment = :segment";
        }

        $stmt = $dbh->prepare($sql);
        $params = [':minPrice' => $minPrice, ':maxPrice' => $maxPrice];

        if ($seatingRange !== null) {
            $params[':minSeating'] = $seatingRange['min'];
            $params[':maxSeating'] = $seatingRange['max'];
        }
        if ($carType !== null) {
            $params[':carType'] = $carType;
        }
        if ($segment !== null) {
            $params[':segment'] = $segment;
        }

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function checkVehicleBooking($dbh, $fromdatetime, $todatetime, $vehicleId) {
        $sql = "SELECT id FROM tblbooking WHERE 
                    (:fromdatetime BETWEEN FromDate AND ToDate 
                    OR :todatetime BETWEEN FromDate AND ToDate 
                    OR FromDate BETWEEN :fromdatetime AND :todatetime) 
                    AND VehicleId = :vhid";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':fromdatetime' => $fromdatetime,
            ':todatetime' => $todatetime,
            ':vhid' => $vehicleId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $seatingRange = ['min' => $selectedSeating, 'max' => $selectedSeating];
    $result = findVehicles($dbh, $minPrice, $maxPrice, $seatingRange, $selectedCarType, $selectedSegment);

    if (empty($result)) {
        $seatingRange = ['min' => $selectedSeating - 2, 'max' => $selectedSeating + 2];
        $result = findVehicles($dbh, $minPrice, $maxPrice, $seatingRange, $selectedCarType, $selectedSegment);
    }

    if (empty($result)) {
        $minPrice = $selectedPrice * 0.25;
        $maxPrice = $selectedPrice * 3;
        $result = findVehicles($dbh, $minPrice, $maxPrice);
    }

    $availableVehicleIds = [];
    foreach ($result as $vehicle) {
        $bookings = checkVehicleBooking($dbh, $fromdatetime, $todatetime, $vehicle['id']);
        if (empty($bookings)) {
            $availableVehicleIds[] = $vehicle['id'];
        }
    }

    if (!empty($availableVehicleIds)) {
        $sql = "SELECT v.id, v.VehiclesTitle, v.PricePerDay, v.Vimage1, v.SeatingCapacity, v.ModelYear, 
                        v.FuelType, b.BrandName, v.VehiclesBrand,
                        COALESCE((SELECT AVG(rating) FROM tblbooking WHERE VehicleId = v.id), 0) AS avg_rating
                FROM tblvehicles v
                JOIN tblbrands b ON b.id = v.VehiclesBrand
                WHERE v.id IN (" . implode(',', array_map('intval', $availableVehicleIds)) . ")
                ORDER BY v.id ASC";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Limit the result to 3 vehicles
        $vehicles = array_slice($vehicles, 0, 3);

        echo json_encode(['success' => true, 'vehicles' => $vehicles]);
    } else {
        echo json_encode(['success' => true, 'vehicles' => []]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
exit;
?>
