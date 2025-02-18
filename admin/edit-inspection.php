<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    $error = ''; 
    $msg = '';

    if (isset($_POST['submit'])) {

        if (empty($_POST['current_mileage']) || empty($_POST['inspection_date']) || trim($_POST['inspection_date']) === "dd/mm/yyyy") {
            $error = 'Please input required fields (inspection date or current mileage)';
        } else {

            $id = $_POST['id'];
            $vehicle = $_POST['vehicle'];
            $inspector = $_POST['inspector'];
            $inspection_date = $_POST['inspection_date'];
            $notes = $_POST['notes'];
            $outgoing_meter = $_POST['current_mileage'];
            $outgoing_fuel = $_POST['outgoing_fuel'];
            $car_availability = ($_POST['car_availability'] == "Available") ? 1 : 0;


            $engine_fluids_status = $_POST['engine_fluids_status'] === 'done' ? 1 : 0;
            $engine_fluids_remarks = $_POST['engine_fluids_remarks'];
            $battery_status = $_POST['battery_status'] === 'done' ? 1 : 0;
            $battery_remarks = $_POST['battery_remarks'];
            $tires_status = $_POST['tires_status'] === 'done' ? 1 : 0;
            $tires_remarks = $_POST['tires_remarks'];
            $brakes_status = $_POST['brakes_status'] === 'done' ? 1 : 0;
            $brakes_remarks = $_POST['brakes_remarks'];
            $lights_electrical_status = $_POST['lights_electrical_status'] === 'done' ? 1 : 0;
            $lights_electrical_remarks = $_POST['lights_electrical_remarks'];
            $air_filters_status = $_POST['air_filters_status'] === 'done' ? 1 : 0;
            $air_filters_remarks = $_POST['air_filters_remarks'];
            $belts_hoses_status = $_POST['belts_hoses_status'] === 'done' ? 1 : 0;
            $belts_hoses_remarks = $_POST['belts_hoses_remarks'];
            $suspension_status = $_POST['suspension_status'] === 'done' ? 1 : 0;
            $suspension_remarks = $_POST['suspension_remarks'];
            $exhaust_system_status = $_POST['exhaust_system_status'] === 'done' ? 1 : 0;
            $exhaust_system_remarks = $_POST['exhaust_system_remarks'];
            $alignment_suspension_status = $_POST['alignment_suspension_status'] === 'done' ? 1 : 0;
            $alignment_suspension_remarks = $_POST['alignment_suspension_remarks'];
            $wipers_windshield_status = $_POST['wipers_windshield_status'] === 'done' ? 1 : 0;
            $wipers_windshield_remarks = $_POST['wipers_windshield_remarks'];
            $timing_belt_chain_status = $_POST['timing_belt_chain_status'] === 'done' ? 1 : 0;
            $timing_belt_chain_remarks = $_POST['timing_belt_chain_remarks'];
            $air_conditioning_heater_status = $_POST['air_conditioning_heater_status'] === 'done' ? 1 : 0;
            $air_conditioning_heater_remarks = $_POST['air_conditioning_heater_remarks'];
            $cabin_exterior_maintenance_status = $_POST['cabin_exterior_maintenance_status'] === 'done' ? 1 : 0;
            $cabin_exterior_maintenance_remarks = $_POST['cabin_exterior_maintenance_remarks'];
            $professional_inspections_status = $_POST['professional_inspections_status'] === 'done' ? 1 : 0;
            $professional_inspections_remarks = $_POST['professional_inspections_remarks'];

        
            $sql = "UPDATE tblinspections SET 
                vehicle = :vehicle, 
                inspector = :inspector, 
                inspection_date = :inspection_date, 
                notes = :notes, 
                outgoing_meter = :outgoing_meter, 
                outgoing_fuel = :outgoing_fuel,
                engine_fluids = :engine_fluids,
                engine_fluids_remarks = :engine_fluids_remarks,
                battery = :battery,
                battery_remarks = :battery_remarks,
                tires = :tires,
                tires_remarks = :tires_remarks,
                brakes = :brakes,
                brakes_remarks = :brakes_remarks,
                lights_electrical = :lights_electrical,
                lights_electrical_remarks = :lights_electrical_remarks,
                air_filters = :air_filters,
                air_filters_remarks = :air_filters_remarks,
                belts_hoses = :belts_hoses,
                belts_hoses_remarks = :belts_hoses_remarks,
                suspension = :suspension,
                suspension_remarks = :suspension_remarks,
                exhaust_system = :exhaust_system,
                exhaust_system_remarks = :exhaust_system_remarks,
                alignment_suspension = :alignment_suspension,
                alignment_suspension_remarks = :alignment_suspension_remarks,
                wipers_windshield = :wipers_windshield,
                wipers_windshield_remarks = :wipers_windshield_remarks,
                timing_belt_chain = :timing_belt_chain,
                timing_belt_chain_remarks = :timing_belt_chain_remarks,
                air_conditioning_heater = :air_conditioning_heater,
                air_conditioning_heater_remarks = :air_conditioning_heater_remarks,
                cabin_exterior_maintenance = :cabin_exterior_maintenance,
                cabin_exterior_maintenance_remarks = :cabin_exterior_maintenance_remarks,
                professional_inspections = :professional_inspections,
                professional_inspections_remarks = :professional_inspections_remarks";

                $sql .= " WHERE id = :id";

            $query = $dbh->prepare($sql);
            $query->bindParam(':vehicle', $vehicle, PDO::PARAM_STR);
            $query->bindParam(':inspector', $inspector, PDO::PARAM_STR);
            $query->bindParam(':inspection_date', $inspection_date, PDO::PARAM_STR);
            $query->bindParam(':notes', $notes, PDO::PARAM_STR);
            $query->bindParam(':outgoing_meter', $outgoing_meter, PDO::PARAM_STR);
            $query->bindParam(':outgoing_fuel', $outgoing_fuel, PDO::PARAM_STR);

            $query->bindParam(':engine_fluids', $engine_fluids_status, PDO::PARAM_STR);
            $query->bindParam(':engine_fluids_remarks', $engine_fluids_remarks, PDO::PARAM_STR);
            $query->bindParam(':battery', $battery_status, PDO::PARAM_STR);
            $query->bindParam(':battery_remarks', $battery_remarks, PDO::PARAM_STR);
            $query->bindParam(':tires', $tires_status, PDO::PARAM_STR);
            $query->bindParam(':tires_remarks', $tires_remarks, PDO::PARAM_STR);
            $query->bindParam(':brakes', $brakes_status, PDO::PARAM_STR);
            $query->bindParam(':brakes_remarks', $brakes_remarks, PDO::PARAM_STR);
            $query->bindParam(':lights_electrical', $lights_electrical_status, PDO::PARAM_STR);
            $query->bindParam(':lights_electrical_remarks', $lights_electrical_remarks, PDO::PARAM_STR);
            $query->bindParam(':air_filters', $air_filters_status, PDO::PARAM_STR);
            $query->bindParam(':air_filters_remarks', $air_filters_remarks, PDO::PARAM_STR);
            $query->bindParam(':belts_hoses', $belts_hoses_status, PDO::PARAM_STR);
            $query->bindParam(':belts_hoses_remarks', $belts_hoses_remarks, PDO::PARAM_STR);
            $query->bindParam(':suspension', $suspension_status, PDO::PARAM_STR);
            $query->bindParam(':suspension_remarks', $suspension_remarks, PDO::PARAM_STR);
            $query->bindParam(':exhaust_system', $exhaust_system_status, PDO::PARAM_STR);
            $query->bindParam(':exhaust_system_remarks', $exhaust_system_remarks, PDO::PARAM_STR);
            $query->bindParam(':alignment_suspension', $alignment_suspension_status, PDO::PARAM_STR);
            $query->bindParam(':alignment_suspension_remarks', $alignment_suspension_remarks, PDO::PARAM_STR);
            $query->bindParam(':wipers_windshield', $wipers_windshield_status, PDO::PARAM_STR);
            $query->bindParam(':wipers_windshield_remarks', $wipers_windshield_remarks, PDO::PARAM_STR);
            $query->bindParam(':timing_belt_chain', $timing_belt_chain_status, PDO::PARAM_STR);
            $query->bindParam(':timing_belt_chain_remarks', $timing_belt_chain_remarks, PDO::PARAM_STR);
            $query->bindParam(':air_conditioning_heater', $air_conditioning_heater_status, PDO::PARAM_STR);
            $query->bindParam(':air_conditioning_heater_remarks', $air_conditioning_heater_remarks, PDO::PARAM_STR);
            $query->bindParam(':cabin_exterior_maintenance', $cabin_exterior_maintenance_status, PDO::PARAM_STR);
            $query->bindParam(':cabin_exterior_maintenance_remarks', $cabin_exterior_maintenance_remarks, PDO::PARAM_STR);
            $query->bindParam(':professional_inspections', $professional_inspections_status, PDO::PARAM_STR);
            $query->bindParam(':professional_inspections_remarks', $professional_inspections_remarks, PDO::PARAM_STR);

            $query->bindParam(':id', $id, PDO::PARAM_INT);

            if ($query->execute()) {
                $msg = "Inspection updated successfully";
            } else {
                $error = "Failed to update record";
            }

            // Update `tblvehicles` as per your existing logic (unchanged)
            $sql1 = "UPDATE tblvehicles SET status = :car_availability WHERE id = :vehicle_id";
            $query1 = $dbh->prepare($sql1);
            $query1->bindParam(':car_availability', $car_availability, PDO::PARAM_STR);
            $query1->bindParam(':vehicle_id', $vehicle, PDO::PARAM_INT);
            $query1->execute();

        }
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

    $vhId = $inspection->vehicle;

    // Fetch car status from tblvehicles using the vehicle column (ID)
    $sql3 = "SELECT status FROM tblvehicles WHERE id = :vehicle_id";
    $query3 = $dbh->prepare($sql3);  // Use $sql3 instead of $sql
    $query3->bindParam(':vehicle_id', $vhId, PDO::PARAM_INT);
    $query3->execute();
    $car_status = $query3->fetch(PDO::FETCH_OBJ);

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
                            <h2 class="page-title">Inspection And Maitenance</h2>
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
                                        <input type="hidden" name="id" id="vehicleId" value="<?php echo htmlentities($inspection->id); ?>">

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
                                            <label class="col-sm-2 col-form-label">Car Availability</label>
                                            <div class="col-sm-10">
                                                <select name="car_availability" class="form-control" required>
                                                    <option value="Available" <?php echo (!empty($car_status) && $car_status->status == 1) ? "selected" : ""; ?>>Available</option>
                                                    <option value="Unavailable" <?php echo (!empty($car_status) && $car_status->status == 0) ? "selected" : ""; ?>>Unavailable</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Last Inspection Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" id="inspection_date" name="inspection_date" class="form-control"
                                                    value="<?php echo htmlentities($inspection->inspection_date); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Last Maintanance Mileage</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="outgoing_meter" class="form-control"
                                                    value="<?php echo htmlentities($inspection->outgoing_meter); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Current Mileage</label>
                                            <div class="col-sm-10">
                                                <input type="number" name="current_mileage" id="current_mileage" class="form-control" placeholder="Enter current mileage..">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Fuel</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="outgoing_fuel" class="form-control"
                                                    value="<?php echo htmlentities($inspection->outgoing_fuel); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Notes</label>
                                            <div class="col-sm-10">
                                                <textarea name="notes"
                                                    class="form-control"><?php echo htmlentities($inspection->notes); ?></textarea>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="button" id="calculateMaintenance" class="btn btn-primary"> Calculate Maintenance</button>
                                            </div>
                                        </div>

                                        <div id="result"></div>

                                        <hr style="border: 1px solid #ccc; margin-top: 50px;">

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
                var updateButton = $("button[name='submit']");

                updateButton.hide();

                $("#calculateMaintenance").click(function () {
                    var vehicleId = $("#vehicleId").val();
                    var currentMileage = $("#current_mileage").val();
                    var inspectionDate = $("#inspection_date").val();

                    var resultContainer = $("#result");
                    resultContainer.html("<div class='alert alert-info'>Calculating...</div>");

                    if (vehicleId && currentMileage && inspectionDate) {

                        console.log(vehicleId);
                        $.ajax({
                            url: "run_maintenance.php",
                            type: "POST",
                            data: { vehicleId: vehicleId, current_mileage: currentMileage },
                            dataType: "json",
                            beforeSend: function () {
                                resultContainer.html("<div class='alert alert-info'>Calculating...</div>");
                            },
                            success: function(response) {

                                if (response.error) {
                                    resultContainer.html("<div class='alert alert-danger'>" + response.error + "</div>");
                                    return;
                                }

                                var output = `
                                    <hr style="border: 1px solid #ccc; margin-top: 50px;">
                                    <h3>Car Maintenance Prediction Results</h3>
                                    <br>
                                    <h5><strong>Last Maintenance Mileage:</strong> ${response.selectedCar_last_maintenance}</h5>
                                    <h5><strong>Current Mileage:</strong> ${response.selectedCar_current_mileage}</h5>

                                    <div class="form-group row">
                                        <div class="col-sm-2"> </div>
                                        <div class="col-sm-10">
                                            <div class="col-sm-2">
                                                <h4>AI Suggestions</h4>
                                            </div>
                                            <div class="col-sm-3">
                                                <h4>Maintenance Status</h4>
                                            </div>
                                            <div class="col-sm-7">
                                                <h4>Remarks</h4>
                                            </div>
                                            
                                        </div>
                                    </div>   
                                `;


                                
                                console.log('inspection results: ', response);
                                $.each(response.inspection_results, function(inspection, data) {
                                    if (data.error) {
                                        output += `<p style='color:red;'><strong>${inspection.replace(/_/g, " ")}:</strong> ${data.error}</p>`;
                                    } else {
                                        let statusBadge = data.maintenance_prediction === "Yes"
                                            ? `<span class="badge badge-warning" style="background-color: #ffcc00 !important; color: #212529 !important;">Needs Maintenance</span>`
                                            : `<span class="badge badge-success">Maintenance Not Required</span>`;

                                        output += `
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">${inspection.replace(/_/g, ' ')}</label>
                                                <div class="col-sm-10">
                                                    <div class="col-sm-2">${statusBadge}</div>
                                                    <div class="col-sm-3">
                                                        <div class="form-check form-check-inline col-sm-6">
                                                            <input class="form-check-input" type="radio" name="${inspection}_status" value="done" ${data.status === 1 ? 'checked' : ''}>
                                                            <label class="form-check-label">Done</label>
                                                        </div>
                                                        <div class="form-check form-check-inline col-sm-6">
                                                            <input class="form-check-input" type="radio" name="${inspection}_status" value="pending" ${data.status === 0 ? 'checked' : ''}>
                                                            <label class="form-check-label">Pending</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <textarea name="${inspection}_remarks" class="form-control" placeholder="Remarks here..">${data.remarks}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    }
                                });

                                resultContainer.html(output);
                                updateButton.show();
                            },
                            error: function() {
                                $("#result").html("<p style='color:red;'>Error processing request.</p>");
                            }
                        });
                    } else {
                        $("#result").html("<p style='color:red;'>Please enter all required fields.</p>");
                    }
                });


                // Save Maintenance Click Event
                $("#saveMaintenance").click(function () {
                    let formData = {};

                    $('input[type=radio]:checked').each(function () {
                        let name = $(this).attr('name');
                        let inspection = name.replace('_status', '');
                        formData[inspection] = formData[inspection] || {};
                        formData[inspection]['status'] = $(this).val();
                    });

                    $('textarea[name$="_remarks"]').each(function () {
                        let name = $(this).attr('name');
                        let inspection = name.replace('_remarks', '');
                        formData[inspection] = formData[inspection] || {};
                        formData[inspection]['remarks'] = $(this).val();
                    });

                    console.log(formData); // Debugging

                    $.ajax({
                        url: "save_maintenance.php",
                        type: "POST",
                        data: JSON.stringify(formData),
                        contentType: "application/json",
                        success: function (response) {
                            alert("Maintenance results saved successfully!");
                        },
                        error: function () {
                            alert("Error saving maintenance results.");
                        }
                    });
                });
            });

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