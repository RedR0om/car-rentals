<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (isset($_POST['submit'])) {
  $driver = $_POST['driver'];
  $payment = $_POST['payment'];
  $fromdatetime = date('Y-m-d H:i:s', strtotime($_POST['fromdatetime'])); // Updated format
  $todatetime = date('Y-m-d H:i:s', strtotime($_POST['todatetime'])); // Updated format
  $message = $_POST['message'];
  $pickup_location = $_POST['pickup_location'];
  $dropoff_location = $_POST['dropoff_location'];
  $useremail = $_SESSION['login'];
  $status = 0;
  $vhid = $_GET['vhid'];
  $bookingno = mt_rand(100000000, 999999999);

  // Initialize GCash number
  $gcash_number = isset($_POST['gcash_number']) ? $_POST['gcash_number'] : null;

  $sql = "SELECT * FROM tblbooking WHERE 
                (:fromdatetime BETWEEN FromDate AND ToDate 
                OR :todatetime BETWEEN FromDate AND ToDate 
                OR FromDate BETWEEN :fromdatetime AND :todatetime) 
                AND VehicleId = :vhid";

  $query = $dbh->prepare($sql);
  $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
  $query->bindParam(':fromdatetime', $fromdatetime, PDO::PARAM_STR);
  $query->bindParam(':todatetime', $todatetime, PDO::PARAM_STR);
  $query->execute();

  if ($query->rowCount() == 0) {
    // Handle Valid ID Upload
    $image = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $random_name = uniqid() . '.' . $extension;
    $target_folder = "validid/";
    $target_file = $target_folder . $random_name;

    if (move_uploaded_file($image, $target_file)) {
      // Handle GCash Receipt Upload
      $gcash_receipt = $_FILES['gcash_receipt']['tmp_name'];
      $gcash_receipt_name = $_FILES['gcash_receipt']['name'];
      $gcash_extension = pathinfo($gcash_receipt_name, PATHINFO_EXTENSION);
      $gcash_random_name = uniqid() . '.' . $gcash_extension;
      $gcash_target_folder = "gcash_receipts/";
      $gcash_target_file = $gcash_target_folder . $gcash_random_name;

      if (move_uploaded_file($gcash_receipt, $gcash_target_file)) {
        // Insert booking into the database
        $sql = "INSERT INTO tblbooking(driver, userEmail, VehicleId, FromDate, ToDate, message, Status, payment, image, BookingNumber, gcash_receipt, gcash_number, pickup_location, dropoff_location) 
VALUES(:driver, :useremail, :vhid, :fromdatetime, :todatetime, :message, :status, :payment, :image, :bookingno, :gcash_receipt, :gcash_number, :pickup_location, :dropoff_location)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pickup_location', $pickup_location, PDO::PARAM_STR);
        $query->bindParam(':dropoff_location', $dropoff_location, PDO::PARAM_STR);
        $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
        $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
        $query->bindParam(':fromdatetime', $fromdatetime, PDO::PARAM_STR);
        $query->bindParam(':todatetime', $todatetime, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':payment', $payment, PDO::PARAM_STR);
        $query->bindParam(':image', $target_file);
        $query->bindParam(':bookingno', $bookingno, PDO::PARAM_STR);
        $query->bindParam(':driver', $driver, PDO::PARAM_STR);
        $query->bindParam(':gcash_receipt', $gcash_target_file, PDO::PARAM_STR);
        $query->bindParam(':gcash_number', $gcash_number, PDO::PARAM_STR);


        if ($query->execute()) {
          echo "<script>alert('Booking successful!');</script>";
        } else {
          echo "<script>alert('Error in booking.');</script>";
        }
      } else {
        echo "<script>alert('Error uploading GCash receipt.');</script>";
      }
    } else {
      echo "<script>alert('Error uploading valid ID.');</script>";
    }
  } else {
    echo "<script>alert('The selected dates are already booked.');</script>";
  }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Car Rental|Car Details</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
  <meta content="Author" name="WebThemez">
  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700"
    rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">


</head>

<body id="body">
  <?php include('includes/header.php'); ?>
  <section id="innerBanner" style="background:black;">
    <div class="inner-content">
      <h2><span style="color: #ff0000">ABOUT CAR</span><br>We provide high quality and well serviced cars </h2>
      <div>
      </div>
    </div>
  </section>

  <main id="main">
    <section id="clients" class="wow fadeInUp">
      <div class="container">
        <div class="section-header">
          <h2>Car details</h2>
          <p>View our selection of pristine and well-maintained vehicles.</p>
        </div>
        <?php
        $vhid = intval($_GET['vhid']);
        $sql = "SELECT tblvehicles.*, tblbrands.BrandName, tblbrands.id as bid 
              FROM tblvehicles 
              JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
              WHERE tblvehicles.id = :vhid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
          foreach ($results as $result) {
            $_SESSION['brndid'] = $result->bid;

            // Define default image path
            $defaultImage = '1audiq8.jpg';
            ?>
            <div class="owl-carousel clients-carousel">
              <img
                src="admin/img/vehicleimages/<?php echo !empty($result->Vimage1) ? htmlentities($result->Vimage1) : $defaultImage; ?>"
                alt="Car Image" style="height: 150px; width:300px;">
              <img
                src="admin/img/vehicleimages/<?php echo !empty($result->Vimage2) ? htmlentities($result->Vimage2) : $defaultImage; ?>"
                alt="Car Image" style="height: 150px; width:300px;">
              <img
                src="admin/img/vehicleimages/<?php echo !empty($result->Vimage3) ? htmlentities($result->Vimage3) : $defaultImage; ?>"
                alt="Car Image" style="height: 150px; width:300px;">
              <img
                src="admin/img/vehicleimages/<?php echo !empty($result->Vimage4) ? htmlentities($result->Vimage4) : $defaultImage; ?>"
                alt="Car Image" style="height: 150px; width:300px;">
            </div>
          </div>

        </section><!-- #clients -->

        <!--Listing-detail-->
        <section class="listing-detail">
          <div class="container">
            <div class="listing_detail_head row">
              <div class="col-md-9">
                <h2><?php echo htmlentities($result->BrandName); ?> , <?php echo htmlentities($result->VehiclesTitle); ?>
                </h2>
                <h6><a href="" style="color:red">NOTE:</a> the seat capacity of this vehicle don't depends on with driver or
                  without </h3>
              </div>
              <div class="col-md-3">
                <div class="price_info">
                  <p>₱ <?php echo number_format(htmlentities($result->PricePerDay), 2); ?> </p>Per Day

                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-9">
                <div class="main_features">
                  <ul>

                    <li> <i class="fa fa-calendar" aria-hidden="true"></i>
                      <h5><?php echo htmlentities($result->ModelYear); ?></h5>
                      <p>Reg.Year</p>
                    </li>
                    <li> <i class="fa fa-cogs" aria-hidden="true"></i>
                      <h5><?php echo htmlentities($result->FuelType); ?></h5>
                      <p>Fuel Type</p>
                    </li>

                    <li> <i class="fa fa-user-plus" aria-hidden="true"></i>
                      <h5><?php echo htmlentities($result->SeatingCapacity); ?></h5>
                      <p>Seater</p>
                    </li>
                    <li><i class="fa fa-wrench" aria-hidden="true"></i>

                      <h5><?php echo htmlentities($result->carType); ?></h5>
                      <p>Transmission Type</p>
                    </li>

                    <li><i class="fa fa-car" aria-hidden="true"></i>

                      <h5><?php echo htmlentities($result->carspecs); ?></h5>
                      <p>Vehicle Type</p>
                    </li>

                  </ul>
                </div>
                <div class="listing_more_info">
                  <div class="listing_detail_wrap">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs gray-bg" role="tablist">
                      <li role="presentation"><a href="#vehicle-overview " aria-controls="vehicle-overview" role="tab"
                          data-toggle="tab">Vehicle Overview </a></li>

                      <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab"
                          data-toggle="tab">Features</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <!-- vehicle-overview -->
                      <div role="tabpanel" class="tab-pane active" id="vehicle-overview">

                        <p><?php echo htmlentities($result->VehiclesOverview); ?></p>
                      </div>


                      <!-- Accessories -->
                      <div role="tabpanel" class="tab-pane" id="accessories">
                        <!--Accessories-->
                        <table>
                          <thead>
                            <tr>
                              <th colspan="2">Vehicle Feature</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Air Conditioner</td>
                              <?php if ($result->AirConditioner == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php
                              } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php
                              } ?>
                            </tr>

                            <tr>
                              <td>AntiLock Braking System</td>
                              <?php if ($result->AntiLockBrakingSystem == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Power Steering</td>
                              <?php if ($result->PowerSteering == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>


                            <tr>

                              <td>Power Windows</td>

                              <?php if ($result->PowerWindows == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>CD Player</td>
                              <?php if ($result->CDPlayer == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Leather Seats</td>
                              <?php if ($result->LeatherSeats == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Central Locking</td>
                              <?php if ($result->CentralLocking == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Power Door Locks</td>
                              <?php if ($result->PowerDoorLocks == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>
                            <tr>
                              <td>Brake Assist</td>
                              <?php if ($result->BrakeAssist == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Driver Airbag</td>
                              <?php if ($result->DriverAirbag == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Passenger Airbag</td>
                              <?php if ($result->PassengerAirbag == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Crash Sensor</td>
                              <?php if ($result->CrashSensor == 1) {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php
                              } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php
                              } ?>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                </div>
                <?php
          }
        } ?>

          </div>



          <?php
          if ($_SESSION['login']) { ?>
            <!--Side-Bar-->
            <aside class="col-md-3">

              <div class="sidebar_widget">
                <div class="widget_heading">
                  <h5><i class="fa fa-envelope" aria-hidden="true"></i>Book Now</h5>


                </div>

                <script>
                  function validateDateTime(input) {
                    if (input.value < document.getElementsByName("fromdatetime")[0].value) {
                      input.setCustomValidity("To Date and Time must be later than or equal to From Date and Time");
                    } else {
                      input.setCustomValidity("");
                    }
                  }
                </script>

                <!-- Trigger/Button to Open Modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bookingModal"
                  style="background: #ff0000; width: 220px;">
                  Book Now
                </button>

                <!-- Modal -->
                <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel" style="color: white;margin-left: 0px;">Booking Form
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" id="bookingForm">
                                        <?php
                                        // Fetch places from tblplace table
                                        $sql = "SELECT PlaceID, PlaceName FROM tblplace";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $places = $query->fetchAll(PDO::FETCH_OBJ);
                                        ?>

                          <!-- Hidden field to check if form is already submitted -->
                          <input type="hidden" name="form_submitted" id="form_submitted" value="0">

                          <!-- Pickup Location Dropdown -->
                          <div class="form-group">
                            <label for="pickup" class="control-label">Pickup Location:</label>
                            <select class="form-control" name="pickup_location" required
                              style="height: calc(3.25rem + 2px);">
                              <option value="">Select Pickup Location</option>
                                            <?php foreach ($places as $place) { ?>
                                <option value="<?php echo $place->PlaceID; ?>">
                                                <?php echo htmlentities($place->PlaceName); ?>
                                </option>
                                      
                              <?php } ?>
                            </select>
                          </div>

                          <!-- Drop-off Location Dropdown -->
                          <div class="form-group">
                            <label for="dropoff" class="control-label">Drop-off Location:</label>
                            <select class="form-control" name="dropoff_location" required
                              style="height: calc(3.25rem + 2px);">
                              <option value="">Select Drop-off Location</option>
                                            <?php foreach ($places as $place) { ?>
                                <option value="<?php echo $place->PlaceID; ?>">
                                                <?php echo htmlentities($place->PlaceName); ?>
                                </option>
                                      
                              <?php } ?>
                            </select>
                          </div>

                          <!-- Date and Time Fields -->
                          <div class="form-group">
                            <label class="control-label">From Date and Time:</label>
                            <input type="datetime-local" class="form-control" name="fromdatetime"
                              placeholder="From Date and Time" min="<?php echo date('Y-m-d\TH:i'); ?>" required
                              style="height: calc(3.25rem + 2px);">
                          </div>
                          <div class="form-group">
                            <label class="control-label">To Date and Time:</label>
                            <input type="datetime-local" class="form-control" name="todatetime"
                              placeholder="To Date and Time" min="<?php echo date('Y-m-d\TH:i'); ?>"
                              onchange="validateDateTime(this)" required style="height: calc(3.25rem + 2px);">
                          </div>

                          <!-- Driver Option -->
                          <div class="form-group">
                            <label for="driver" class="control-label">Driver:</label><br>
                            <input type="radio" name="driver" value="1" required> With Driver<br>
                            <input type="radio" name="driver" value="2" required> Without Driver<br>
                          </div>

                          <!-- Message -->
                          <div class="form-group">
                            <textarea rows="4" class="form-control" name="message" placeholder="Message"
                              required></textarea>
                          </div>

                          <!-- Payment Option -->
                          <div class="form-group">
                            <label class="control-label">Payment Option</label>
                            <select class="selectpicker form-control" name="payment" id="payment-option" required
                              onchange="showPaymentFields()" style="height: calc(3.25rem + 2px);">
                              <option value="" disabled selected>Select Payment Method</option>
                              <option value="cash">Cash</option>
                              <option value="gcash">GCash</option>
                            </select>
                          </div>

                          <!-- GCash Payment Fields -->
                          <div id="gcash-fields" style="display: none;">
                            <p>Send it to this Gcash Number: 0909584666</p>
                            <div class="form-group">
                              <label for="gcash-number" class="control-label">GCash Number:</label>
                              <input type="text" class="form-control" name="gcash_number" placeholder="Enter GCash Number"
                                required>
                            </div>
                            <div class="form-group">
                              <label class="control-label">Upload GCash Payment Receipt</label><br>
                              <input type="file" class="form-control-file" id="gcash_receipt" name="gcash_receipt"
                                required>
                            </div>
                          </div>

                          <!-- Upload Valid ID -->
                                        <?php if ($_SESSION['login']) { ?>
                            <div class="form-group">
                              <label class="control-label">Upload Valid ID</label><br>
                              <input type="file" class="form-control-file" id="image" name="image" required>
                            </div>
                            <div class="form-group">
                              <input type="submit" class="btn" name="submit" value="Book Now" style="background: #ff0000;"
                                id="submit-btn">
                            </div>
                                        <?php } else { ?>
                            <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login
                              For Book</a>
                                  
                          <?php } ?>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <script>
                  // Disable form submission after it's already been submitted
                  document.getElementById('bookingForm').addEventListener('submit', function (event) {
                    // Check if the form has already been submitted
                    if (document.getElementById('form_submitted').value == '1') {
                      event.preventDefault();  // Prevent form submission
                      alert('This booking cannot be edited after submission.');
                    } else {
                      // Mark the form as submitted
                      document.getElementById('form_submitted').value = '1';
                    }
                  });
                </script>




                <script>
                  function showPaymentFields() {
                    var paymentOption = document.getElementById("payment-option").value;
                    var gcashFields = document.getElementById("gcash-fields");

                    if (paymentOption === "gcash") {
                      gcashFields.style.display = "block";
                    } else {
                      gcashFields.style.display = "none";
                    }
                  }
                </script>


              </div>
            </aside>
            <!--/Side-Bar-->
          </div>
        <?php } ?>
      </div>
      </div>
      </div>
      </div>
  </main>

  <!-- Add this CSS in the <head> section or in an external stylesheet -->
  <style>
    /* Custom modal styling */
    .modal-content {
      border-radius: 8px;
      border: none;
      background: #f9f9f9;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .modal-header {
      background-color: #ff0000;
      color: white;
      border-bottom: 1px solid #ddd;
      padding: 15px;
      border-radius: 8px 8px 0 0;
    }

    .modal-footer {
      border-top: 1px solid #ddd;
      padding: 15px;
      background-color: #f7f7f7;
      border-radius: 0 0 8px 8px;
    }

    .modal-title {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .modal-body {
      padding: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-control {
      border-radius: 5px;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
    }

    .btn {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      font-size: 1rem;
      border-radius: 5px;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    .btn-secondary {
      background-color: #6c757d;
      color: white;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
    }

    .close {
      color: white;
      font-size: 1.5rem;
    }

    .close:hover {
      color: #f1f1f1;
    }

    /* Custom select dropdown styling */
    select.form-control {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background-color: #fff;
      background-position: right 10px center;
      background-repeat: no-repeat;
      padding-right: 30px;
    }

    /* Optional: Add a background color for form inputs */
    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }

    .form-group label {
      font-weight: bold;
      margin-bottom: 8px;
    }

    /* GCash fields styling */
    #gcash-fields p {
      margin-top: 0;
      font-weight: bold;
      color: #007bff;
    }
  </style>



  <?php include('includes/footer.php'); ?>
  <!-- /Footer-->


  <!--Login-Form -->
  <?php include('includes/login.php'); ?>
  <!--/Login-Form -->

  <!--Register-Form -->
  <?php include('includes/registration.php'); ?>

  <!--/Register-Form -->


  <!--Forgot-password-Form -->
  <?php include('includes/forgotpassword.php'); ?>



  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/magnific-popup/magnific-popup.min.js"></script>
  <script src="lib/sticky/sticky.js"></script>
  <script src="contact/jqBootstrapValidation.js"></script>
  <script src="contact/contact_me.js"></script>
  <script src="js/main.js"></script><!-- Scripts -->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/interface.js"></script>
  <!--Switcher-->
  <script src="assets/switcher/js/switcher.js"></script>
  <!--bootstrap-slider-JS-->
  <script src="assets/js/bootstrap-slider.min.js"></script>
  <!--Slider-JS-->
  <script src="assets/js/slick.min.js"></script>
  <script src="assets/js/owl.carousel.min.js"></script>

</body>

</html>
<?php

?>