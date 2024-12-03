<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (isset($_POST['send'])) {
  $name = $_POST['fullname'];
  $email = $_POST['email'];
  $contactno = $_POST['contactno'];
  $message = $_POST['message'];
  $sql = "INSERT INTO  tblcontactusquery(name,EmailId,ContactNumber,Message) VALUES(:name,:email,:contactno,:message)";
  $query = $dbh->prepare($sql);
  $query->bindParam(':name', $name, PDO::PARAM_STR);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':contactno', $contactno, PDO::PARAM_STR);
  $query->bindParam(':message', $message, PDO::PARAM_STR);
  $query->execute();
  $lastInsertId = $dbh->lastInsertId();
  if ($lastInsertId) {
    $msg = "Query Sent. We will contact you shortly";
  } else {
    $error = "Something went wrong. Please try again";
  }

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
      <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #fa2837;">CONTACT</span><br>We simplify your
        journey!</h2>
      <div>
      </div>
    </div>
  </section><!-- #Page Banner -->

  <main id="main">


    <!--==========================
      Contact Section
      ============================-->
    <section id="contact" class="wow fadeInUp">
      <div class="container">
        <div class="section-header">
          <p style="width: 100%; font-family:'Tahoma',sans-serif">Please contact us via email or phone at 09199044995.
            We would be delighted to answer your questions and arrange a meeting with you. Tempo | Rental can help you
            stand out from the crowd.</p>
        </div>
        <div class="row contact-info">
          <div class="col-lg-5">
            <div class="contact-address">
              <i class="ion-ios-location-outline"></i>
              <h3>Address</h3>
              <address style="font-family:'Tahoma',sans-serif">Blk 4 Lot 37 Sweden St. Carmona Estate Ph 11 Lantic 4116
                Carmona, Cavite</address>
            </div>
            <div class="contact-phone">
              <i class="ion-ios-telephone-outline"></i>
              <h3>Phone Number</h3>
              <p style="font-family:'Tahoma',sans-serif">0947-382-1708<br>
                0917-448-8750</p>
            </div>
            <div class="contact-email">
              <i class="ion-ios-email-outline"></i>
              <h3>Email</h3>
              <p style="font-family:'Tahoma',sans-serif"><a
                  href="https://mail.google.com/mail/u/0/">markmatabang930@gmail.com</a></p>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="container">
              <div class="form">
                <?php if ($error) { ?>
                  <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
                <?php } else if ($msg) { ?>
                    <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                <!-- Form itself -->
                <div class="contact_form gray-bg">
                  <form method="post" style="font-family:'Tahoma',sans-serif">
                    <div class="form-group">
                      <label class="control-label" style="margin-left:  -85%;">Full Name <span>*</span></label>
                      <input type="text" name="fullname" class="form-control white_bg" id="fullname" required>
                    </div>
                    <div class="form-group">
                      <label class="control-label" style="margin-left:  -80%;">Email Address <span>*</span></label>
                      <input type="email" name="email" class="form-control white_bg" id="emailaddress" required>
                    </div>
                    <div class="form-group">
                      <label class="control-label" style="margin-left:  -80%;">Phone Number <span>*</span></label>
                      <input type="text" name="contactno" class="form-control white_bg" id="phonenumber" required
                        maxlength="11">
                    </div>
                    <div class="form-group">
                      <label class="control-label" style="margin-left:  -85%;">Message <span>*</span></label>
                      <textarea class="form-control white_bg" name="message" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                      <button class="btn" type="submit" name="send" type="submit">Send Message <span
                          class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

    </section><!-- #contact -->

    <?php include('includes/contact.php'); ?>
  </main>
  <?php include('includes/footer.php'); ?><!-- #footer -->

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