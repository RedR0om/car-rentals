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
      <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #fa2837;">GALLERY</span></h2>
      <div>
      </div>
    </div>
  </section><!-- #Page Banner -->
  <br>
  <main id="main">
    <div class="row wow fadeInUp">
      <div class="large-12 columns text-center">
        <h2 class="section-title" style="font-family:'Raleway', sans-serif; color: #333;">Our Car Collection</h2>
        <p class="section-subtitle" style="font-family:'Open Sans', sans-serif; color: #777;">Check out the premium cars
          we
          have to offer for your next ride.</p>
        <div class="gallery-container">
          <?php
          require 'dbc.php';
          $stmt = $dbc->query("SELECT * FROM tbl_photos ORDER by img_id ASC");
          foreach ($stmt as $img) {
            ?>
            <div class="gallery-item">
              <a href="../admin/<?= $img['img_path']; ?>" class="image-popup">
                <img class="gallery-img" data-caption="<?= $img['img_title']; ?>" src="../admin/<?= $img['img_path']; ?>"
                  alt="<?= $img['img_title']; ?>">
              </a>
              <div class="img-title"><?= $img['img_title']; ?></div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>

    <!-- Lightbox Plugin for Image Popup -->
    <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
    <script src="lib/magnific-popup/jquery.magnific-popup.min.js"></script>

    <script>
      $(document).ready(function () {
        $('.image-popup').magnificPopup({
          type: 'image',
          gallery: {
            enabled: true
          }
        });
      });
    </script>

    <style>
      /* General Styling for the Section */
      .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 30px;
      }

      .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
      }

      .gallery-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease;
      }

      .img-title {
        position: absolute;
        bottom: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        text-align: center;
        padding: 5px;
        font-size: 16px;
        font-family: 'Open Sans', sans-serif;
        transition: all 0.3s ease;
      }

      .gallery-item:hover .img-title {
        background: rgba(0, 0, 0, 0.8);
      }
    </style>
  </main>
  <br>
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