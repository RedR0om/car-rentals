<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
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

        $msg = "Booking Successfully Confirmed";
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
                                                <th>Message</th>
                                                <th>Valid ID</th>
                                                <th>GCash Receipt</th>
                                                <th>Status</th>
                                                <th>Posting date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $sql = "SELECT tblusers.FullName, tblbrands.BrandName, tblvehicles.plate, tblvehicles.VehiclesTitle, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.VehicleId as vid, tblbooking.Status, tblbooking.PostingDate, tblbooking.id, tblbooking.image, tblbooking.gcash_receipt 
                                        FROM tblbooking 
                                        JOIN tblvehicles ON tblvehicles.id=tblbooking.VehicleId 
                                        JOIN tblusers ON tblusers.EmailId=tblbooking.userEmail 
                                        JOIN tblbrands ON tblvehicles.VehiclesBrand=tblbrands.id
                                        WHERE tblbooking.Status != 2 AND tblbooking.Status != 6";  // Exclude Rejected and Car Returned statuses
                                    
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
                                                    <td><?php echo htmlentities($result->message); ?></td>
                                                    <td>
                                                        <?php if (!empty($result->image)) { ?>
                                                            <img src="..//<?php echo htmlentities($result->image); ?>" width="100"
                                                                height="50" alt="Valid ID" data-toggle="modal" data-target="#imageModal"
                                                                onclick="showImage('../<?php echo htmlentities($result->image); ?>')">
                                                        <?php } else { ?>
                                                            <span>No Valid ID</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($result->gcash_receipt)) { ?>
                                                            <img src="../<?php echo htmlentities($result->gcash_receipt); ?>"
                                                                width="100" height="50" alt="GCash Receipt" data-toggle="modal"
                                                                data-target="#imageModal"
                                                                onclick="showImage('../<?php echo htmlentities($result->gcash_receipt); ?>')">
                                                        <?php } else { ?>
                                                            <span>No GCash Receipt</span>
                                                        <?php } ?>
                                                    </td>

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
                                                <th>Message</th>
                                                <th>Valid ID</th>
                                                <th>GCash Receipt</th>
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

                            <!-- Image Modal -->
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


        </script>

    </body>

    </html>
<?php } ?>