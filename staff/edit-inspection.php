<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {
        $id = $_POST['id'];
        $vehicle = $_POST['vehicle'];
        $inspector = $_POST['inspector'];
        $inspection_date = $_POST['inspection_date'];
        $inspection_status = $_POST['inspection_status'];
        $repair_status = $_POST['repair_status'];
        $notes = $_POST['notes'];
        $outgoing_date = $_POST['outgoing_date'];
        $outgoing_time = $_POST['outgoing_time'];
        $outgoing_meter = $_POST['outgoing_meter'];
        $outgoing_fuel = $_POST['outgoing_fuel'];
        $incoming_date = $_POST['incoming_date'];
        $incoming_time = $_POST['incoming_time'];
        $incoming_meter = $_POST['incoming_meter'];
        $incoming_fuel = $_POST['incoming_fuel'];
        $checklist = $_POST['checklist'];

        $sql = "UPDATE tblinspections SET 
            vehicle = :vehicle, 
            inspector = :inspector, 
            inspection_date = :inspection_date, 
            inspection_status = :inspection_status, 
            repair_status = :repair_status, 
            notes = :notes, 
            outgoing_date = :outgoing_date, 
            outgoing_time = :outgoing_time, 
            outgoing_meter = :outgoing_meter, 
            outgoing_fuel = :outgoing_fuel, 
            incoming_date = :incoming_date, 
            incoming_time = :incoming_time, 
            incoming_meter = :incoming_meter, 
            incoming_fuel = :incoming_fuel, 
            checklist = :checklist 
            WHERE id = :id";

        $query = $dbh->prepare($sql);
        $query->bindParam(':vehicle', $vehicle, PDO::PARAM_STR);
        $query->bindParam(':inspector', $inspector, PDO::PARAM_STR);
        $query->bindParam(':inspection_date', $inspection_date, PDO::PARAM_STR);
        $query->bindParam(':inspection_status', $inspection_status, PDO::PARAM_STR);
        $query->bindParam(':repair_status', $repair_status, PDO::PARAM_STR);
        $query->bindParam(':notes', $notes, PDO::PARAM_STR);
        $query->bindParam(':outgoing_date', $outgoing_date, PDO::PARAM_STR);
        $query->bindParam(':outgoing_time', $outgoing_time, PDO::PARAM_STR);
        $query->bindParam(':outgoing_meter', $outgoing_meter, PDO::PARAM_STR);
        $query->bindParam(':outgoing_fuel', $outgoing_fuel, PDO::PARAM_STR);
        $query->bindParam(':incoming_date', $incoming_date, PDO::PARAM_STR);
        $query->bindParam(':incoming_time', $incoming_time, PDO::PARAM_STR);
        $query->bindParam(':incoming_meter', $incoming_meter, PDO::PARAM_STR);
        $query->bindParam(':incoming_fuel', $incoming_fuel, PDO::PARAM_STR);
        $query->bindParam(':checklist', json_encode($checklist), PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $msg = "Inspection updated successfully";
    }

    // Fetch inspection data based on ID from URL
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM tblinspections WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $inspection = $query->fetch(PDO::FETCH_OBJ);

        if (!$inspection) {
            header('location:manage-inspections.php'); // Redirect if not found
        }
    }

    // Fetch checklist items from tblinspectiontype
    $sql = "SELECT * FROM tblinspectiontype";
    $query = $dbh->prepare($sql);
    $query->execute();
    $checklistItems = $query->fetchAll(PDO::FETCH_OBJ);

    // Fetch vehicles from tblvehicles table
    $sql = "SELECT id, VehiclesTitle FROM tblvehicles";
    $query = $dbh->prepare($sql);
    $query->execute();
    $vehicles = $query->fetchAll(PDO::FETCH_OBJ);
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
        <title>Car Rental Portal | Staff Edit Inspection</title>

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
                            <h2 class="page-title">Edit Inspection</h2>
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
                                        <input type="hidden" name="id" value="<?php echo htmlentities($inspection->id); ?>">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Vehicle</label>
                                            <div class="col-sm-10">
                                                <select name="vehicle" class="form-control select2" required>
                                                    <option value="">Select Vehicle</option>
                                                    <?php foreach ($vehicles as $vehicle) { ?>
                                                        <option value="<?php echo htmlentities($vehicle->id); ?>" <?php echo ($vehicle->id == $inspection->vehicle) ? 'selected' : ''; ?>>
                                                            <?php echo htmlentities($vehicle->VehiclesTitle); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Inspector</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="inspector" class="form-control"
                                                    value="<?php echo htmlentities($inspection->inspector); ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Inspection Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" name="inspection_date" class="form-control"
                                                    value="<?php echo htmlentities($inspection->inspection_date); ?>"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Inspection Status</label>
                                            <div class="col-sm-10">
                                                <select name="inspection_status" class="form-control">
                                                    <option value="Pending" <?php echo ($inspection->inspection_status == 'Pending') ? 'selected' : ''; ?>>
                                                        Pending</option>
                                                    <option value="Completed" <?php echo ($inspection->inspection_status == 'Completed') ? 'selected' : ''; ?>>
                                                        Completed</option>
                                                    <option value="In Progress" <?php echo ($inspection->inspection_status == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                    <option value="Reject" <?php echo ($inspection->inspection_status == 'Reject') ? 'selected' : ''; ?>>
                                                        Reject</option>
                                                    <option value="Conditional Pass" <?php echo ($inspection->inspection_status == 'Conditional Pass') ? 'selected' : ''; ?>>Conditional Pass</option>
                                                    <option value="On Hold" <?php echo ($inspection->inspection_status == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Repair Status</label>
                                            <div class="col-sm-10">
                                                <select name="repair_status" class="form-control">
                                                    <option value="Needs Repair" <?php echo ($inspection->repair_status == 'Needs Repair') ? 'selected' : ''; ?>>
                                                        Needs Repair</option>
                                                    <option value="Pending" <?php echo ($inspection->repair_status == 'Pending') ? 'selected' : ''; ?>>
                                                        Pending</option>
                                                    <option value="Completed" <?php echo ($inspection->repair_status == 'Completed') ? 'selected' : ''; ?>>
                                                        Completed</option>
                                                    <option value="In Progress" <?php echo ($inspection->repair_status == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Notes</label>
                                            <div class="col-sm-10">
                                                <textarea name="notes"
                                                    class="form-control"><?php echo htmlentities($inspection->notes); ?></textarea>
                                            </div>
                                        </div>

                                        <h4>Outgoing Details</h4>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Outgoing Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" name="outgoing_date" class="form-control"
                                                    value="<?php echo htmlentities($inspection->outgoing_date); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Outgoing Time</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="outgoing_time" class="form-control"
                                                    value="<?php echo htmlentities($inspection->outgoing_time); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Outgoing Meter</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="outgoing_meter" class="form-control"
                                                    value="<?php echo htmlentities($inspection->outgoing_meter); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Outgoing Fuel</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="outgoing_fuel" class="form-control"
                                                    value="<?php echo htmlentities($inspection->outgoing_fuel); ?>">
                                            </div>
                                        </div>

                                        <h4>Incoming Details</h4>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Incoming Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" name="incoming_date" class="form-control"
                                                    value="<?php echo htmlentities($inspection->incoming_date); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Incoming Time</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="incoming_time" class="form-control"
                                                    value="<?php echo htmlentities($inspection->incoming_time); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Incoming Meter</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="incoming_meter" class="form-control"
                                                    value="<?php echo htmlentities($inspection->incoming_meter); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Incoming Fuel</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="incoming_fuel" class="form-control"
                                                    value="<?php echo htmlentities($inspection->incoming_fuel); ?>">
                                            </div>
                                        </div>



                                        <div class="section-title">Inspections Checklist</div>
                                        <div class="row">
                                            <?php foreach ($checklistItems as $item) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group checklist-item"
                                                        style="margin-left: 10px; margin-right: 10px;">
                                                        <label><?php echo htmlentities($item->inspectiontype_name); ?></label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="checklist[<?php echo $item->id; ?>]"
                                                                value="<?php echo htmlentities($item->inspectiontype_name); ?>">
                                                            <?php echo (in_array($item->id, json_decode($inspection->checklist, true))) ? 'checked' : ''; ?>>
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <textarea name="checklist_notes[<?php echo $item->id; ?>]"
                                                            class="form-control" placeholder="Enter notes"></textarea>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>




                                        <div class="form-group row">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="submit" name="submit" class="btn btn-primary">Update
                                                    Inspection</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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