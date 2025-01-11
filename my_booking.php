<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Car Rental Portal | My Booking</title>
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
        <style>
            .btn-yellow {
                background: yellow;
                color: black;
                border: 1px solid yellow;
            }
        </style>
    </head>

    <body id="body">
        <?php include('includes/header.php'); ?>
        <section id="innerBanner" style="background: black;">
            <div class="inner-content">
                <h2><span style="color: #009bc2">My Bookings</span><br>We create the opportunities!</h2>
                <div>
                </div>
            </div>
        </section><!-- #Page Banner -->

        <main id="main">

            <?php
            $useremail = $_SESSION['login'];
            $sql = "SELECT * from tblusers where EmailId=:useremail";
            $query = $dbh->prepare($sql);
            $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            $cnt = 1;
            if ($query->rowCount() > 0) {
                foreach ($results as $result) { ?>
                    <section class="user_profile inner_pages">
                        <div class="container">
                            <div class="user_profile_info gray-bg padding_4x4_40">
                                <div class="upload_user_logo"><img src="<?php echo htmlentities($result->image); ?>" alt="image"
                                        style="width: 150px; height: 150px; border-radius:50%;">
                                </div>

                                <div class="dealer_info">
                                    <h5><?php echo htmlentities($result->FullName); ?></h5>
                                    <p><?php echo htmlentities($result->Address); ?><br>
                                        <?php echo htmlentities($result->City); ?>&nbsp;<?php echo htmlentities($result->Country);
                }
            } ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <?php include('includes/sidebar.php'); ?>

                            <div class="col-md-6 col-sm-8">
                                <div class="profile_wrap">
                                    <h5 class="uppercase underline">My Bookings </h5>
                                    <div class="my_vehicles_list">
                                        <ul class="vehicle_listing">
                                            <?php
                                            $useremail = $_SESSION['login'];
                                            $sql = "SELECT tblvehicles.Vimage1 as Vimage1,tblvehicles.VehiclesTitle,tblvehicles.id as vid,tblbrands.BrandName,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.Status  from tblbooking join tblvehicles on tblbooking.VehicleId=tblvehicles.id join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblbooking.userEmail=:useremail order by tblbooking.id desc";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>

                                                    <li>
                                                        <div class="vehicle_img">
                                                            <a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid); ?>"><img src="
                                                                admin/img/vehicleimages/<?php echo !empty($result->Vimage1) ? htmlentities($result->Vimage1) : 'civicfront.jpg'; ?>" alt="image"></a> </div>
                                                        <div class="vehicle_title">
                                                            <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid); ?>"> <?php echo htmlentities($result->BrandName); ?> , <?php echo htmlentities($result->VehiclesTitle); ?></a></h6>
                  <p><b>From Date:</b> <?php echo htmlentities($result->FromDate); ?><br /> <b>To Date:</b> <?php echo htmlentities($result->ToDate); ?></p>
                </div>
                <?php if ($result->Status == 1) { ?>
                <div class=" vehicle_status"> <a href="#" class="btn btn-yellow btn-xs">Confirmed</a>
                                                                        <div class="clearfix"></div>
                                                            </div>

                                                        <?php } else if ($result->Status == 2) { ?>
                                                                <div class="vehicle_status"> <a href="#"
                                                                        class="btn outline btn-xs">Cancelled</a>
                                                                    <div class="clearfix"></div>
                                                                </div>


                                                        <?php } else if ($result->Status == 4) { ?>
                                                                    <div class="vehicle_status"> <a href="#"
                                                                            class="btn outline btn-xs active-btn">Done</a>
                                                                        <div class="clearfix"></div>
                                                                    </div>


                                                        <?php } else { ?>
                                                                    <div class="vehicle_status"> <a href="#" class="btn outline btn-xs">Not
                                                                            Confirm yet</a>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                        <?php } ?>
                                                        <div style="float: left">
                                                            <p><b>Message:</b> <?php echo htmlentities($result->message); ?> </p>
                                                        </div>
                                                    </li>
                                                <?php }
                                            } ?>


                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav aria-label="Page navigation example" class="pagination d-flex justify-content-center">
                        <ul class="pagination d-flex justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo $page <= 1 ? '#' : '?page=' . ($page - 1); ?>"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>"><a class="page-link"
                                        href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="<?php echo $page >= $totalPages ? '#' : '?page=' . ($page + 1); ?>"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
            </section>

            <section id="call-to-action" class="wow fadeInUp">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9 text-center text-lg-left">
                            <h3 class="cta-title">Get Our Service</h3>
                            <p class="cta-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores quae porro
                                consequatur aliquam, incidunt fugiat culpa esse aute nulla cupidatat non proident, sunt in
                                culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                        <div class="col-lg-3 cta-btn-container text-center">
                            <a class="cta-btn align-middle" href="#contact">Contact Us</a>
                        </div>
                    </div>

                </div>
            </section><!-- #call-to-action -->


            <!-- Terms & Condition Modal with Driver -->
            <div class="modal fade" id="tcWithDriverModal" tabindex="-1" role="dialog"
                aria-labelledby="tcWithDriverModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tcWithDriverModalLabel">Terms & Condition with Driver</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Condition Modal without Driver -->
            <div class="modal fade" id="tcWithoutDriverModal" tabindex="-1" role="dialog"
                aria-labelledby="tcWithoutDriverModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tcWithoutDriverModalLabel">Terms & Condition without Driver</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include('includes/footer.php'); ?>
        <!-- #footer -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
        <!--Login-Form -->
        <?php include('includes/userpay.php'); ?>
        <?php include('includes/upload.php'); ?>
        <!--/Login-Form -->

        <!--Register-Form -->
        <?php include('includes/registration.php'); ?>

        <!--/Register-Form -->

        <!--Forgot-password-Form -->
        <?php include('includes/forgotpassword.php'); ?>
        <!--/Forgot-password-Form -->

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
    <?php
} ?>