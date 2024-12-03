<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Fetch bookings where the status is "Car Returned" or "Rejected"
    $sql = "SELECT tblusers.FullName, tblbrands.BrandName, tblvehicles.plate, tblvehicles.VehiclesTitle, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.VehicleId as vid, tblbooking.Status, tblbooking.PostingDate, tblbooking.id, tblbooking.image, tblbooking.gcash_receipt FROM tblbooking JOIN tblvehicles ON tblvehicles.id=tblbooking.VehicleId JOIN tblusers ON tblusers.EmailId=tblbooking.userEmail JOIN tblbrands ON tblvehicles.VehiclesBrand=tblbrands.id WHERE tblbooking.Status IN (2, 5)";

    // Filter by date if the user has submitted a date range
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

    // Execute query
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
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

        <title>Car Rental Portal |Archived Bookings</title>

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

                            <h2 class="page-title">Archived Bookings</h2>

                            <!-- Filter Form -->
                            <form method="GET" align="center">
                                <label for="from_date">From Date:</label>
                                <input type="date" name="from_date" id="from_date">
                                <label for="to_date">To Date:</label>
                                <input type="date" name="to_date" id="to_date">
                                <button type="submit" name="sort_by_date">Sort by Date Range</button>
                                <button type="reset" value="reset">Reset</button>
                            </form>

                            <div class="panel panel-default">
                                <div class="panel-heading">Archived Bookings Info</div>
                                <div class="panel-body">
                                    <?php if ($msg) { ?>
                                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
                                    <?php } ?>
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
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($result->FullName); ?></td>
                                                        <td><a href="edit-vehicle.php?id=<?php echo htmlentities($result->vid); ?>"><?php echo htmlentities($result->BrandName); ?>,
                                                                <?php echo htmlentities($result->VehiclesTitle); ?></a></td>
                                                        <td><?php echo htmlentities($result->plate); ?></td>
                                                        <td><?php echo htmlentities($result->FromDate); ?></td>
                                                        <td><?php echo htmlentities($result->ToDate); ?></td>
                                                        <td><?php echo htmlentities($result->message); ?></td>
                                                        <td>
                                                            <?php if (!empty($result->image)) { ?>
                                                                <img src="..//<?php echo htmlentities($result->image); ?>" width="100"
                                                                    height="50" alt="Valid ID" data-toggle="modal"
                                                                    data-target="#imageModal"
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
                                                            if ($result->Status == 2) {
                                                                echo "<span style='color:red'>Rejected</span>";
                                                            } else if ($result->Status == 6) {
                                                                echo "<span style='color:green'>Car Returned</span>";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo htmlentities($result->PostingDate); ?></td>
                                                    </tr>
                                                    <?php $cnt = $cnt + 1;
                                                }
                                            } else { ?>
                                                <tr>
                                                    <td colspan="11">No archived bookings found</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

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
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <a href="dashboard.php"><input type="button" class="w3-button w3-blue"
                                            value="Go Back"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
            aria-hidden="true">
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