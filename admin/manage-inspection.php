<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {
        $vehicle = $_POST['vehicle'];
        $inspector = $_POST['inspector'];
        $inspection_date = $_POST['inspection_date'];
        $inspection_status = $_POST['inspection_status'];
        $repair_status = $_POST['repair_status'];
        $notes = $_POST['notes'];

        $sql = "INSERT INTO tblinspections 
            (vehicle, inspector, inspection_date, inspection_status, repair_status, notes) 
            VALUES (:vehicle, :inspector, :inspection_date, :inspection_status, :repair_status, :notes)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':vehicle', $vehicle, PDO::PARAM_STR);
        $query->bindParam(':inspector', $inspector, PDO::PARAM_STR);
        $query->bindParam(':inspection_date', $inspection_date, PDO::PARAM_STR);
        $query->bindParam(':inspection_status', $inspection_status, PDO::PARAM_STR);
        $query->bindParam(':repair_status', $repair_status, PDO::PARAM_STR);
        $query->bindParam(':notes', $notes, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $msg = "Inspection recorded successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
    if (isset($_GET['del'])) {
        $inspection_id = $_GET['del'];
        $sql = "DELETE FROM tblinspections WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $inspection_id, PDO::PARAM_INT); // Use PDO::PARAM_INT for integer IDs
        $query->execute();

        if ($query->rowCount() > 0) {
            $msg = "Inspection deleted successfully";
        } else {
            $error = "Failed to delete the inspection. Please try again.";
        }
    }


    // Fetch inspections with vehicle names
    $sql = "SELECT i.*, v.VehiclesTitle FROM tblinspections i 
            JOIN tblvehicles v ON i.vehicle = v.id"; // Assuming 'id' is the primary key in tblvehicles
    $query = $dbh->prepare($sql);
    $query->execute();
    $inspections = $query->fetchAll(PDO::FETCH_OBJ);

    // Define colors for Repair Status
    $repairColors = [
        'Needs Repair' => ['bg' => '#DC3545', 'color' => '#FFFFFF'],
        'Pending' => ['bg' => '#FFC107', 'color' => '#000000'],
        'Completed' => ['bg' => '#28A745', 'color' => '#FFFFFF'],
        'In Progress' => ['bg' => '#17A2B8', 'color' => '#FFFFFF'],
        'On Hold' => ['bg' => '#6C757D', 'color' => '#FFFFFF'],
    ];

    // Define colors for statuses of inspections
    $inspectionStatusColors = [
        'pending' => ['bg' => '#FFC107', 'color' => '#000000', 'text' => 'Needs Inspection'],
        'done' => ['bg' => '#28A745', 'color' => '#FFFFFF', 'text' => 'Inspection Completed'],
        'overdue' => ['bg' => '#DC3545', 'color' => '#FFFFFF', 'text' => 'Inspection Overdue'],
        'unknown' => ['bg' => '#6C757D', 'color' => '#FFFFFF', 'text' => 'Unknown']
    ]

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

        <title>Car Rental Portal | All Inspection</title>

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
        <!-- Admin Style -->
        <link rel="stylesheet" href="css/style.css">

        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
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
                            <h2 class="page-title">Manage Inspections</h2>
                            <div style="margin-bottom: 20px; font-size: 12px;">
                                <a href="add-inspection.php" class="btn btn-primary">Add Inspection</a>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">Inspection Records</div>
                                <div class="panel-body">
                                    <?php if ($error) { ?>
                                        <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
                                    <?php } else if ($msg) { ?>
                                            <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
                                    <?php } ?>
                                    <table id="inspectionTable"
                                        class="display table table-striped table-bordered table-hover" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Vehicle</th>
                                                <th>Inspector</th>
                                                <th>Repair Status</th>
                                                <th>Monthly Inspection Status</th>
                                                <th>Quarterly Inspection Status</th>
                                                <th>Annually Inspection Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            foreach ($inspections as $inspection) {
                                                // Get colors based on inspection status
                                                $inspectionColor = $inspectionColors[$inspection->inspection_status] ?? ['bg' => '#ffffff', 'color' => '#000000'];
                                                $repairColor = $repairColors[$inspection->repair_status] ?? ['bg' => '#ffffff', 'color' => '#000000'];

                                                // constant variables
                                                $dateToday = date('Y-m-d');
                                                $today = new DateTime($dateToday);

                                                $createdAt = new DateTime($inspection->created_at);

                                                // monthly inspection status
                                                if($inspection->last_monthly_inspection) {
                                                    
                                                    $lastMonthDateOfInspection = new DateTime($inspection->last_monthly_inspection);
                                                    
                                                    $interval = $lastMonthDateOfInspection->diff($today);
    
                                                    $daysPassed = $interval->days;

                                                    if ($daysPassed >= 30 && $daysPassed < 37) {
                                                        $monthlyInspectionColor = $inspectionStatusColors['pending'];
                                                    } else if ($daysPassed > 37) {
                                                        $monthlyInspectionColor = $inspectionStatusColors['overdue'];
                                                    } else if ($daysPassed < 30) {
                                                        $monthlyInspectionColor = $inspectionStatusColors['done'];
                                                    } else {
                                                        $monthlyInspectionColor = $inspectionStatusColors['unknown'];
                                                    }

                                                } else {
                                                    
                                                    $interval = $createdAt->diff($today);
    
                                                    $daysPassed = $interval->days;

                                                    if ($daysPassed >= 30 && $daysPassed < 37) {
                                                        $monthlyInspectionColor = $inspectionStatusColors['pending'];
                                                    } else if ($daysPassed > 37) {
                                                        $monthlyInspectionColor = $inspectionStatusColors['overdue'];
                                                    } else if ($daysPassed < 30) {
                                                        $monthlyInspectionColor = $inspectionStatusColors['done'];
                                                    } else {
                                                        $monthlyInspectionColor = $inspectionStatusColors['unknown'];
                                                    }
                                                }


                                                // quarterly inspection status
                                                if($inspection->last_quarterly_inspection) {

                                                    $lastQuarterlyDateOfInspection = new DateTime($inspection->last_quarterly_inspection);
    
                                                    $interval = $lastQuarterlyDateOfInspection->diff($today);
    
                                                    $monthsPassed = ($interval->y * 12) + $interval->m;

                                                    if ($monthsPassed >= 3 && $monthsPassed < 4) {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['pending'];
                                                    } else if ($monthsPassed > 4) {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['overdue'];
                                                    } else if ($monthsPassed < 3) {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['done'];
                                                    } else {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['unknown'];
                                                    }

                                                } else {
    
                                                    $interval = $createdAt->diff($today);
    
                                                    $monthsPassed = ($interval->y * 12) + $interval->m;

                                                    if ($monthsPassed >= 3 && $monthsPassed < 5) {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['pending'];
                                                    } else if ($monthsPassed > 4) {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['overdue'];
                                                    } else if ($monthsPassed < 3) {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['done'];
                                                    } else {
                                                        $quarterlyInspectionColor = $inspectionStatusColors['unknown'];
                                                    }
                                                }


                                                // annually inspection status
                                                if ($inspection->last_annual_inspection) {
                                                    $lastAnnualDateOfInspection = new DateTime($inspection->last_annual_inspection);
                                                
                                                    $interval = $lastAnnualDateOfInspection->diff($today);
                                                
                                                    $monthsPassed = ($interval->y * 12) + $interval->m;
                                                
                                                    if ($monthsPassed >= 12 && $monthsPassed < 15) {
                                                        $annualInspectionColor = $inspectionStatusColors['pending'];
                                                    } else if ($monthsPassed >= 15) {
                                                        $annualInspectionColor = $inspectionStatusColors['overdue'];
                                                    } else if ($monthsPassed < 12) {
                                                        $annualInspectionColor = $inspectionStatusColors['done'];
                                                    } else {
                                                        $annualInspectionColor = $inspectionStatusColors['unknown'];
                                                    }
                                                } else {
                                                
                                                    $interval = $createdAt->diff($today);
                                                
                                                    $monthsPassed = ($interval->y * 12) + $interval->m;
                                                
                                                    if ($monthsPassed >= 12 && $monthsPassed < 15) {
                                                        $annualInspectionColor = $inspectionStatusColors['pending'];
                                                    } else if ($monthsPassed >= 15) {
                                                        $annualInspectionColor = $inspectionStatusColors['overdue'];
                                                    } else if ($monthsPassed < 12) {
                                                        $annualInspectionColor = $inspectionStatusColors['done'];
                                                    } else {
                                                        $annualInspectionColor = $inspectionStatusColors['unknown'];
                                                    }
                                                }

                                                ?>

                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td>
                                                        <p><?php echo htmlentities($inspection->VehiclesTitle); ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?php echo htmlentities($inspection->inspector); ?></p>
                                                    </td>
                                                    <td>
                                                        <p style="background-color: <?php echo $repairColor['bg']; ?>; color:
                                                            <?php echo $repairColor['color']; ?>; padding: 10px 15px;
                                                            border-radius: 5px;">
                                                            <?php echo htmlentities($inspection->repair_status); ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p style="background-color: <?php echo $monthlyInspectionColor['bg']; ?>; color:
                                                            <?php echo $monthlyInspectionColor['color']; ?>; padding: 10px 15px;
                                                            border-radius: 5px;">
                                                            <?php echo htmlentities($monthlyInspectionColor['text']); ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p style="background-color: <?php echo $quarterlyInspectionColor['bg']; ?>; color:
                                                            <?php echo $quarterlyInspectionColor['color']; ?>; padding: 10px 15px;
                                                            border-radius: 5px;">
                                                            <?php echo htmlentities($quarterlyInspectionColor['text']); ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p style="background-color: <?php echo $annualInspectionColor['bg']; ?>; color:
                                                            <?php echo $annualInspectionColor['color']; ?>; padding: 10px 15px;
                                                            border-radius: 5px;">
                                                            <?php echo htmlentities($annualInspectionColor['text']); ?>
                                                        </p>
                                                    </td>
                                                    <td>

                                                        <a href="edit-inspection.php?id=<?php echo htmlentities($inspection->id); ?>"
                                                            class="btn btn-primary">Inspect</a>
                                                
                                                        <?php if (strtolower($_SESSION['alogin']) === 'admin') {?>
                                                        <a href="manage-inspection.php?del=<?php echo htmlentities($inspection->id); ?>"
                                                            class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this inspection?');">
                                                            Delete
                                                        </a>
                                                        <?php } ?>

                                                </tr>
                                                <?php $cnt++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
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
    </body>

    </html>

<?php } ?>