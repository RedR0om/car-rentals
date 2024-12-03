<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
{ 
  header('location:index.php');
}
else{
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
    .table {
        width: 100%;
        margin-bottom: 20px;
    }

    .table-striped tbody>tr:nth-child(odd)>td,
    .table-striped tbody>tr:nth-child(odd)>th {
        background-color: #f9f9f9;
    }

    @media print {
        #print {
            display: none;
        }
    }

    @media print {
        #PrintButton {
            display: none;
        }
    }

    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0;
        /* this affects the margin in the printer settings */
    }
    </style>
</head>

<body id="body">
    <section id="innerBanner" style="background: black;">
        <div class="inner-content">
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

                    <div class="dealer_info">

                        <?php 
                }
              }?></p>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-8 col-sm-8" style=" margin-left: 10%;">
                        <div class="profile_wrap" style="max-width: 1000000px; width:800px; margin-top: -300px;">
                            <div class="my_vehicles_list">
                                <ul class="vehicle_listing">
                                    <?php 
                    $useremail=$_SESSION['login'];
                    $sql = "SELECT tblvehicles.ModelYear, tblvehicles.TypeCar, tblvehicles.plate, tblvehicles.Vimage1 AS Vimage1, tblvehicles.VehiclesTitle, tblvehicles.id AS vid, tblbrands.BrandName, tblbooking.id, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.payment, tblbooking.RejectMessage, tblbooking.Status, tblvehicles.PricePerDay, DATEDIFF(tblbooking.ToDate, tblbooking.FromDate) AS totaldays, tblbooking.BookingNumber, tblcartype.cartype_name FROM tblbooking JOIN tblvehicles ON tblbooking.VehicleId = tblvehicles.id JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand JOIN tblcartype ON tblcartype.cartype_id = tblvehicles.TypeCar where tblbooking.userEmail=:useremail";
                    $query = $dbh -> prepare($sql);
                    $query-> bindParam(':useremail', $useremail, PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                      foreach($results as $result)
                      {  
                        ?>

                                    <li>
                                        <h4 style="color:red">Booking No.
                                            &nbsp;<?php echo htmlentities($result->BookingNumber);?></h4>
                                        <div class="vehicle_img"> <a
                                                href="car_details.php?vhid=<?php echo htmlentities($result->vid);?>"><img
                                                    src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>"
                                                    alt="image"></a> </div>
                                        <div class="vehicle_title">

                                            <h6><a href="car_details.php?vhid=<?php echo htmlentities($result->vid);?>">
                                                    <?php echo htmlentities($result->BrandName);?> ,
                                                    <?php echo htmlentities($result->VehiclesTitle);?></a></h6>
                                            <p><b>From </b> <?php echo htmlentities($result->FromDate);?> <b>To </b>
                                                <?php echo htmlentities($result->ToDate);?></p>
                                            <div style="float: left">
                                                <p><b>Message:</b> <?php echo htmlentities($result->message);?> </p>
                                            </div>
                                        </div>


                                    </li>

                                    <h5 style="color:blue">Invoice</h5>
                                    <table>
                                        <tr>
                                            <th>Car Name</th>
                                            <th>Plate Number</th>
                                            <th>Car Type</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Total Days</th>
                                            <th>Rent / Day</th>
                                        </tr>
                                        <tr>
                                                <td><?php echo htmlentities($result->VehiclesTitle);?>,
                                                    <?php echo htmlentities($result->BrandName);?><?php echo " ";?><?php echo htmlentities($result->ModelYear);?></td>
                                                    <td><?php echo htmlentities($result->plate);?></td>
                                                    <td><?php echo htmlentities($result->cartype_name);?></td>
                                                    <td><?php echo htmlentities($result->FromDate);?></td>
                                                <td> <?php echo htmlentities($result->ToDate);?></td>
                                                <td><?php echo htmlentities($tds=$result->totaldays);?></td>
                                                <td>₱
                                                    <?php echo number_format(htmlentities($ppd=$result->PricePerDay), 2 );?>
                                                </td>
                                            </tr>
                                        <tr>
                                            <th colspan="6" style="text-align:center;"> Grand Total</th>
                                            <th> ₱ <?php echo htmlentities($tds*$ppd);?></th>
                                        </tr>
                                        <tr>
                                                <th colspan="6" style="text-align:center;"> Payment Option</th>
                                                <?php if($result->payment==1) { ?>
                                                <th>Full Payment</th>
                                                <?php }else{?><th>Partial Payment</th>
                                                    <?php }?>
                                            <tr>
                                            <tr>
                                                <th colspan="6" style="text-align:center;">Amount to be paid</th>
                                                <th><?php if($result->payment==1)
                          { ?>
                                                    <a>₱ <?php echo  number_format(htmlentities($tds*$ppd), 2);?></a>


                                                    <?php 
                         } elseif($result->payment==2) { ?>
                                                    <a>₱
                                                        <?php echo  number_format(htmlentities(($tds*$ppd)/2), 2);?></a>


                                                    <?php 
                      } ?>
                                                </th>
                                            </tr>
                                        <h2><span style="color: #009bc2">Official Receipt</span><br><span
                                                style="color: red; font-size: 20px;">Note: Please Bring Two Valid
                                                IDs</span></h2>

                                    </table>
                                    <hr />
                                    <br><br><br>
                                    <br><br><br>
                                    <br><br><br>
                                    <br><br><br>
                                    <br><br><br>
                                    <br><br><br>
                                    <br><br><br>
                                    <br><br><br>
                                    <br><br><br>
                                    <?php
                  }
                }  else { ?>
                                    <h5 align="center" style="color:red">No booking yet</h5>
                                    <?php 
                } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <center><button id="PrintButton" onclick="PrintPage()">Print</button></center>
        </section>


    </main>

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    <!--Login-Form -->
    <?php include('includes/userpay.php');?>
    <!--/Login-Form -->

    <!--Register-Form -->
    <?php include('includes/registration.php');?>

    <!--/Register-Form -->

    <!--Forgot-password-Form -->
    <?php include('includes/forgotpassword.php');?>
    <!--/Forgot-password-Form -->
    <script type="text/javascript">
    function PrintPage() {
        window.print();
    }
    document.loaded = function() {

    }
    window.addEventListener('DOMContentLoaded', (event) => {
        PrintPage()
        setTimeout(function() {
            window.close()
        }, 750)
    });
    </script>
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