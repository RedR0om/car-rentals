<?php
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
  <meta charset="utf-8">
  <title>Car Rental | About Us</title>
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

  <section id="innerBanner" style="background: black;">
    <div class="inner-content">
      <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #fa2837;">CAR LISTING</span><br>We provide
        high
        quality cars</h2>
    </div>
  </section><!-- #Page Banner -->

  <main id="main">
    <section id="about" class="wow fadeInUp">
      <div class="container">
        <div class="result-sorting-wrapper" style="margin: 5%;">
          <div class="sorting-count">
            <?php
            $sql = "SELECT COUNT(id) AS count FROM tblvehicles";
            $query = $dbh->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            $cnt = $result->count;
            ?>
            <p><?php echo htmlentities($cnt); ?> Listings</p>
          </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
          <!-- Filter Buttons -->
          <button id="showAvailable" class="btn btn-success mr-2">Show Available</button>
          <button id="showNotAvailable" class="btn btn-danger">Show Not Available</button>
        </div>

        <div class="row">
          <div class="col-md-9">
            <?php
            $itemsPerPage = 3;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $lastId = isset($_GET['lastId']) ? (int) $_GET['lastId'] : 0;
            $availabilityFilter = isset($_GET['available']) ? $_GET['available'] : 'all';

            // Modify SQL based on filter selection
            $availabilitySQL = "";
            if ($availabilityFilter === 'available') {
              $availabilitySQL = "HAVING ongoing_bookings = 0";
            } elseif ($availabilityFilter === 'not_available') {
              $availabilitySQL = "HAVING ongoing_bookings > 0";
            }

            $sql = "SELECT v.id, v.VehiclesTitle, v.PricePerDay, v.Vimage1, v.SeatingCapacity, v.ModelYear, 
                                v.FuelType, b.BrandName,
                                (SELECT AVG(rating) FROM tblbooking WHERE VehicleId = v.id) AS avg_rating,
                                (SELECT COUNT(*) FROM tblbooking WHERE VehicleId = v.id AND Status = 'ongoing') AS ongoing_bookings
                                FROM tblvehicles v
                                JOIN tblbrands b ON b.id = v.VehiclesBrand
                                WHERE v.id > :lastId
                                $availabilitySQL
                                ORDER BY v.id ASC
                                LIMIT :itemsPerPage";
            $query = $dbh->prepare($sql);
            $query->bindParam(':lastId', $lastId, PDO::PARAM_INT);
            $query->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
              foreach ($results as $result) {
                $lastId = $result->id;
                $isAvailable = $result->ongoing_bookings > 0 ? 'Not Available' : 'Available';
                ?>
                <div class="card mb-4">
                  <div class="row no-gutters">
                    <div class="col-md-5">
                      <img
                        src="admin/img/vehicleimages/<?php echo !empty($result->Vimage1) ? htmlentities($result->Vimage1) : '1audiq8.jpg'; ?>"
                        alt="image" style="width: 100%; height: 100%;">
                    </div>
                    <div class="col-md-7">
                      <div class="card-body">
                        <h5 class="card-title">
                          <a href="car_details.php?vhid=<?php echo htmlentities($result->id); ?>">
                            <?php echo htmlentities($result->BrandName); ?>,
                            <?php echo htmlentities($result->VehiclesTitle); ?>
                          </a>
                        </h5>
                        <p class="card-text">
                          <strong>₱<?php echo number_format(htmlentities($result->PricePerDay), 2); ?></strong>
                          Per Day
                        </p>
                        <p class="card-text">Rating:
                          <?php echo htmlentities($result->avg_rating); ?>/5.00 <i class="fa fa-star"></i>
                        </p>
                        <p class="card-text"
                          style="color: <?php echo $isAvailable == 'Not Available' ? 'red' : 'green'; ?>;">
                          <?php echo $isAvailable; ?>
                        </p>
                        <ul class="list-inline">
                          <li class="list-inline-item"><i class="fa fa-user"></i>
                            <?php echo htmlentities($result->SeatingCapacity); ?> seats</li>
                          <li class="list-inline-item"><i class="fa fa-calendar"></i>
                            <?php echo htmlentities($result->ModelYear); ?> model</li>
                          <li class="list-inline-item"><i class="fa fa-car"></i>
                            <?php echo htmlentities($result->FuelType); ?></li>
                        </ul>
                        <a href="car_details.php?vhid=<?php echo htmlentities($result->id); ?>"
                          class="btn btn-primary mt-2 w-100">View Details</a>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            }
            ?>
          </div>

          <aside class="col-md-3">
            <div class="sidebar_widget" style="border: 2px solid black;">
              <h5 class="widget_heading"><i class="fa fa-car"></i> Recently Listed Cars</h5>
              <div class="recent_addedcars">
                <ul>
                  <?php
                  $sql = "SELECT v.id, v.VehiclesTitle, v.PricePerDay, v.Vimage1, b.BrandName
                                    FROM tblvehicles v
                                    JOIN tblbrands b ON b.id = v.VehiclesBrand
                                    ORDER BY v.id DESC
                                    LIMIT 4";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $recentCars = $query->fetchAll(PDO::FETCH_OBJ);
                  foreach ($recentCars as $car) {
                    ?>
                    <li class="gray-bg">
                      <div class="recent_post_img">
                        <a href="car_details.php?vhid=<?php echo htmlentities($car->id); ?>">
                          <img
                            src="admin/img/vehicleimages/<?php echo !empty($car->Vimage1) ? htmlentities($car->Vimage1) : 'default.jpg'; ?>"
                            alt="image">
                        </a>
                      </div>
                      <div class="recent_post_title">
                        <a href="car_details.php?vhid=<?php echo htmlentities($car->id); ?>">
                          <?php echo htmlentities($car->BrandName); ?>,
                          <?php echo htmlentities($car->VehiclesTitle); ?>
                        </a>
                        <p class="widget_price">
                          ₱<?php echo number_format(htmlentities($car->PricePerDay), 2); ?> Per
                          Day</p>
                      </div>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </main>


  <?php include('includes/footer.php'); ?><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!--Login-Form -->
  <?php include('includes/login.php'); ?>
  <!--/Login-Form -->

  <!--Register-Form -->
  <?php include('includes/registration.php'); ?>

  <!--/Register-Form -->

  <!--Forgot-password-Form -->
  <?php include('includes/forgotpassword.php'); ?>


  <script>
    document.getElementById('showAvailable').addEventListener('click', function () {
      window.location.href = "?available=available";
    });
    document.getElementById('showNotAvailable').addEventListener('click', function () {
      window.location.href = "?available=not_available";
    });
  </script>
  <!-- JavaScript  -->
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
  <script src="js/main.js"></script>

</body>

</html>