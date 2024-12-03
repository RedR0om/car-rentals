<?php
if (isset($_POST['emailsubscibe'])) {
  $subscriberemail = $_POST['subscriberemail'];
  $sql = "SELECT SubscriberEmail FROM tblsubscribers WHERE SubscriberEmail=:subscriberemail";
  $query = $dbh->prepare($sql);
  $query->bindParam(':subscriberemail', $subscriberemail, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  $cnt = 1;
  if ($query->rowCount() > 0) {
    echo "<script>alert('Already Subscribed.');</script>";
  } else {
    $sql = "INSERT INTO  tblsubscribers(SubscriberEmail) VALUES(:subscriberemail)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':subscriberemail', $subscriberemail, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
      echo "<script>alert('Subscribed successfully.');</script>";
    } else {
      echo "<script>alert('Something went wrong. Please try again');</script>";
    }
  }
}
?>
<section class="footer-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <div class="fs-about">
          <div class="fa-logo">
            <a href="#"><img src="logo.png" alt="" width="140px"></a>
          </div>
          <p>TRIPLE MIKE TRANSPORT SERVICES is owned by Mark Monis Matabang, which operates and licenses the brand
            throughout the Philippines.</p>
          <div class="fa-social">
            <a href="https://www.facebook.com/@triplemikecarrentalservices"><i class="fa fa-facebook"></i></a>
            <a href="https://mail.google.com/mail/u/0/#search/markmatabang930%40gmail.com?compose=new"><i
                class="fa  fa-envelope-o"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-6">
        <div class="fs-widget">
          <h4 style="margin-left: -10px;">Quick Links</h4>
          <ul style="display: inline-block;">
            <li style="font-family:'Tahoma',sans-serif"><a href="about.php">About Us</a></li>
            <li style="font-family:'Tahoma',sans-serif"><a href="faq.php">FAQs</a></li>
            <li style="font-family:'Tahoma',sans-serif"><a href="tc.php">Terms and Conditions</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-6">
        <div class="fs-widget">
          <h4>Contact Us</h4>
          <ul style="display: inline-block;">
            <p style="color: white;"> <i class="fa fa-map" style="color: #ff0000; margin-left: -30px;"></i>
              Blk 4 Lot 37 Sweden St. Carmona Estate Ph 11 Lantic 4116 Carmona, Cavite</p>
            <p style="color: white;"> <i class="fa fa-phone" style="color: #ff0000; margin-left: -30px;"></i>
              0947-382-1708<br>
              0917-448-8750</p>
            <p style="color: white;"> <i class="fa fa-envelope" style="color: #ff0000;margin-left: -30px;"></i>
              markmatabang930@gmail.com</p>
            <p style="color: white;"> <i class="fa fa-facebook" style="color: #ff0000; margin-left: -30px;"></i>
              Triple Mike Transport And Car Rental Services</p>
            <div id="google_element"></div>
          </ul>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="fs-widget">
          <h4 style="margin-left: 100px;">Subscribe Newsletter</h4>
          <div class="fw-recent">
            <form method="post">
              <div class="form-group">
                <input type="email" name="subscriberemail" class="form-control newsletter-input"
                  style="font-family:'Tahoma',sans-serif" required placeholder="Enter Email Address" />
              </div>
              <button type="submit" name="emailsubscibe" class="btn btn-block"
                style="font-family:'Tahoma',sans-serif">Subscribe <span class="angle_arrow"><i class="fa fa-angle-right"
                    aria-hidden="true"></i></span></button>
            </form>
            <p class="subscribed-text" style="font-family:'Tahoma',sans-serif">*We send great deals and latest auto news
              to our subscribed users very week.</p>

          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="copyright-text">
          <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;
            <script>document.write(new Date().getFullYear());</script> All rights reserved
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Footer Section End -->


<style>
  /*---------------------
  Get Touch Section
-----------------------*/

  /*---------------------
  Footer
-----------------------*/

  .footer-section {
    background: #000000;
    padding-top: 50px;

  }

  .fs-about {
    margin-bottom: 30px;

  }

  .fs-about .fa-logo {
    margin-bottom: 30px;

  }

  .fs-about .fa-logo a {
    display: inline-block;
    width: 100px;
  }

  .fs-about p {
    line-height: 26px;
    color: #c4c4c4;
    font-size: 15px;
  }

  .fs-about .fa-social a {
    font-size: 14px;
    color: #c4c4c4;
    margin-right: 10px;
  }

  .fs-about .fa-social a:last-child {
    margin-right: 0;
  }

  .fs-widget {
    margin-bottom: 30px;

  }

  .fs-widget h4 {
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 18px;

  }

  .fs-widget ul li {
    list-style: none;
    margin-left: -50px;
  }

  .fs-widget ul li a {
    font-size: 14px;
    color: #c4c4c4;
    line-height: 30px;
  }

  .fs-widget .fw-recent {
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #1a1a1a;
    margin-left: 12px;
  }

  .fs-widget .fw-recent:last-child {
    padding-bottom: 0;
    border: none;
    margin-left: 100px;
  }

  .fs-widget .fw-recent h6 {
    margin-bottom: 6px;
  }

  .fs-widget .fw-recent h6 a {
    color: #c4c4c4;
    letter-spacing: 0.5px;
  }

  .fs-widget .fw-recent ul li {
    font-size: 12px;
    color: #4d4d4d;
    display: inline-block;
    margin-right: 25px;
    position: relative;
  }

  .fs-widget .fw-recent ul li:last-child:after {
    display: none;
  }

  .fs-widget .fw-recent ul li:after {
    position: absolute;
    right: -16px;
    top: 0;
    content: "|";
  }

  .copyright-text {
    font-size: 14px;
    color: #c4c4c4;
    letter-spacing: 0.5px;
    border-top: 1px solid #1a1a1a;
    padding: 25px 0;
    margin-top: 15px;
  }

  .copyright-text a,
  .copyright-text i {
    color: #f36100;
  }
</style>