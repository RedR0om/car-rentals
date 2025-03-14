<?php
session_start();
include('includes/config.php');

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $requiredFields = ['payment', 'fromdatetime', 'todatetime', 'message', 'pickup_location', 'dropoff_location', 'is_metro_manila', 'estimated_cost'];

    // Validate required fields
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || $_POST[$field] === '') {
        throw new Exception("Missing required field: $field");
        }
    }

    // Sanitize inputs
    $account_number = isset($_POST['account_number']) ? htmlspecialchars($_POST['account_number']) : null;
    $account_name = isset($_POST['account_name']) ? htmlspecialchars($_POST['account_name']) : null;
    $reference_number = isset($_POST['reference_number']) ? htmlspecialchars($_POST['reference_number']) : null;
    $payment = htmlspecialchars($_POST['payment']);
    $paymentOption = htmlspecialchars($_POST['payment']);
    $fromdatetime = date('Y-m-d H:i:s', strtotime($_POST['fromdatetime']));
    $todatetime = date('Y-m-d H:i:s', strtotime($_POST['todatetime']));
    $message = htmlspecialchars($_POST['message']);
    $pickup_location = htmlspecialchars($_POST['pickup_location']);
    $dropoff_location = htmlspecialchars($_POST['dropoff_location']);
    $is_metro_manila = htmlspecialchars($_POST['is_metro_manila']);
    $estimated_cost = htmlspecialchars($_POST['estimated_cost']);
    $useremail = $_SESSION['login'];
    $vhid = htmlspecialchars($_POST['vhid']);
    $status = 0;
    $bookingno = mt_rand(100000000, 999999999);

    if ($payment === 'cash') {
        $payment = 1;
      }
    
      if ($payment === 'gcash') {
        $payment = 2;
      }
    
      if ($payment === 'bdo') {
        $payment = 3;
      }
    
      if ($payment === 'security bank') {
        $payment = 4;
      }
    
      if ($payment === 'bpi') {
        $payment = 5;
      }

    // Handle Valid ID Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $random_name = uniqid() . '.' . $extension;
        $target_folder = "validid/";
        $target_file = $target_folder . $random_name;

        if (!move_uploaded_file($image, $target_file)) {
            throw new Exception('Error uploading Valid ID.');
        }
    } else {
        throw new Exception('Valid ID is required.');
    }

    // Handle Receipt Upload (if payment is not cash)
    $gcash_target_file = null;
    if ($payment != 1 && isset($_FILES['gcash_receipt']) && $_FILES['gcash_receipt']['error'] === UPLOAD_ERR_OK) {
        $gcash_receipt = $_FILES['gcash_receipt']['tmp_name'];
        $gcash_receipt_name = $_FILES['gcash_receipt']['name'];
        $gcash_extension = pathinfo($gcash_receipt_name, PATHINFO_EXTENSION);
        $gcash_random_name = uniqid() . '.' . $gcash_extension;
        $gcash_target_folder = "gcash_receipts/";
        $gcash_target_file = $gcash_target_folder . $gcash_random_name;

        if (!move_uploaded_file($gcash_receipt, $gcash_target_file)) {
            throw new Exception('Error uploading GCash receipt.');
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO tblbooking (userEmail, VehicleId, FromDate, ToDate, message, Status, payment, payment_option, BookingNumber, pickup_location, dropoff_location, is_metro_manila, estimated_cost, image, gcash_receipt, account_number, account_name, reference_number) 
            VALUES (:useremail, :vhid, :fromdatetime, :todatetime, :message, :status, :payment, :payment_option, :bookingno, :pickup_location, :dropoff_location, :is_metro_manila, :estimated_cost, :image, :gcash_receipt, :account_number, :account_name, :reference_number)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
    $query->bindParam(':fromdatetime', $fromdatetime, PDO::PARAM_STR);
    $query->bindParam(':todatetime', $todatetime, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_INT);
    $query->bindParam(':payment', $payment, PDO::PARAM_INT);
    $query->bindParam(':payment_option', $paymentOption, PDO::PARAM_STR);
    $query->bindParam(':bookingno', $bookingno, PDO::PARAM_INT);
    $query->bindParam(':pickup_location', $pickup_location, PDO::PARAM_STR);
    $query->bindParam(':dropoff_location', $dropoff_location, PDO::PARAM_STR);
    $query->bindParam(':is_metro_manila', $is_metro_manila, PDO::PARAM_STR);
    $query->bindParam(':estimated_cost', $estimated_cost, PDO::PARAM_STR);
    $query->bindParam(':image', $target_file, PDO::PARAM_STR);
    $query->bindParam(':gcash_receipt', $gcash_target_file, PDO::PARAM_STR);
    $query->bindParam(':account_number', $account_number, PDO::PARAM_STR);
    $query->bindParam(':account_name', $account_name, PDO::PARAM_STR);
    $query->bindParam(':reference_number', $reference_number, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking successful']);
        exit;
    } else {
        throw new Exception('Error in database operation');
    }
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  exit;
}
?>
