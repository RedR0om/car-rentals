<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST["verify_email"]))
{
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    // connect with database
    $conn = mysqli_connect("localhost", "root", "", "carrental");

    // mark email as verified
    $sql = "UPDATE tblusers SET email_verified_at = NOW() WHERE EmailId = '" . $email . "' AND verification_code = '" . $verification_code . "'";
    $result  = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) == 0)
    {
        die("Verification code failed.");
    }

    echo "<script>alert('Registration successful. You can now login.');</script>";
    echo "<script>window.location.href='index.php';</script>";
}

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
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body id="body">
    <?php include('includes/header.php');?>
    <section id="innerBanner" style="background: black;">
        <div class="inner-content">
            <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #009bc2">OTP VERIFICATION</span></h2>
            <div>
            </div>
        </div>
    </section><!-- #Page Banner -->

    <main id="main">


        <!--==========================
      Contact Section
      ============================-->
      <center>
        <section id="contact" class="wow fadeInUp">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <form method="POST">
                            <div class="form-group">
                                <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
                                <input type="text" name="verification_code" class="form-control" id="name" placeholder="Enter OTP Code" required />
                            </div>
                           
                            <input type="submit" name="verify_email" value="Verify">
                        </form>
                    </div>
                </div>
            </div>
        </section></center>
    </main>
    <?php include('includes/footer.php');?>
    <!-- #footer -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

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