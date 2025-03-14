<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    // Connect with database
    $conn = mysqli_connect("ballast.proxy.rlwy.net:35637", "root", "BobDdBAPBobrKyzYicQYaJhDpujZqoKa", "railway");

    // Mark email as verified
    $stmt = $conn->prepare("UPDATE tblusers SET email_verified_at = NOW() WHERE EmailId = ? AND verification_code = ?");
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();

    if ($stmt->affected_rows == 0) {
        echo "<div class='alert alert-danger text-center'>Verification code is incorrect. Please try again.</div>";
    } else {
        echo "<script>alert('Registration successful. You can now log in.');</script>";
        echo "<script>window.location.href='index.php';</script>";
    }
    $stmt->close();
    $conn->close();
    exit();
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
    <?php include('includes/header.php'); ?>
    <section id="innerBanner" style="background: black;">
        <div class="inner-content">
            <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #fa2837;">OTP VERIFICATION</span></h2>
            <div>
            </div>
        </div>
    </section><!-- #Page Banner -->

    <main id="main">
        <section id="contact" class="wow fadeInUp py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="card shadow-lg">
                            <div class="card-header text-white text-center">
                                <h4>Verify Your Email</h4>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST">
                                    <input type="hidden" name="email"
                                        value="<?php echo htmlspecialchars($_GET['email']); ?>" required>

                                    <div class="form-group">
                                        <label for="verification_code">Enter OTP Code</label>
                                        <input type="text" name="verification_code" class="form-control"
                                            placeholder="Enter OTP Code" required />
                                    </div>

                                    <button type="submit" name="verify_email"
                                        class="btn btn-primary btn-block">Verify</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>

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