<?php
session_start();
include('includes/config.php');
error_reporting(0);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Car rental portal</title>
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
    <link href="lib/font/font-awesome.css" rel="stylesheet" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="css/style.css" rel="stylesheet">

    <style media="screen">
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
        }

        .popup {
            background: rgba(0, 0, 0, 0.7);
            width: 60%;
            height: 60%;
            padding: 1% 1%;
            position: fixed;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
            font-family: "Poppins", sans-serif;
            display: none;
            text-align: center;
            z-index: 9999;
        }

        .popup .container {
            width: 100%;
            /* Fixed width */
            height: 100%;
            /* Fixed height */
            max-width: 100%;
            /* Make sure the image doesn't exceed the container width */
            max-height: 100%;
            /* Make sure the image doesn't exceed the container height */
            margin: 0 auto;
            /* Center the container horizontally */
        }

        .popup img {
            height: 100%;
            width: 100%;
            border: 2px solid white;
            max-width: 70%;
            /* Make the image responsive */
            max-height: 70%;
            /* Make the image responsive */
            display: block;
            /* Remove any whitespace below the image */
            margin: 0 auto;
            /* Center the image horizontally */
        }

        .popup button {
            display: block;
            margin: 0 0 10px auto;
            font-size: 30px;
            color: #ffffff;
            background: none;
            width: 10%;
            height: 10%;
            border: none;
            outline: none;
            cursor: pointer;

        }
    </style>

</head>

<body id="body">
    <?php include('includes/header.php'); ?>

    <section id="hero" class="clearfix" style="background-image: url('bg.png'); height: 800px;">
        <div class="container">

            <div class="hero-banner">
            </div>

            <div class="hero-content">
                <h3 style="margin: 40px 0px 7px -120px; color: white;">We offer a quality service</h3>
                <p style="margin: 0 0px 40px -120px; color: white;">Best Rental Cars in your
                    location.</p>
                <div style="margin: 0 0px 40px -120px;">
                    <?php if (strlen($_SESSION['login']) == 0) {
                        ?>
                        <a href=" #loginform" data-toggle="modal" data-dismiss="modal" class="btn-banner">Login /
                            Register</a>
                        <?php
                    } ?>
                </div>
            </div>

        </div>
    </section><!-- #Hero -->

    <main id="main">
        <?php
        $tid = 1;
        $sql = "SELECT * from tblbanner ORDER BY RAND() LIMIT 1";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $cnt = 1;
        foreach ($results as $result) { ?>
        <?php } ?>

        <!--==========================
      Services Section
      ============================-->
        <section id="services">
            <div class="container">
                <div class="section-header">
                    <h2 style="font-family:'Tahoma',sans-serif">Find the Best Car for you</h2>
                    <p style="font-family:'Tahoma',sans-serif">Once choosing a car rental, you must consider several
                        factors to make sure that you have made the best decision.</p>
                </div>

                <div class="row">
                    <?php
                    $sql = "SELECT tblvehicles.VehiclesTitle,tblbrands.BrandName,tblvehicles.PricePerDay,tblvehicles.FuelType,tblvehicles.ModelYear,tblvehicles.id,tblvehicles.SeatingCapacity,tblvehicles.VehiclesOverview,tblvehicles.Vimage1 from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand order by rand() limit 3 ";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                    ?>
                            <div class="col-lg-4">
                                <div class="box wow  fadeInLeft" style="width:104%; height: 87.8%;">
                                    <div class="car-info-box">
                                        <a href="car_details.php?vhid=<?php echo htmlentities($result->id); ?>">
                                            <img src="admin/img/vehicleimages/<?php echo !empty($result->Vimage1) ? htmlentities($result->Vimage1) : 'civicfront.jpg'; ?>" alt="image" style="height: 180px; border-bottom:  solid; border-color: #fa2837b3; width: 290px; max-height: 180px;">

                                            <ul style=" width: 290px;  background-color: #fa2837;">
                                                <li><i class="fa fa-car"
                                                        aria-hidden="true"></i><?php echo htmlentities($result->FuelType); ?>
                                                </li>
                                                <li><i class="fa fa-calendar"
                                                        aria-hidden="true"></i><?php echo htmlentities($result->ModelYear); ?>
                                                    Model</li>
                                                <li><i class="fa fa-user"
                                                        aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity); ?>
                                                    seats</li>
                                            </ul>
                                        </a>
                                        <div class="car-title-m">
                                            <h3><a
                                                    href="car_details.php?vhid=<?php echo htmlentities($result->id); ?>">
                                                    <?php echo substr($result->VehiclesTitle, 0, 21); ?></a></h3>
                                            <h7 class="price" style="  float:left; margin-left: 5%;">Price Per Day : ₱
                                                <?php echo number_format(htmlentities($result->PricePerDay), 2); ?></h7>

                                        </div>
                                        <div class="inventory_info_m " style="height: 100px;">
                                            <p><?php echo substr($result->VehiclesOverview, 0, 70); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } ?>
                </div>
            </div>
        </section>
    </main>

    <section class="section-padding testimonial-section parallex-bg">
        <div class="container div_zindex" style="height: 400px;">
            <div class="section-header black-text text-center" style="height: 50px">
                <h2 style="font-family:'Tahoma',sans-serif">Our Satisfied Customers</h2>
            </div>
            <div class="row">
                <div id="testimonial-slider" class="owl-carousel owl-theme" style="opacity: 10;display: none !important;">
                    <?php
                    $tid = 1;
                    $sql = "SELECT tbltestimonial.Testimonial,tblusers.FullName,tblusers.image from tbltestimonial join tblusers on tbltestimonial.UserEmail=tblusers.EmailId where tbltestimonial.status=:tid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':tid', $tid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {  ?>


                            <div class="testimonial-m" style="margin-top: 3%;">
                                <div class="testimonial-img" style="width: 30%; height:  50%;"> <img
                                        src="<?php echo htmlentities($result->image); ?>" alt=""
                                        style="margin-left: 5px;width: 155px; height: 140px;" /> </div>
                                <div class="testimonial-content" style=" width: 100%;  border: 2px solid black;">
                                    <div class="testimonial-heading">
                                        <h5><?php echo htmlentities($result->FullName); ?></h5>
                                        <p><?php echo htmlentities($result->Testimonial); ?></p>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>



                </div>
            </div>
        </div>
     
        <div class="dark-overlay"></div>

    </section>
    <?php include('includes/contact.php'); ?>
    <!-- #call-to-action -->
    <!--==========================
    Footer
    ============================-->
    <?php include('includes/footer.php'); ?>
    <!-- #footer -->

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