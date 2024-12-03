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
      <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #fa2837;">ABOUT US</span><br>We provide high
        quality cars</h2>
    </div>
  </section><!-- #Page Banner -->

  <main id="main">
    <!-- About Us Section -->
    <section id="about" class="wow fadeInUp">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 about-img">
            <video width="600" height="290" controls loop autoplay muted style="width: 100%;">
              <source src="about-vid1.mp4" type="video/mp4">
            </video>
          </div>

          <div class="col-lg-6 content">
            <p
              style="font-size: calc(15px + 0.390625vw); text-indent: 90px; text-align: justify; font-family:'Tahoma',sans-serif">
              TRIPLE MIKE TRANSPORT SERVICES operate one of the world's best-known car rental brands with different
              locations in parts of Luzon. TRIPLE MIKE TRANSPORT SERVICES has a long history of innovation in the car
              rental industry and is one of the best customer services for customer loyalty. <br><br>
              TRIPLE MIKE TRANSPORT SERVICES is owned by Mark Monis Matabang, which operates and licenses the brand
              throughout the Philippines. <br><br>
              To ensure the safety of the car owners, customers must present the following legal documents:
            </p>
            <ul>
              <li style="font-size: calc(15px + 0.390625vw);font-family:'Tahoma',sans-serif"><i
                  class="icon ion-ios-checkmark-outline"></i> 1 Valid ID (Original and Photocopy)</li>
              <li style="font-size: calc(15px + 0.390625vw);font-family:'Tahoma',sans-serif"><i
                  class="icon ion-ios-checkmark-outline"></i> Police Clearance</li>
              <li style="font-size: calc(15px + 0.390625vw);font-family:'Tahoma',sans-serif"><i
                  class="icon ion-ios-checkmark-outline"></i> Driver's License</li>
            </ul>
          </div>
        </div>

        <!-- Mission and Vision Section -->
        <div class="row" style="margin-top: 50px;">
          <div class="col-lg-6">
            <!-- Mission Section -->
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 2.5em; color: #fa2837; font-weight: bold;">
              MISSION</h2>
            <p
              style="font-size: 1.2em; font-family: 'Tahoma', sans-serif; text-align: justify; line-height: 1.6; max-width: 900px; margin: 0 auto;">
              "We will ensure a stress-free car rental experience by providing superior services that cater to our
              customers' individual needs... always conveying the 'We Try Harder' spirit with knowledge, caring, and a
              passion for excellence."
            </p>
          </div>
          <div class="col-lg-6">
            <!-- Vision Section -->
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 2.5em; color: #fa2837; font-weight: bold;">
              VISION</h2>
            <p
              style="font-size: 1.2em; font-family: 'Tahoma', sans-serif; text-align: justify; line-height: 1.6; max-width: 900px; margin: 0 auto;">
              "We will lead our industry by defining service excellence and building unmatched customer loyalty."
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Us Section -->
    <?php include('includes/contact.php'); ?>
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