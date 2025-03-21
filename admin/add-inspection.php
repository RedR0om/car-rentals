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
        $notes = $_POST['notes'];
        $outgoing_date = $_POST['outgoing_date'];
        $outgoing_meter = $_POST['outgoing_meter'];
        $outgoing_fuel = $_POST['outgoing_fuel'];

        $sql = "INSERT INTO tblinspections 
            (vehicle, inspector, notes, 
            outgoing_date, outgoing_meter, outgoing_fuel) 
            VALUES (:vehicle, :inspector, :notes, 
            :outgoing_date, :outgoing_meter, :outgoing_fuel )";
        $query = $dbh->prepare($sql);
        $query->bindParam(':vehicle', $vehicle, PDO::PARAM_STR);
        $query->bindParam(':inspector', $inspector, PDO::PARAM_STR);
        $query->bindParam(':notes', $notes, PDO::PARAM_STR);
        $query->bindParam(':outgoing_date', $outgoing_date, PDO::PARAM_STR);
        $query->bindParam(':outgoing_meter', $outgoing_meter, PDO::PARAM_STR);
        $query->bindParam(':outgoing_fuel', $outgoing_fuel, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();

        // insert in tblvehicle_maintenance
        $nextScheduledMaintenanceDate = date('Y-m-d', strtotime('+30 days'));
        $formattedNextScheduledMaintenanceDate = date("m/d/Y", strtotime($nextScheduledMaintenanceDate));
        $lastMaintenanceDate = date('Y-m-d');
        $formattedlastMaintenanceDate = date("m/d/Y", strtotime($lastMaintenanceDate));
        $inspectionType = 'professional_inspections';
        $remarks = '';
        $isMaintenance = rand(0,1);
        $aiIsMaintenance = rand(0,1);

        $sql2 = "INSERT INTO tblvehicle_maintenance 
        (vehicleId, last_mileage, mileage, inspection_type, remarks, 
        last_maintenance_date, next_scheduled_maintenance_date, is_maintenance, ai_is_maintenance) 
        VALUES (:vehicleId, :last_mileage, :mileage, :inspection_type, :remarks, :last_maintenance_date,
        :next_scheduled_maintenance_date, :is_maintenance, :ai_is_maintenance)";

        $query2 = $dbh->prepare($sql2);

        $query2->bindParam(':vehicleId', $lastInsertId, PDO::PARAM_STR);
        $query2->bindParam(':last_mileage', $outgoing_meter, PDO::PARAM_STR);
        $query2->bindParam(':mileage', $outgoing_meter, PDO::PARAM_STR);
        $query2->bindParam(':inspection_type', $inspectionType, PDO::PARAM_STR);
        $query2->bindParam(':remarks', $remarks, PDO::PARAM_STR);
        $query2->bindParam(':last_maintenance_date', $formattedlastMaintenanceDate, PDO::PARAM_STR);
        $query2->bindParam(':next_scheduled_maintenance_date', $formattedNextScheduledMaintenanceDate, PDO::PARAM_STR);
        $query2->bindParam(':is_maintenance', $isMaintenance, PDO::PARAM_INT);
        $query2->bindParam(':ai_is_maintenance', $aiIsMaintenance, PDO::PARAM_INT);

        $query2->execute();

        if ($lastInsertId) {
            $msg = "Inspection recorded successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }

    // Fetch checklist items from tblinspectiontype
    $sql = "SELECT * FROM tblinspectiontype";
    $query = $dbh->prepare($sql);
    $query->execute();
    $checklistItems = $query->fetchAll(PDO::FETCH_OBJ);
    ?>

    <!DOCTYPE html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="theme-color" content="#3e454c">
        <title>Car Rental Portal | Admin Add Photo</title>

        <!-- Font awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Bootstrap CSS -->
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
        <!-- Custom Style -->
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


        <style>
            body {
                background-color: #f4f4f4;
                font-family: 'Arial', sans-serif;
            }

            .container-fluid {
                margin-top: 20px;
            }

            .panel {
                border: none;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .panel-heading {
                background-color: #007bff;
                color: white;
                padding: 15px;
                border-radius: 8px 8px 0 0;
            }

            .form-heading {
                font-weight: bold;
                color: #555;
                padding: 15px 0;
                border-bottom: 2px solid #eee;
            }

            .form-control {
                border-radius: 0.3rem;
                box-shadow: none;
            }

            /* Style for the section title */
            .section-title {
                font-weight: bold;
                color: #333;
                margin: 20px 0 10px;
                border-bottom: 2px solid #eee;
                padding-bottom: 5px;
            }

            /* Style for each checklist item container */
            .checklist-item {
                display: flex;
                align-items: center;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                margin-bottom: 10px;
                background-color: #f9f9f9;
            }

            /* Style for the label */
            .checklist-item label {
                font-size: 16px;
                font-weight: bold;
                margin-right: 10px;
            }

            /* The switch - the box around the slider */
            .switch {
                position: relative;
                display: inline-block;
                width: 80px;
                height: 34px;
                margin: 0 15px;
            }

            /* Hide default HTML checkbox */
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            /* The slider */
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
            }

            input:checked+.slider {
                background-color: #2196F3;
            }

            input:focus+.slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked+.slider:before {
                transform: translateX(26px);
            }

            /* Rounded sliders */
            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }

            /* Style for the text area */
            .checklist-item textarea {
                flex-grow: 1;
                padding: 10px;
                border-radius: 4px;
                border: 1px solid #ccc;
                resize: none;
                margin-left: 15px;
            }

            .btn-primary {
                background-color: #28a745;
                border-color: #28a745;
                transition: background-color 0.3s ease;
            }

            .btn-primary:hover {
                background-color: #218838;
                border-color: #1e7e34;
            }

            .alert {
                border-radius: 0.3rem;
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
                            <h2 class="page-title form-heading">Vehicle Inspection Form</h2>
                            <div class="panel panel-default">
                                <div class="panel-heading">Inspection Details</div>
                                <div class="panel-body">
                                    <?php if ($error) { ?>
                                        <div class="alert alert-danger"><strong>ERROR:</strong>
                                            <?php echo htmlentities($error); ?></div>
                                    <?php } else if ($msg) { ?>
                                            <div class="alert alert-success"><strong>SUCCESS:</strong>
                                            <?php echo htmlentities($msg); ?></div>
                                    <?php } ?>

                                    <form method="post" class="form-horizontal">
                                        <?php
                                        // Fetch vehicles from tblvehicles table
                                        $sql = "SELECT id, VehiclesTitle FROM tblvehicles";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $vehicles = $query->fetchAll(PDO::FETCH_OBJ);
                                        ?>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Vehicle</label>
                                            <div class="col-sm-10">
                                                <select name="vehicle" class="form-control select2" required>
                                                    <option value="">Select Vehicle</option>
                                                    <?php foreach ($vehicles as $vehicle) { ?>
                                                        <option value="<?php echo htmlentities($vehicle->id); ?>">
                                                            <?php echo htmlentities($vehicle->VehiclesTitle); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Inspector</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="inspector" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Notes</label>
                                            <div class="col-sm-10">
                                                <textarea name="notes" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Date</label>
                                                    <div class="col-sm-8">
                                                        <input type="date" name="outgoing_date" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Meter Reading (Km)</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" name="outgoing_meter" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Fuel Level</label>
                                                    <div class="col-sm-8">
                                                        <select name="outgoing_fuel" class="form-control">
                                                            <option value="1/4">1/4</option>
                                                            <option value="1/2">1/2</option>
                                                            <option value="3/4">3/4</option>
                                                            <option value="Full">Full</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group text-center mt-4">
                                            <button type="submit" name="submit" class="btn btn-primary btn-lg">Submit
                                                Inspection</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
                    $('.select2').select2({
                        placeholder: "Select Vehicle",
                        allowClear: true,
                        // Optional: Configure the search functionality
                        minimumResultsForSearch: 0 // Show search box even if there are fewer options
                    });
                });
            </script>
    </body>

    </html>
<?php } ?>