<?php
session_start();
include('includes/config.php');
// error_reporting(0); TODO: bring it back this line to close the error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*


$existingReservation = false;

if (isset($_POST['submit'])) {
  $payment = $_POST['payment'];
  $paymentOption = $_POST['payment'];
  $fromdatetime = date('Y-m-d H:i:s', strtotime($_POST['fromdatetime'])); // Updated format
  $todatetime = date('Y-m-d H:i:s', strtotime($_POST['todatetime'])); // Updated format
  $message = $_POST['message'];
  $pickup_location = $_POST['pickup_location'];
  $dropoff_location = $_POST['dropoff_location'];
  $is_metro_manila = $_POST['is_metro_manila'];
  $estimated_cost = $_POST['estimated_cost'];
  $useremail = $_SESSION['login'];
  $status = 0;
  $vhid = $_GET['vhid'];
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

  // Initialize Account details
  $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : null;
  $account_name = isset($_POST['account_name']) ? $_POST['account_name'] : null;
  $reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : null;

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
    $existingReservation = false;
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

      if ($payment != 1 && !move_uploaded_file($gcash_receipt, $gcash_target_file)) {
        echo "<script>alert('Error uploading GCash receipt.');</script>";
      } else {
        // Insert booking into the database
        $sql = "INSERT INTO tblbooking(userEmail, VehicleId, FromDate, ToDate, message, Status, payment, image, BookingNumber, gcash_receipt, payment_option, account_number, account_name, reference_number, pickup_location, dropoff_location, is_metro_manila, estimated_cost) 
        VALUES(:useremail, :vhid, :fromdatetime, :todatetime, :message, :status, :payment, :image, :bookingno, :gcash_receipt, :payment_option, :account_number, :account_name, :reference_number, :pickup_location, :dropoff_location, :is_metro_manila, :estimated_cost)";
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
        $query->bindParam(':gcash_receipt', $gcash_target_file, PDO::PARAM_STR);
        $query->bindParam(':payment_option', $paymentOption, PDO::PARAM_STR);
        $query->bindParam(':account_number', $account_number, PDO::PARAM_STR);
        $query->bindParam(':account_name', $account_name, PDO::PARAM_STR);
        $query->bindParam(':reference_number', $reference_number, PDO::PARAM_STR);
        $query->bindParam(':is_metro_manila', $is_metro_manila, PDO::PARAM_STR);
        $query->bindParam(':estimated_cost', $estimated_cost, PDO::PARAM_STR);

        if ($query->execute()) {
          echo "<script>alert('Booking successful!');</script>";
        } else {
          echo "<script>alert('Error in booking.');</script>";
        }

      }

    } else {
      echo "<script>alert('Error uploading valid ID.');</script>";
    }
  } else {
    $existingReservation = true;
    echo "<script>alert('The selected dates are already booked from " . $fromdatetime . " to " . $todatetime . " for Vehicle ID: " . $vhid . "');</script>";


  }
}

