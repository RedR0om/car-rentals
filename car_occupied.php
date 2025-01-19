    <?php
    session_start();
    include('includes/config.php');

    // Enable error reporting for debugging (remove in production)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fromdatetime = date('Y-m-d H:i:s', strtotime($_POST['fromdatetime']));
        $todatetime = date('Y-m-d H:i:s', strtotime($_POST['todatetime']));
        $vhid = $_POST['vhid'];

        
        $sql = "SELECT * FROM tblbooking b
            JOIN tblvehicles v ON b.VehicleId = v.id
            WHERE 
            (:fromdatetime BETWEEN b.FromDate AND b.ToDate 
            OR :todatetime BETWEEN b.FromDate AND b.ToDate 
            OR b.FromDate BETWEEN :fromdatetime AND :todatetime) 
            AND b.VehicleId = :vhid
            AND v.status = 1";

        $query = $dbh->prepare($sql);
        $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
        $query->bindParam(':fromdatetime', $fromdatetime, PDO::PARAM_STR);
        $query->bindParam(':todatetime', $todatetime, PDO::PARAM_STR);
        $query->execute();

        $response = [
            'existingReservation' => $query->rowCount() > 0 // True if reservation exists
        ];

        echo json_encode($response);
        exit;
    }
    ?>
