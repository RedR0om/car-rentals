<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{
if(isset($_POST['submit']))
  {
$rating=$_POST['rating'];
$email=$_SESSION['login'];
$id = $_GET['id'];

$sql="UPDATE tblbooking SET rating=:rating where id=:id";
$query = $dbh->prepare($sql);
$query->bindParam(':rating',$rating,PDO::PARAM_STR);
$query->bindParam(':id',$id,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();

$msg="Rated Successfully";

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

    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/css/star-rating.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/js/star-rating.min.js"></script>

    <!-- Favicons -->
    <link href="img/favicon.png" rel="icon">
    <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">

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
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
      }
      .succWrap{
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
      }
    </style>
  </head>

  <body id="body">
    <?php include('includes/header-rate.php');?>
    <section id="innerBanner" style="background: black;"> 
      <div class="inner-content">
        <h2><span style="color: #009bc2">Rate Us</span><br>Your Feedback is our key to Success!</h2>
        <div> 
        </div>
      </div> 
    </section><!-- #Page Banner -->

    <main id="main">
     <?php 
$useremail=$_SESSION['login'];
$sql = "SELECT * from tblusers where EmailId=:useremail";
$query = $dbh -> prepare($sql);
$query -> bindParam(':useremail',$useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{ ?>
<section class="user_profile inner_pages">
  <div class="container">
    <div class="user_profile_info gray-bg padding_4x4_40">
      <div class="upload_user_logo"> <img src="assets/images/dealer-logo.jpg" alt="image">
      </div>

      <div class="dealer_info">
        <h5><?php echo htmlentities($result->FullName);?></h5>
        <p><?php echo htmlentities($result->Address);?><br>
          <?php echo htmlentities($result->City);?>&nbsp;<?php echo htmlentities($result->Country); }}?></p>
      </div>
    </div>
  
    <div class="row">
      <div class="col-md-3 col-sm-3">
        <?php include('includes/sidebar.php');?>
      <div class="col-md-6 col-sm-8">
        <div class="profile_wrap">
          <h5 class="uppercase underline">Give Rate</h5>
            <?php 
            if($error){?>
              <div class="errorWrap">
                <strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
            <?php } 
            else if($msg){?>
                <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
            <?php }?>

          
            <?php
// Make a database connection
$conn = mysqli_connect("localhost", "root", "", "carrental");

$id=$_GET['id'];
// Check if the column is empty
$sql = "SELECT * FROM tblbooking WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (empty($row['rating'])) {
  // Display the form
  ?>
  <form action="#" method="post">
    <div class="container">
      <input id="ratinginput" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1" value="2">
    </div>
    <div class="form-group">
      <button type="submit" name="submit" class="btn">Save  <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button>
    </div>
  </form>
  <?php
} else {
  $rating = $_POST['rating'];
  // 'rating' column is not empty
  echo '<h2>Thanks for rating! '.$rating.'</h2>';
}

?>
        </div>
      </div>
    </div>
  </div>
</section>
          } ?>
          <!--/Profile-setting--> 
          <section id="call-to-action" class="wow fadeInUp">
            <div class="container">
              <div class="row">
                <div class="col-lg-9 text-center text-lg-left">
                  <h3 class="cta-title">Get Our Service</h3>
                  <p class="cta-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores quae porro consequatur aliquam, incidunt fugiat culpa esse aute nulla cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
                <div class="col-lg-3 cta-btn-container text-center">
                  <a class="cta-btn align-middle" href="contact.php">Contact Us</a>
                </div>
              </div>

            </div>
          </section><!-- #call-to-action -->
        </main>
        <?php include('includes/footer.php');?><!-- #footer -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
        <!--Login-Form -->
        <?php include('includes/login.php');?>
        <!--/Login-Form --> 

        <!--Register-Form -->
        <?php include('includes/registration.php');?>

        <!--/Register-Form --> 

        <!--Forgot-password-Form -->
        <?php include('includes/forgotpassword.php');?>
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
        <script>
$("#ratinginput").rating();
</script>

      </body>
      </html>
      <?php 
    } ?>
