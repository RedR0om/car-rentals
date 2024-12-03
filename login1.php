<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
}

if (isset($_SESSION['unique_id'])) {
    header("location: users.php");
}
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Car Rental Portal | My Profile</title>
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
      .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
      }

      .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
      }
        
.form header{
  font-size: 25px;
  font-weight: 600;
  padding-bottom: 10px;
  border-bottom: 1px solid #e6e6e6;
}
.form form{
  margin: 20px 0;
}
.form form .error-text{
  color: #721c24;
  padding: 8px 10px;
  text-align: center;
  border-radius: 5px;
  background: #f8d7da;
  border: 1px solid #f5c6cb;
  margin-bottom: 10px;
  display: none;
}
.form form .name-details{
  display: flex;
}
.form .name-details .field:first-child{
  margin-right: 10px;
}
.form .name-details .field:last-child{
  margin-left: 10px;
}
.form form .field{
  display: flex;
  margin-bottom: 10px;
  flex-direction: column;
  position: relative;
}
.form form .field label{
  margin-bottom: 2px;
}
.form form .input input{
  height: 40px;
  width: 100%;
  font-size: 16px;
  padding: 0 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
}
.form form .field input{
  outline: none;
}
.form form .image input{
  font-size: 17px;
}
.form form .button input{
  height: 45px;
  border: none;
  color: #fff;
  font-size: 17px;
  background: #333;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 13px;
}
.form form .field i{
  position: absolute;
  right: 15px;
  top: 70%;
  color: #ccc;
  cursor: pointer;
  transform: translateY(-50%);
}
.form form .field i.active::before{
  color: #333;
  content: "\f070";
}
.form .link{
  text-align: center;
  margin: 10px 0;
  font-size: 17px;
}
.form .link a{
  color: #333;
}
.form .link a:hover{
  text-decoration: underline;
}

    </style>
  </head>

  <body id="body">
    <?php include('includes/header.php'); ?>
    <section id="innerBanner" style="background: black;">
      <div class="inner-content">
        <h2><span style="color: #ff0000">Chat</span><br>Communication is always the key!</h2>
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
        foreach ($results as $result) {
          ?>
          <section class="user_profile inner_pages">
            <div class="container">
              <div class="user_profile_info gray-bg padding_4x4_40">

                <div class="upload_user_logo"> <img src="<?php echo htmlentities($result->image); ?>" alt="image"
                    style="width: 150px; height: 150px; border-radius:50%;">

                  <div class="form-group" style="padding-top: 10px;">
                    <center>
                      <button style=" border: none;"><a href="#profile" data-toggle="modal" data-dismiss="modal"
                          class="btn-banner">Upload Here</a>
                      </button>
                    </center>
                  </div>
                </div>

                <div class="dealer_info">
                  <h5><?php echo htmlentities($result->FullName); ?></h5>
                  <p><?php echo htmlentities($result->Address); ?><br>
                    <?php echo htmlentities($result->City); ?>&nbsp;<?php echo htmlentities($result->Country); ?></p>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3 col-sm-3">
                  <?php include('includes/sidebar.php'); ?>
                  <div class="col-md-6 col-sm-8">
                    <div class="profile_wrap">
                      <h5 class="uppercase underline">Chat</h5>
                      <?php
                      if ($msg) {
                        ?>
                        <div class="succWrap">
                          <strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
                        </div>
                        <?php
                      } ?>
                      <section class="form login">
                          <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="error-text"></div>
                            <div class="field input">
                              <label>Email Address</label>
                              <input type="text" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="field input">
                              <label>Password</label>
                              <input type="password" name="password" placeholder="Enter your password" required>
                              <i class="fa fa-eye"></i>
                            </div>
                            <div class="field button">
                              <input type="submit" name="submit" value="Continue to Chat">
                            </div>
                          </form>
                          <div class="link">Not yet signed up? <a href="chat-panel.php">Signup now</a></div>
                        </section>
                    </div>
                  </div>
                </div>
              </div>
          </section>
          <?php
        }
      } ?>
      
    </main>
    
    <?php include('includes/footer.php'); ?><!-- #footer -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    <!--Login-Form -->
    <?php include('includes/login.php'); ?>
    <!--/Login-Form -->
    <?php include('includes/profile.php'); ?>

    <!--Register-Form -->
    <?php include('includes/registration.php'); ?>

    <!--/Register-Form -->

    <!--Forgot-password-Form -->
    <?php include('includes/forgotpassword.php'); ?>
    <!--/Forgot-password-Form -->

    <!-- JavaScript  -->
    <script src="javascript/pass-show-hide.js"></script>
    <script src="javascript/login.js"></script>
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