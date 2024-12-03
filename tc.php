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
            <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #ff0000">READ</span><br>Terms and Condition
            </h2>
            <div>
            </div>
        </div>
    </section><!-- #Page Banner -->

    <main id="main">

        <div class="container">
            <br>
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" style="color: #fff"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Terms & Conditions for Renting
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <h3>TERMS AND CONDITIONS</h3>
                            <?php
                            $tid = 1;
                            $sql = "SELECT * from tblrentingtc";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            foreach ($results as $result) { ?>
                                <div>

                                    <h4><?php echo htmlentities($result->title); ?></h4>
                                    <p><?php echo htmlentities($result->description); ?></p>

                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" style="color: #fff"
                                data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Terms & Conditions for Renting with Driver
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <h3>TERMS AND CONDITIONS</h3>
                            <?php
                            $tid = 1;
                            $sql = "SELECT * from tbltcwdriver";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            foreach ($results as $result) { ?>
                                <div>

                                    <h4><?php echo htmlentities($result->title); ?></h4>
                                    <p><?php echo htmlentities($result->description); ?></p>

                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" style="color: #fff"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Terms & Conditions for Renting without Driver
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <h3>TERMS AND CONDITIONS</h3>
                            <?php
                            $tid = 1;
                            $sql = "SELECT * from tbltcwodriver";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            foreach ($results as $result) { ?>
                                <div>

                                    <h4><?php echo htmlentities($result->title); ?></h4>
                                    <p><?php echo htmlentities($result->description); ?></p>

                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" style="color: #fff"
                                data-target="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
                                Requirements before booking
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <h3>Requirements</h3>
                            <?php
                            $tid = 1;
                            $sql = "SELECT * from tblrequirements";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            foreach ($results as $result) { ?>
                                <div>

                                    <h4><?php echo htmlentities($result->title); ?></h4>
                                    <p><?php echo htmlentities($result->description); ?></p>

                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>


        <!-- Contact Us -->
        <?php include('includes/contact.php'); ?>
    </main>

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
    <script src="js/main.js"></script>

</body>

</html>