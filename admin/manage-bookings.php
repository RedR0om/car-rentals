<?php
session_start();
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    $msg = '';
    $error = '';

    if (isset($_REQUEST['eid'])) {
        $eid = intval($_GET['eid']);
        $status = "2";
        $sql = "UPDATE tblbooking SET Status=:status WHERE  id=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();

        $msg = "Booking Successfully Cancelled";
    }

    if (isset($_REQUEST['aeid'])) {
        $aeid = intval($_GET['aeid']);
        $status = 1;

        $sql = "UPDATE tblbooking SET Status=:status WHERE  id=:aeid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
        $query->execute();

        if ($query) {
            $getBookingData = "SELECT tblusers.FullName, tblusers.EmailId, tblbrands.BrandName, tblvehicles.plate, tblvehicles.VehiclesTitle, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.VehicleId as vid, tblbooking.Status, tblbooking.PostingDate, tblbooking.id, tblbooking.image, tblbooking.gcash_receipt 
                        FROM tblbooking 
                        JOIN tblvehicles ON tblvehicles.id=tblbooking.VehicleId 
                        JOIN tblusers ON tblusers.EmailId=tblbooking.userEmail 
                        JOIN tblbrands ON tblvehicles.VehiclesBrand=tblbrands.id
                        WHERE tblbooking.id=:aeid";
            $query = $dbh->prepare($getBookingData);
            $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
            $query->execute();

            $results = $query->fetchAll(PDO::FETCH_OBJ);

            $result = $results[0];
            
            if (isset($result->EmailId)) {
                $email = $result->EmailId;
                $fullname = $result->FullName; 
            } else {
                $email = null;
                $fullname = "Client"; 
            }

            // PHPMailer setup
            $mail = new PHPMailer(true);

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'temporental2020@gmail.com';
            $mail->Password = 'poxmfhhclnpaqvip';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('temporental2020@gmail.com', 'temporental2020@gmail.com');

            if (!empty($email)) {
                $mail->addAddress($email, $fullname);
            } else {
                echo "Error: Email address is not defined.";
            }

            $mail->isHTML(true);
            $mail->Subject = 'T.M.T.C.R.S Support';

            // Constructing the email body
            $mail->Body = "
                <p>Dear " . $fullname . ",</p>

                <p>We are pleased to inform you that your booking has been successfully confirmed. Below are the details of your booking:</p>

                <table style='border-collapse: collapse; width: 100%;'>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Client Name:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>" . $fullname . "</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Email:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>" . $email . "</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Vehicle:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>" . $result->VehiclesTitle . " (" . $result->BrandName . ")</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Plate Number:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>" . $result->plate . "</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>From Date:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>" . $result->FromDate . "</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>To Date:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>" . $result->ToDate . "</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Status:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>Confirmed</td>
                    </tr>
                </table>

                <p>Thank you for choosing Triple Mike Transport Services. If you have any questions regarding your booking, please don't hesitate to contact us.</p>

                <p>Best regards,</p>
                <p><strong>Triple Mike Transport Services</strong><br>
                <a href='mailto:temporental2020@gmail.com'>temporental2020@gmail.com</a><br>
                Customer Support</p>
            ";

            // Send email
            if ($mail->send()) {
                $msg = "Booking Successfully Confirmed and Email Sent";
            } else {
                $error = "Email sending failed!";
            }
        }
    }

    // Check for On Going action
    if (isset($_GET['on_going'])) {
        $id = intval($_GET['on_going']);
        $sql = "UPDATE tblbooking SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $status = 3; // On Going status
        $query->execute();
        $msg = "Booking status changed to On Going successfully";
    }

    // Check for Done action
    if (isset($_GET['done'])) {
        $id = intval($_GET['done']);
        $sql = "UPDATE tblbooking SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $status = 4; // Done status
        $query->execute();
        $msg = "Booking status changed to Done successfully";
    }

    // Check for Done action
    if (isset($_GET['reject'])) {
        $id = intval($_GET['reject']);
        $reason = isset($_GET['reason']) ? trim($_GET['reason']) : '';
        $sql = "UPDATE tblbooking SET Status=:status, RejectMessage=:reason WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':reason', $reason, PDO::PARAM_STR); // bind the reason parameter
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $status = 5; // Reject status
        $query->execute();
        $msg = "Booking Reject successfully";
    }
    // Check for Return action
    if (isset($_GET['return'])) {
        $id = intval($_GET['return']);
        $sql = "UPDATE tblbooking SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $status = 6; // Status for returned car
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Car returned successfully";
    }


    ?>

    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="theme-color" content="#3e454c">

        <title>Car Rental Portal |Staff Manage Bookings</title>

        <!-- Font awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Sandstone Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Bootstrap Datatables -->
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <!-- Bootstrap social button library -->
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <!-- Bootstrap select -->
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <!-- Bootstrap file input -->
        <link rel="stylesheet" href="css/fileinput.min.css">
        <!-- Awesome Bootstrap checkbox -->
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <!-- Admin Stye -->
        <link rel="stylesheet" href="css/style.css">
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
        </style>

    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <div class="ts-main-content">
            <?php include('includes/leftbar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">

                            <h2 class="page-title">Manage Bookings</h2>

                            <!-- Zero Configuration Table -->
                            <div class="panel panel-default">
                                <div class="panel-heading">Bookings Info</div>
                                <div class="panel-body">
                                    <?php if ($error) { ?>
                                        <div class="errorWrap">
                                            <strong>ERROR</strong>:<?php echo htmlentities($error); ?>
                                        </div><?php } else if ($msg) { ?>
                                            <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
                                    <?php } ?>
                                    <form method="GET" align="center">
                                        <label for="from_date">From Date:</label>
                                        <input type="date" name="from_date" id="from_date">
                                        <label for="to_date">To Date:</label>
                                        <input type="date" name="to_date" id="to_date">
                                        <button type="submit" name="sort_by_date" class="btn btn-primary">Sort by Date
                                            Range</button>
                                        <button type="submit" name="sort_by_date1" class="btn btn-primary">Sort by Booked
                                            Date</button>
                                        <button type="reset-btn" class="btn btn-danger">Reset</button>
                                    </form>

                                    <table id="zctb" class="display table table-striped table-bordered table-hover"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Vehicle</th>
                                                <th>Plate No.</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                                <!-- <th>Itinerary</th> -->
                                                <th>Valid ID</th>
                                                <th>GCash Receipt</th>
                                                <th>Payment Option</th>
                                                <th>Status</th>
                                                <th>Posting date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $sql = "SELECT tblusers.FullName, tblusers.EmailId, tblbrands.BrandName, tblvehicles.plate, tblvehicles.VehiclesTitle, tblbooking.FromDate,
                                        tblbooking.ToDate, tblbooking.message, tblbooking.VehicleId as vid, tblbooking.Status, tblbooking.PostingDate, tblbooking.id, tblbooking.image,
                                        tblbooking.gcash_receipt, tblbooking.payment_option, tblbooking.account_name, tblbooking.account_number, tblbooking.reference_number,
                                        (SELECT CONCAT(tblplace.PlaceName, tblplace.City) FROM tblplace WHERE tblplace.PlaceID = tblbooking.pickup_location) as pickup_location,
                                        (SELECT CONCAT(tblplace.PlaceName, tblplace.City) FROM tblplace WHERE tblplace.PlaceID = tblbooking.dropoff_location) as dropoff_location,
                                        tblbooking.is_metro_manila
                                        FROM tblbooking 
                                        JOIN tblvehicles ON tblvehicles.id=tblbooking.VehicleId 
                                        JOIN tblusers ON tblusers.EmailId=tblbooking.userEmail 
                                        JOIN tblbrands ON tblvehicles.VehiclesBrand=tblbrands.id
                                        WHERE tblbooking.Status != 2 AND tblbooking.Status != 6
                                        ORDER BY
                                        tblbooking.Status ASC,
                                        tblbooking.PostingDate DESC";  // Exclude Rejected and Car Returned statuses
                                    
                                        if (isset($_GET['sort_by_date'])) {
                                            $from_date = $_GET['from_date'];
                                            $to_date = $_GET['to_date'];
                                            if (!empty($from_date) && !empty($to_date)) {
                                                $sql .= "WHERE tblbooking.FromDate >= '$from_date' AND tblbooking.ToDate <= '$to_date' ";
                                            } else {
                                                if (!empty($from_date)) {
                                                    $sql .= "WHERE tblbooking.FromDate >= '$from_date' AND tblbooking.ToDate <= '$from_date'";
                                                }
                                            }

                                        }

                                        if (isset($_GET['sort_by_date'])) {
                                            $from_date = $_GET['from_date'];
                                            $to_date = $_GET['to_date'];
                                            if (!empty($from_date) && !empty($to_date)) {
                                                $sql .= " AND tblbooking.FromDate >= '$from_date' AND tblbooking.ToDate <= '$to_date' ";
                                            } else {
                                                if (!empty($from_date)) {
                                                    $sql .= " AND tblbooking.FromDate >= '$from_date' AND tblbooking.ToDate <= '$from_date'";
                                                }
                                            }
                                        }

                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($result->FullName); ?></td>
                                                    <td><a href="edit-vehicle.php?id=<?php echo htmlentities($result->vid); ?>"><?php echo htmlentities($result->BrandName); ?>
                                                            , <?php echo htmlentities($result->VehiclesTitle); ?></td>
                                                    <td><?php echo htmlentities($result->plate); ?></td>
                                                    <td><?php echo htmlentities($result->FromDate); ?></td>
                                                    <td><?php echo htmlentities($result->ToDate); ?></td>
                                                    <!-- <td><?php echo htmlentities($result->message); ?></td> -->
                                                    <td>
                                                        <?php if (!empty($result->image)) { ?>
                                                            <img src="..//<?php echo "validid/67623a640641e.jpg"; ?>" width="100"
                                                                height="50" alt="Valid ID" data-toggle="modal" data-target="#imageModal"
                                                                onclick="showImage('../<?php echo htmlentities($result->image); ?>')">
                                                        <?php } else { ?>
                                                            <span>No Valid ID</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($result->gcash_receipt)) { ?>
                                                            <img src="../<?php echo "gcash_receipts/676241959ba10.jpg"; ?>"
                                                                width="100" height="50" alt="GCash Receipt" data-toggle="modal"
                                                                data-target="#imageModal"
                                                                onclick="showImage('../<?php echo htmlentities($result->gcash_receipt); ?>')">
                                                        <?php } else { ?>
                                                            <span>No GCash Receipt</span>
                                                        <?php } ?>
                                                    </td>

                                                    <td><?php echo $result->payment_option ? htmlentities($result->payment_option) : 'No Payment Option'; ?></td>

                                                    <td>
                                                        <?php
                                                        if ($result->Status == 0) {
                                                            echo htmlentities('Not Confirmed yet');
                                                        } else if ($result->Status == 1) {
                                                            echo htmlentities('Confirmed');
                                                        } else if ($result->Status == 3) {
                                                            echo htmlentities('On-Going');
                                                        } else if ($result->Status == 4) {
                                                            echo htmlentities('Done');
                                                        } else if ($result->Status == 5) {
                                                            echo htmlentities('Rejected');
                                                        } else if ($result->Status == 6) { // Car Returned
                                                            echo "<span style='color:green'>Car Returned</span>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo htmlentities($result->PostingDate); ?></td>
                                                    <td>
                                                        <a href="#"
                                                        data-fullname="<?php echo htmlspecialchars($result->FullName); ?>"
                                                        data-email="<?php echo htmlspecialchars($result->EmailId); ?>"
                                                        data-brandname="<?php echo htmlspecialchars($result->BrandName); ?>"
                                                        data-plate="<?php echo htmlspecialchars($result->plate); ?>"
                                                        data-vehicletitle="<?php echo htmlspecialchars($result->VehiclesTitle); ?>"
                                                        data-fromdate="<?php echo htmlspecialchars($result->FromDate); ?>"
                                                        data-todate="<?php echo htmlspecialchars($result->ToDate); ?>"
                                                        data-message="<?php echo htmlspecialchars($result->message); ?>"
                                                        data-idvehicle="<?php echo htmlspecialchars($result->vid); ?>"
                                                        data-status="<?php echo htmlspecialchars($result->Status); ?>"
                                                        data-postingdate="<?php echo htmlspecialchars($result->PostingDate); ?>"
                                                        data-image="<?php echo !empty($result->image) ? "validid/67623a640641e.jpg" : ''; ?>"
                                                        data-receipt="<?php echo !empty($result->gcash_receupt) ? "gcash_receipts/676241959ba10.jpg" : ''; ?>"
                                                        data-paymentoption="<?php echo $result->payment_option; ?>"
                                                        data-accountname="<?php echo $result->account_name; ?>"
                                                        data-accountnumber="<?php echo $result->account_number; ?>"
                                                        data-referencenumber="<?php echo $result->reference_number; ?>"
                                                        data-pickuplocation="<?php echo $result->pickup_location; ?>"
                                                        data-dropofflocation="<?php echo $result->dropoff_location; ?>"
                                                        data-ismetromanila="<?php echo $result->is_metro_manila; ?>"
                                                        data-id="<?php echo htmlspecialchars($result->id); ?>"
                                                        data-toggle="modal" 
                                                        data-target="#viewDetailsModal">
                                                        View Details
                                                    </a> /
                                                        <?php if ($result->Status == 0) { ?>
                                                            <a href="manage-bookings.php?aeid=<?php echo htmlentities($result->id); ?>"
                                                                onclick="return confirm('Do you really want to Confirm this booking')">Confirm</a>
                                                            /
                                                            <a href="#" onclick="var reason = prompt('Please enter the reason for rejecting this booking:');
                    if (reason) {
                        window.location.href = 'manage-bookings.php?reject=<?php echo htmlentities($result->id); ?>&reason=' + encodeURIComponent(reason);
                    }">Reject</a>
                                                        <?php } else if ($result->Status == 1) { ?>
                                                                <a
                                                                    href="manage-bookings.php?on_going=<?php echo htmlentities($result->id); ?>">On-Going</a>
                                                                /
                                                                <a href="manage-bookings.php?eid=<?php echo htmlentities($result->id); ?>"
                                                                    onclick="return confirm('Do you really want to Cancel this Booking')">Cancel</a>
                                                        <?php } else if ($result->Status == 3) { ?>
                                                                    <a
                                                                        href="manage-bookings.php?done=<?php echo htmlentities($result->id); ?>">Done</a>
                                                        <?php } else if ($result->Status == 4) { ?>
                                                                        <span style="color:green">Done</span> /
                                                                        <a href="manage-bookings.php?return=<?php echo htmlentities($result->id); ?>"
                                                                            onclick="return confirm('Do you really want to return this car?')">Return
                                                                            Car</a>
                                                        <?php } else if ($result->Status == 6) { // Car has been returned ?>
                                                                            <span style="color:Green">Car Returned</span>
                                                        <?php } else { ?>
                                                                            <span style="color:red">Rejected</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $cnt = $cnt + 1;
                                            }
                                        } ?>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Vehicle</th>
                                                <th>Plate No.</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                                <!-- <th>Itinerary</th> -->
                                                <th>Valid ID</th>
                                                <th>GCash Receipt</th>
                                                <th>Payment Option</th>
                                                <th>Status</th>
                                                <th>Posting date</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                    <a href="dashboard.php"><input type="button" class="w3-button w3-blue" value="Go Back">
                                    </a>
                                </div>
                            </div>

                            <!-- View Details Modal -->
                            <div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="viewDetailsModalLabel">Booking Details</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                <strong>Full Name:</strong>
                                                <p style="padding-left: 15px;" id="modal-fullname"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Email:</strong>
                                                <p style="padding-left: 15px;" id="modal-email"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Booking Status:</strong>
                                                <p style="padding-left: 15px;" id="modal-status"></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                <strong>Vehicle Brand:</strong>
                                                <p style="padding-left: 15px;" id="modal-brandname"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Vehicle Name:</strong>
                                                <p style="padding-left: 15px;" id="modal-vehicletitle"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Plate No:</strong>
                                                <p style="padding-left: 15px;" id="modal-plate"></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                <strong>Booking Start Date:</strong>
                                                <p style="padding-left: 15px;" id="modal-fromdate"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Booking End Date:</strong>
                                                <p style="padding-left: 15px;" id="modal-todate"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Booking Creation Date:</strong>
                                                <p style="padding-left: 15px;" id="modal-postingdate"></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                <strong>Pick-up Location:</strong>
                                                <p style="padding-left: 15px;" id="modal-pickuplocation"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Drop-off Location:</strong>
                                                <p style="padding-left: 15px;" id="modal-dropofflocation"></p>
                                                </div>
                                                <div class="col-md-4">
                                                <strong>Is Within Metro Manila?</strong>
                                                <p style="padding-left: 15px;" id="modal-ismetromanila"></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                <strong>Itinerary:</strong><br><br>
                                                <p style="padding-left: 15px;" id="modal-message"></p>
                                                </div>
                                            </div>

                                            <br><br>

                                            <div class="row payment-details">
                                                <div class="col-md-3">
                                                <strong>Payment Option:</strong>
                                                <p style="padding-left: 15px;" id="modal-paymentoption"></p>
                                                </div>
                                                <div class="col-md-3">
                                                <strong>Account Name:</strong>
                                                <p style="padding-left: 15px;" id="modal-accountname"></p>
                                                </div>
                                                <div class="col-md-3">
                                                <strong>Account Number:</strong>
                                                <p style="padding-left: 15px;" id="modal-accountnumber"></p>
                                                </div>
                                                <div class="col-md-3">
                                                <strong>Reference Number:</strong>
                                                <p style="padding-left: 15px;" id="modal-referencenumber"></p>
                                                </div>
                                            </div>

                                            <br><br>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Valid ID:</strong><br>
                                                    <img id="modal-image" 
                                                        src="" 
                                                        width="300" 
                                                        height="250" 
                                                        alt="GCash Receipt"
                                                        style="border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>GCash Receipt:</strong><br>
                                                    <img id="modal-receipt" 
                                                        src="" 
                                                        width="300" 
                                                        height="250" 
                                                        alt="GCash Receipt"
                                                        style="border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- Image Modal -->
                            <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                                aria-labelledby="imageModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img id="modalImage" src="" alt="Image" style="width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script>
            function showImage(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
            }
        </script>

        <!-- Loading Scripts -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-select.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/fileinput.js"></script>
        <script src="js/chartData.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(document).ready(function () {

                var $tableHeaders = $('table th');


                var $tableRows = $('table tbody tr');


                $('#reset-btn').click(function () {

                    $tableHeaders.removeClass('asc desc');


                    $('table tbody').html($tableRows);
                });
            });

            $(document).ready(function() {
                // Handle view details modal
                $('#viewDetailsModal').on('show.bs.modal', function(event) {
                    var link = $(event.relatedTarget);

                    // Retrieve data-* attributes
                    var fullname = link.data('fullname');
                    var email = link.data('email');
                    var brandname = link.data('brandname');
                    var plate = link.data('plate');
                    var vehicletitle = link.data('vehicletitle');
                    var fromdate = link.data('fromdate');
                    var todate = link.data('todate');
                    var message = link.data('message');
                    var idvehicle = link.data('idvehicle');
                    var status = link.data('status');
                    var postingdate = link.data('postingdate');
                    var image = link.data('image');
                    var receipt = link.data('receipt');
                    var paymentoption = link.data('paymentoption');
                    var accountname = link.data('accountname');
                    var accountnumber = link.data('accountnumber');
                    var referencenumber = link.data('referencenumber');
                    var pickuplocation = link.data('pickuplocation');
                    var dropofflocation = link.data('dropofflocation');
                    var ismetromanila = link.data('ismetromanila') === 1 ? 'Yes' : 'No';
                    var id = link.data('id');

                    console.log('gcash: ', receipt);

                    var statusText = "";

                    switch (status) {
                        case 0:
                            statusText = "Not Confirmed yet";
                            break;
                        case 1:
                            statusText = "Confirmed";
                            break;
                        case 3:
                            statusText = "On-Going";
                            break;
                        case 4:
                            statusText = "Done";
                            break;
                        case 5:
                            statusText = "Rejected";
                            break;
                        case 6:
                            statusText = "<span style='color:green'>Car Returned</span>";
                            break;
                        default:
                            statusText = "Unknown Status";
                    }

                    // Toggle payment details
                    if (paymentoption === 'cash' || !paymentoption || paymentoption === '' || paymentoption === null) {
                        $('.payment-details').css('display', 'none'); // Hide
                    } else {
                        $('.payment-details').css('display', 'block'); // Show
                    }

                    $('#modal-fullname').text(fullname);
                    $('#modal-email').text(email);
                    $('#modal-brandname').text(brandname);
                    $('#modal-plate').text(plate);
                    $('#modal-vehicletitle').text(vehicletitle);
                    $('#modal-fromdate').text(fromdate);
                    $('#modal-todate').text(todate);
                    $('#modal-message').text(message);
                    $('#modal-idvehicle').text(idvehicle);
                    $('#modal-status').text(statusText);
                    $('#modal-postingdate').text(postingdate);
                    $('#modal-receipt').attr('src', '../' + receipt);
                    $('#modal-image').attr('src', '..//' + image);
                    $('#modal-paymentoption').text(paymentoption);
                    $('#modal-accountname').text(accountname);
                    $('#modal-accountnumber').text(accountnumber);
                    $('#modal-referencenumber').text(referencenumber);
                    $('#modal-pickuplocation').text(pickuplocation);
                    $('#modal-dropofflocation').text(dropofflocation);
                    $('#modal-ismetromanila').text(ismetromanila);
                    $('#modal-id').text(id);
                });
            });

        </script>

    </body>

    </html>
<?php } ?>