*/
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
            $defaultImage = 'civicfront.jpg';
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
            <div class="listing_detail_head" style="margin-top: 100px;">
            <div class="row">
              <!-- Left Container -->
              <div class="col-md-8">
                <h2>
                  <?php echo htmlentities($result->BrandName); ?> , 
                  <?php echo htmlentities($result->VehiclesTitle); ?>
                </h2>
                <h6>
                  <a href="" style="color:red">NOTE:</a> the seat capacity of this vehicle doesn't depend on 
                  whether with driver or without.
                </h6>
              </div>

              <!-- Right Container -->
              <div class="col-md-4 text-md-end">
                <div class="price_info">
                  <p>₱ <?php echo number_format(htmlentities($result->PricePerDay), 2); ?> </p>Per Day
                </div>
              </div>
            </div>
            </div>
            <div class="row">
              <div class="col-md-9">
                <div class="main_features">
                  <ul>
                    <li> <i class="fa fa-calendar" aria-hidden="true"></i>
                      <h5><?php echo htmlentities($result->ModelYear ?? ''); ?></h5>
                      <p>Reg.Year</p>
                    </li>
                    <li> <i class="fa fa-cogs" aria-hidden="true"></i>
                      <h5><?php echo htmlentities($result->FuelType ?? ''); ?></h5>
                      <p>Fuel Type</p>
                    </li>

                    <li> <i class="fa fa-user-plus" aria-hidden="true"></i>
                      <h5><?php echo htmlentities($result->SeatingCapacity ?? ''); ?></h5>
                      <p>Seater</p>
                    </li>
                    <li><i class="fa fa-wrench" aria-hidden="true"></i>

                      <h5><?php echo htmlentities($result->carType ?? ''); ?></h5>
                      <p>Transmission Type</p>
                    </li>

                    <li><i class="fa fa-car" aria-hidden="true"></i>

                      <h5><?php echo htmlentities($result->carspecs ?? ''); ?></h5>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#termsModal"
                  style="background: #ff0000; width: 220px;">
                  Book Now
                </button>

                <!-- Terms and Conditions Modal -->
                <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-center" style="color: white;margin-left: 0px;" id="termsModalLabel">Terms and Conditions for Car Rental
                          Service</h5>
                      </div>
                      <div class="modal-body" style="max-height: 700px; overflow-y: auto; flex-grow: 1;">
                        <ol>
                          <li><strong>Personal Information</strong>
                            <ul>
                              <li>Full Name</li>
                              <li>Address</li>
                              <li>Contact Number</li>
                              <li>At least 2 valid ID’s or Passport</li>
                            </ul>
                          </li>

                          <li><strong>General Terms</strong>
                            <ul>
                              <li>
                                Rental Agreement- By renting a vehicle from Triple Mike Transport and Car Rental Services,
                                you agree to all terms and conditions stated herein, as well as any terms outlined in your
                                rental contract.
                              </li>
                            </ul>
                          </li>

                          <li><strong>Rental Period</strong>
                            <ul>
                              <li>
                                Duration - The rental period is specified in the rental agreement. Extensions must be
                                requested
                                and approved in advance, and additional fees may apply.
                              </li>
                              <li>
                                Late Return- Late returns may incur additional charges. If a vehicle is returned later
                                than the scheduled
                                time without prior approval, you may be charged an hourly or daily rate for the excess
                                time.
                              </li>
                            </ul>
                          </li>

                          <li><strong>Payments and Deposits</strong>
                            <ul>
                              <li>
                                Reservation - the client are required to pay 50% of total booking fee.( To be paid and
                                upload the proof of
                                payment in the booking tab). The remaining balance will be paid through cash given to the
                                driver or online transactions.
                              </li>
                              <li>
                                Rental Fees - The rental fees are calculated based on the agreed-upon rate, rental period,
                                and any additional options selected.
                              </li>
                              <li>
                                Payment Method - All payments are due at the start of the rental period. Payment
                                processing is manual,
                                as per the business agreement between the owner and customer.
                              </li>
                            </ul>
                          </li>

                          <li><strong>Vehicle Use and Restrictions</strong>
                            <ul>
                              <li>
                                Permitted Use - The vehicle is to be used only as a passenger vehicle, operated by
                                authorized drivers.
                              </li>
                              <li>
                                Geographic Limitations - Vehicles are restricted to specific geographical areas book by
                                client.
                              </li>
                            </ul>
                          </li>

                          <li><strong>Insurance</strong>
                            <ul>
                              <li>
                                Liability - Triple Mike Transport and Car Rental Services is not responsible for any
                                injuries,
                                damage, or losses incurred during the rental period.
                              </li>
                            </ul>
                          </li>

                          <li><strong>Cancellations and No-Shows</strong>
                            <ul>
                              <li>
                                Cancellation Policy - strictly no cancellation and no refund.
                              </li>
                              <li>
                                Reschedule - is allowed 3 days prior to date of booking.
                              <li>
                                No-Show Policy - If the renter fails to pick up the vehicle at the scheduled time without
                                prior
                                notification, Triple Mike Transport and Car Rental Services reserves the right to cancel
                                the
                                reservation, and reservation fee will not be refunded.
                              </li>
                            </ul>
                          </li>

                          <li><strong>Agreement and Acceptance</strong>
                            <ul>
                              <li>
                                By agreeing to the rental agreement or receiving a vehicle from Triple Mike Transport and
                                Car Rental Services, the renter acknowledges and accepts all terms and conditions stated
                              </li>
                            </ul>
                          </li>
                        </ol>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bookingModal"
                          onclick="checkTermsCheckbox()">Accept</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Booking Modal -->
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
                        <form method="post" enctype="multipart/form-data" id="bookingForm" onsubmit="submitForm(event)">
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
                            <label for="dropoff" class="control-label">Venue Location:</label>
                            <select class="form-control" name="dropoff_location" required
                              style="height: calc(3.25rem + 2px);">
                              <option value="">Select Venue Location</option>
                              <?php foreach ($places as $place) { ?>
                                <option value="<?php echo $place->PlaceID; ?>">
                                  <?php echo htmlentities($place->PlaceName); ?>
                                </option>

                              <?php } ?>
                            </select>
                          </div>

                          <!-- Is Metro Manila Fields Only one Required! -->
                          <div class="form-group">
                            <label for="is-metro-manila" class="control-label">Is within Metro Manila?</label>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="is_metro_manila" id="metro-manila-yes" value="1" required>
                              <label class="form-check-label" for="metro-manila-yes">Yes</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="is_metro_manila" id="metro-manila-no" value="0">
                              <label class="form-check-label" for="metro-manila-no">No</label>
                            </div>
                          </div>


                          <!-- Date and Time Fields -->
                          <div class="form-group" id="fromDateField">
                            <label class="control-label">From Date and Time:</label>
                            <input type="datetime-local" class="form-control" id="fromdatetime" name="fromdatetime"
                              placeholder="From Date and Time" min="<?php echo date(format: 'Y-m-d\TH:i'); ?>" required
                              style="height: calc(3.25rem + 2px);" onchange="calculateEstimatedCost()">
                          </div>

                          <div class="form-group" id="toDateField">
                            <label class="control-label">To Date and Time:</label>
                            <input type="datetime-local" class="form-control" id="todatetime" name="todatetime"
                              placeholder="To Date and Time" min="<?php echo date('Y-m-d\TH:i'); ?>" required
                              style="height: calc(3.25rem + 2px);" onchange="calculateEstimatedCost()">
                          </div>

                          <!-- Within the Day Checkbox -->
                          <div class="form-group">
                            <label>
                              <input type="checkbox" id="withinDayCheckbox" onchange="toggleDateFields()"> Within the Day
                            </label>
                          </div>

                          <!-- Estimated Cost -->
                          <div class="form-group">
                            <label class="control-label">Estimated Cost</label>
                            <input type="text" class="form-control" id="estimatedCost" name="estimated_cost" value="₱ 0.00" readonly
                              style="height: calc(3.25rem + 2px);">
                          </div>

                          <!-- Message -->
                          <div class="form-group">
                            <textarea rows="4" class="form-control" name="message" placeholder="Itinerary"
                              required></textarea>
                          </div>

                          <!-- Payment Option -->
                          <div class="form-group">
                            <label class="control-label">Payment Option:</label>
                            <select class="selectpicker form-control" name="payment" id="payment-option" required
                              onchange="showPaymentFields()" style="height: calc(3.25rem + 2px);">
                              <option value="" disabled selected>Select Payment Method</option>
                              <option value="cash">Cash</option>
                              <option value="gcash">GCash</option>
                              <option value="bdo">BDO</option>
                              <option value="security bank">Security Bank</option>
                              <option value="bpi">BPI</option>
                            </select>
                          </div>

                          <!-- GCash Payment Fields -->
                          <div id="gcash-fields" style="display: none;">
                            <p>Send it to this <span id="payment-type">Gcash</span> Account: <span
                                id="account-number">0909584666</span></p>

                            <div class="form-group">
                              <label for="account_name" class="control-label">Account Name:</label>
                              <input type="text" class="form-control" id="account_name" name="account_name"
                                placeholder="Enter Account Name" required>
                            </div>

                            <div class="form-group">
                              <label for="account_number" class="control-label">Account Number:</label>
                              <input type="number" class="form-control" id="account_number" name="account_number"
                                placeholder="Enter Account Number" required>
                            </div>

                            <div class="form-group">
                              <label for="reference_number" class="control-label">Reference Number:</label>
                              <input type="text" class="form-control" id="reference_number" name="reference_number"
                                placeholder="Enter Reference Number" required>
                            </div>

                            <div class="form-group">
                              <label class="control-label">Upload Payment Receipt</label><br>
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

                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="termsCheckbox" required>
                              <label class="form-check-label" for="termsCheckbox">
                                I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and
                                  Conditions</a>.
                              </label>
                            </div>

                            <div class="form-group mt-2"
                              style="display: flex; justify-content: center; align-items: center;">
                              <input type="submit" class="btn" name="submit" value="Book Now" style="background: #ff0000;" id="submit-btn">
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


                <!-- Availability Modal -->
                <div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog" aria-labelledby="availabilityModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header" style="background-color: #ff0000; color: white;">
                        <h5 class="modal-title" id="availabilityModalLabel" style="color: white; margin-left: 0px;">Available Vehicles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="availabilityModalBody">
                        <!-- Dynamic message and content will be injected here -->
                        <div id="reservationMessage" class="mb-3 text-danger" style="font-size: 1.2rem;"></div>
                        
                        <!-- Vehicle Grid -->
                        <div class="row" id="vehicleGrid"></div>

                        <!-- Estimated Cost -->
                        <div class="form-group mt-4">
                          <label class="control-label">Estimated Cost</label>
                          <input type="text" class="form-control" id="newEstimatedCost" name="new_estimated_cost" value="₱ 0.00" readonly
                            style="height: calc(3.25rem + 2px);">
                        </div>
                      </div>
                      <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" onclick="reSubmitForm()">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>




                <script>
                  function submitForm(event) {
                    // Prevent the default form submission
                    event.preventDefault();

                    // Create FormData object
                    const formData = new FormData(document.getElementById('bookingForm'));

                    // Extract the vehicle ID (vhid) from the URL
                    const vehicleId = new URLSearchParams(window.location.search).get('vhid');

                    // Add vhid to the FormData
                    if (vehicleId) {
                      formData.append('vhid', vehicleId);
                    } else {
                      alert('Vehicle ID is missing in the URL.');
                      return; // Stop submission if vhid is missing
                    }

                    console.log("Form Data Contents:");
                    for (const [key, value] of formData.entries()) {
                      console.log(`${key}: ${value}`);
                    }
                    

                    // Send the data to the server
                    fetch('car_occupied.php', {
                      method: 'POST',
                      body: formData
                    })
                      .then(response => response.json()) // Parse the server's JSON response
                      .then(data => {
                        if (data.existingReservation) {
                          //alert('The reservation already exists!'); // For Debug: Optional
                          showAvailabilityModal('The car is already booked on the selected dates showing alternative Vehicles.', formData);
                        } else {
                          alert('The reservation is available.');
                          // Call function to post data to car_reserving.php
                          reserveCar(formData);
                        }
                        console.log('Response from server:', data);
                      })
                      .catch(error => {
                        console.error('Error:', error);
                        alert('There was an error submitting the request.');
                      });
                  }


                  function reserveCar(formData) {
                    fetch('car_reserving.php', {
                      method: 'POST',
                      body: formData
                    })
                      .then(response => response.json())
                      .then(data => {
                        if (data.success) {
                          alert('Car reservation successfully completed!');
                        } else {
                          alert('Error during car reservation: ' + data.message);
                        }
                        console.log('Car Reserving Response:', data);
                        window.history.back(); // Go back to the previous page
                        setTimeout(function() {
                          window.location.reload(true); // Refresh the page after navigating back
                        }, 100); // Small delay to ensure the back action happens first
                      })
                      .catch(error => {
                        console.error('Error:', error);
                        alert('There was an error completing the car reservation.');
                        window.history.back(); // Go back to the previous page
                        setTimeout(function() {
                          window.location.reload(true); // Refresh the page after navigating back
                        }, 100); // Small delay to ensure the back action happens first
                      });
                  }

                  function showAvailabilityModal(message, formData) {
                    fetch('car_availability.php', {
                      method: 'POST',
                      body: formData,
                    })
                      .then(response => {
                        if (!response.ok) {
                          throw new Error('Network response was not ok.');
                        }
                        return response.json();
                      })
                      .then(data => {
                        console.log('Response:', data);

                        if (data.success) {
                          if (data.vehicles.length > 0)
                          {
                            const reservationMessage = document.getElementById('reservationMessage');
                            const modalBody = document.getElementById('vehicleGrid');

                            // Get the values from the datetime inputs
                            const fromDateTime = document.getElementById("fromdatetime").value;
                            const toDateTime = document.getElementById("todatetime").value;

                            // Ensure both values are present
                            if (fromDateTime && toDateTime) {
                              // Convert ISO datetime string to a more readable format
                              const formatDateTime = (datetime) => {
                                const options = { 
                                  year: "numeric", 
                                  month: "long", 
                                  day: "numeric", 
                                  hour: "2-digit", 
                                  minute: "2-digit", 
                                  hour12: true 
                                };
                                return new Date(datetime).toLocaleString(undefined, options);
                              };

                              const formattedFromDateTime = formatDateTime(fromDateTime);
                              const formattedToDateTime = formatDateTime(toDateTime);

                              // Update the reservation message with formatted values
                              reservationMessage.textContent = `Sorry, the car you have selected is already reserved from ${formattedFromDateTime} to ${formattedToDateTime}. Here are some alternatives based on your selections.`;
                            }

                            // Clear previous content
                            modalBody.innerHTML = '';

                            if (data.vehicles && data.vehicles.length > 0) {
                              // Loop through vehicles and create cards
                              data.vehicles.forEach(vehicle => {
                                const vehicleCard = `
                                  <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                      <img 
                                        src="${vehicle.Vimage1 ? 'admin/img/vehicleimages/' + vehicle.Vimage1 : 'img/bmw-x5-1.jpg'}" 
                                        class="card-img-top" 
                                        alt="${vehicle.VehiclesTitle}">
                                      <div class="card-body">
                                        <h5 class="card-title">${vehicle.BrandName}, ${vehicle.VehiclesTitle}</h5>
                                        <p class="card-text">
                                          <strong>Price:</strong> ₱${Number(vehicle.PricePerDay).toLocaleString()} Per Day<br>
                                          <strong>Rating:</strong> ${vehicle.avg_rating.toFixed(1)}/5 <i class="fa fa-star"></i>
                                        </p>
                                      </div>
                                      <div class="card-footer text-center">
                                        <button class="btn btn-primary btn-block" onclick="handleVehicleSelection(${vehicle.id}, ${vehicle.PricePerDay})">Select</button>
                                      </div>
                                    </div>
                                  </div>
                                `;
                                modalBody.insertAdjacentHTML('beforeend', vehicleCard);
                              });

                              // Show the availability modal
                              $('#bookingModal').modal('hide');
                              $('#availabilityModal').modal('show');
                            } else {
                              reservationMessage.textContent = 'Sorry, the car you selected is already reserved, and no alternatives are available at this time.';
                            }
                          }
                          else{
                            alert('Sorry, the car you selected is already reserved, and no alternatives are available at this time.');
                            
                            window.history.back(); // Go back to the previous page
                            setTimeout(function() {
                              window.location.reload(true); // Refresh the page after navigating back
                            }, 100); // Small delay to ensure the back action happens first
                          }
                        } else {
                          alert(`Error: ${data.message}`);
                        }
                      })
                      .catch(error => {
                        console.error('Error:', error);
                        alert('There was an error checking vehicle availability.');
                      });
                  }
                </script>
              
                <script>
                  let selectedVehicleId = null;
                  let selectedPricePerDay = null;

                  function handleVehicleSelection(vehicleId, pricePerDay) {
                    selectedVehicleId = vehicleId;
                    selectedPricePerDay = pricePerDay;

                    //console.log(`Selected Vehicle ID: ${selectedVehicleId}, Price Per Day: ₱${selectedPricePerDay.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`);

                    // Trigger cost calculation with the new price
                    calculateEstimatedCost();

                    //Add a true condition for the submit button...
                      
                  }

                  function reSubmitForm() {
                    console.log('Reservation is resubmitted');

                    // Reinitialize the FormData object with updated vehicle ID
                    const formData = new FormData(document.getElementById('bookingForm'));

                    
                    // Add vhid to the FormData
                    if (selectedVehicleId) {
                      formData.append('vhid', selectedVehicleId);
                    } else {
                      alert('Vehicle ID is missing in the URL.');
                      return;
                    }

                    console.log("Form Data Contents:");
                    for (const [key, value] of formData.entries()) {
                      console.log(`${key}: ${value}`);
                    }
                    
                    // Send the data to the server
                    fetch('car_occupied.php', {
                      method: 'POST',
                      body: formData
                    })
                      .then(response => response.json()) // Parse the server's JSON response
                      .then(data => {
                        if (data.existingReservation) {
                          alert('The reservation already exists!');
                        } else {
                          alert('The reservation is available.');
                          // Call function to post data to car_reserving.php
                          reReserveCar(formData);
                        }
                        console.log('Response from server:', data);
                      })
                      .catch(error => {
                        console.error('Error:', error);
                        alert('There was an error submitting the request.');
                      });
                  }


                  function reReserveCar(formData) {
                    fetch('car_reserving.php', {
                      method: 'POST',
                      body: formData
                    })
                      .then(response => response.json())
                      .then(data => {
                        if (data.success) {
                          alert('Car reservation successfully completed!');
                        } else {
                          alert('Error during car reservation: ' + data.message);
                        }
                        console.log('Car Reserving Response:', data);
                        window.history.back(); // Go back to the previous page
                        setTimeout(function() {
                          window.location.reload(true); // Refresh the page after navigating back
                        }, 100); // Small delay to ensure the back action happens first
                      })
                      .catch(error => {
                        console.error('Error:', error);
                        alert('There was an error completing the car reservation.');
                        window.history.back(); // Go back to the previous page
                        setTimeout(function() {
                          window.location.reload(true); // Refresh the page after navigating back
                        }, 100); // Small delay to ensure the back action happens first
                      });
                  }

                  function toggleDateFields() {
                    const checkbox = document.getElementById("withinDayCheckbox");
                    const toDateField = document.getElementById("toDateField");
                    const fromDateInput = document.getElementById("fromdatetime");
                    const toDateInput = document.getElementById("todatetime");

                    if (checkbox.checked) {
                      // Hide "To Date and Time" field and set its value to the end of the same date as "From Date and Time"
                      toDateField.style.display = "none";

                      // Extract the date portion from fromDateInput and set to 23:59
                      if (fromDateInput.value) {
                        const [datePart] = fromDateInput.value.split("T"); // Split into date and time
                        toDateInput.value = `${datePart}T23:59`;
                      } else {
                        console.warn("fromDateInput value is empty or invalid.");
                      }

                      console.log("ToDateField hidden. ToDateInput value set to:", toDateInput.value); // Log new value of toDateInput

                      calculateEstimatedCost(true); // Use daily price only
                    } else {
                      // Show "To Date and Time" field
                      toDateField.style.display = "block";
                      console.log("ToDateField shown. FromDateInput value:", fromDateInput.value, "ToDateInput value:", toDateInput.value); // Log both values
                      calculateEstimatedCost(false); // Calculate based on date range
                    }
                  }



                  function calculateEstimatedCost(withinDay = false) {
                    const dailyPrice = selectedPricePerDay || parseFloat("<?php echo number_format(htmlentities($result->PricePerDay), 2, '.', ''); ?>");
                    const estimatedCost = document.getElementById("estimatedCost");
                    const newEstimatedCost = document.getElementById("newEstimatedCost");

                    const fromDateInput = document.getElementById("fromdatetime").value;
                    const toDateInput = document.getElementById("todatetime").value;

                    if (!fromDateInput || (toDateInput === "" && !withinDay)) {
                      estimatedCost.value = "₱ 0.00";
                      return;
                    }

                    const fromDate = new Date(fromDateInput);

                    if (withinDay) {
                      estimatedCost.value = `₱ ${dailyPrice.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
                      newEstimatedCost.value = `₱ ${dailyPrice.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
                    } else {
                      const toDate = new Date(toDateInput);

                      if (toDate > fromDate) {
                        const diffInTime = toDate - fromDate;
                        const diffInDays = Math.ceil(diffInTime / (1000 * 60 * 60 * 24));
                        const totalCost = diffInDays * dailyPrice;

                        estimatedCost.value = `₱ ${totalCost.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
                        newEstimatedCost.value = `₱ ${totalCost.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
                        console.log("Total Cost Calculated:", totalCost);
                      } else {
                        estimatedCost.value = "₱ 0.00";
                        newEstimatedCost.value = "₱ 0.00";
                      }
                    }
                  }

                
                  function showPaymentFields() {
                    var paymentOption = document.getElementById("payment-option").value;
                    var gcashFields = document.getElementById("gcash-fields");
                    var paymentType = document.getElementById("payment-type");
                    var accountNumber = document.getElementById("account-number");

                    var accountNumbers = {
                      "gcash": "0909584666",
                      "bdo": "010650235126",
                      "security bank": "0000059935150",
                      "bpi": "09473821708",
                    };

                    if (paymentOption !== "cash") {
                      gcashFields.style.display = "block";

                      paymentType.textContent = paymentOption.charAt(0).toUpperCase() + paymentOption.slice(1);
                      accountNumber.textContent = accountNumbers[paymentOption] || "Unknown";

                      document.getElementById('account_name').setAttribute('required', 'required');
                      document.getElementById('account_number').setAttribute('required', 'required');
                      document.getElementById('gcash_receipt').setAttribute('required', 'required');
                      document.getElementById('reference_number').setAttribute('required', 'required');
                    } else {
                      gcashFields.style.display = "none";

                      document.getElementById('account_name').removeAttribute('required');
                      document.getElementById('account_number').removeAttribute('required');
                      document.getElementById('gcash_receipt').removeAttribute('required');
                      document.getElementById('reference_number').removeAttribute('required');
                    }
                  }

                  function checkTermsCheckbox() {
                    const termsCheckbox = document.getElementById('termsCheckbox');
                    if (termsCheckbox) {
                      termsCheckbox.checked = true;
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

  <!-- <script src="assets/js/jquery.min.js"></script> -->
  <!-- <script src="assets/js/bootstrap.min.js"></script> -->
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