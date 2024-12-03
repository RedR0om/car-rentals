<?php
session_start();
error_reporting(E_ALL); // Use E_ALL for better debugging
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit; // Always call exit after a redirect
}

if (isset($_POST['submit'])) {
    // Validate input fields
    $vehicletitle = $_POST['vehicletitle'] ?? '';
    $plate = $_POST['plate'] ?? '';
    $cartype = $_POST['cartype'] ?? '';
    $cartype_name = $_POST['cartype_name'] ?? '';
    $brand = $_POST['brandname'] ?? '';
    $vehicleoverview = $_POST['vehicalorcview'] ?? '';
    $priceperday = $_POST['priceperday'] ?? '';
    $fueltype = $_POST['fueltype'] ?? '';
    $modelyear = $_POST['modelyear'] ?? '';
    $seatingcapacity = $_POST['seatingcapacity'] ?? '';

    // Collect images
    $vimage1 = $_FILES["img1"]["name"] ?? '';
    $vimage2 = $_FILES["img2"]["name"] ?? '';
    $vimage3 = $_FILES["img3"]["name"] ?? '';
    $vimage4 = $_FILES["img4"]["name"] ?? '';
    $vimage5 = $_FILES["img5"]["name"] ?? '';

    // Collect accessory options
    $airconditioner = isset($_POST['airconditioner']) ? 1 : 0;
    $powerdoorlocks = isset($_POST['powerdoorlocks']) ? 1 : 0;
    $antilockbrakingsys = isset($_POST['antilockbrakingsys']) ? 1 : 0;
    $brakeassist = isset($_POST['brakeassist']) ? 1 : 0;
    $powersteering = isset($_POST['powersteering']) ? 1 : 0;
    $driverairbag = isset($_POST['driverairbag']) ? 1 : 0;
    $passengerairbag = isset($_POST['passengerairbag']) ? 1 : 0;
    $powerwindow = isset($_POST['powerwindow']) ? 1 : 0;
    $cdplayer = isset($_POST['cdplayer']) ? 1 : 0;
    $centrallocking = isset($_POST['centrallocking']) ? 1 : 0;
    $crashcensor = isset($_POST['crashcensor']) ? 1 : 0;
    $leatherseats = isset($_POST['leatherseats']) ? 1 : 0;

    try {
        // Check if the plate number already exists
        $sql = "SELECT * FROM tblvehicles WHERE plate = :plate";
        $query = $dbh->prepare($sql);
        $query->bindParam(':plate', $plate, PDO::PARAM_STR);
        $query->execute();

        if ($query->fetch(PDO::FETCH_ASSOC)) {
            // If a record with the same plate number already exists, show an error message
            $error = "A vehicle with the same plate number already exists.";
        } else {
            // Handle file uploads and check for errors
            for ($i = 1; $i <= 5; $i++) {
                if (isset($_FILES["img$i"]) && $_FILES["img$i"]["error"] == UPLOAD_ERR_OK) {
                    $targetPath = "img/vehicleimages/" . basename($_FILES["img$i"]["name"]);
                    if (!move_uploaded_file($_FILES["img$i"]["tmp_name"], $targetPath)) {
                        throw new Exception("Failed to upload image $i.");
                    }
                } else {
                    throw new Exception("File upload error for image $i.");
                }
            }

            // Insert vehicle data into the database
            $sql = "INSERT INTO tblvehicles 
                    (VehiclesTitle, plate, CarType, VehiclesBrand, VehiclesOverview, 
                    PricePerDay, FuelType, ModelYear, SeatingCapacity, Vimage1, Vimage2, Vimage3, 
                    Vimage4, Vimage5, AirConditioner, PowerDoorLocks, AntiLockBrakingSystem, 
                    BrakeAssist, PowerSteering, DriverAirbag, PassengerAirbag, PowerWindows, 
                    CDPlayer, CentralLocking, CrashSensor, LeatherSeats, TypeCar) 
                    VALUES 
                    (:vehicletitle, :plate, :cartype, :brand, :vehicleoverview, 
                    :priceperday, :fueltype, :modelyear, :seatingcapacity, :vimage1, :vimage2, 
                    :vimage3, :vimage4, :vimage5, :airconditioner, :powerdoorlocks, 
                    :antilockbrakingsys, :brakeassist, :powersteering, :driverairbag, 
                    :passengerairbag, :powerwindow, :cdplayer, :centrallocking, 
                    :crashcensor, :leatherseats, :cartype_name)";

            $query = $dbh->prepare($sql);
            // Bind parameters
            $query->bindParam(':vehicletitle', $vehicletitle, PDO::PARAM_STR);
            $query->bindParam(':plate', $plate, PDO::PARAM_STR);
            $query->bindParam(':cartype', $cartype, PDO::PARAM_STR);
            $query->bindParam(':brand', $brand, PDO::PARAM_STR);
            $query->bindParam(':vehicleoverview', $vehicleoverview, PDO::PARAM_STR);
            $query->bindParam(':priceperday', $priceperday, PDO::PARAM_STR);
            $query->bindParam(':fueltype', $fueltype, PDO::PARAM_STR);
            $query->bindParam(':modelyear', $modelyear, PDO::PARAM_STR);
            $query->bindParam(':seatingcapacity', $seatingcapacity, PDO::PARAM_STR);
            $query->bindParam(':vimage1', $vimage1, PDO::PARAM_STR);
            $query->bindParam(':vimage2', $vimage2, PDO::PARAM_STR);
            $query->bindParam(':vimage3', $vimage3, PDO::PARAM_STR);
            $query->bindParam(':vimage4', $vimage4, PDO::PARAM_STR);
            $query->bindParam(':vimage5', $vimage5, PDO::PARAM_STR);
            $query->bindParam(':airconditioner', $airconditioner, PDO::PARAM_INT);
            $query->bindParam(':powerdoorlocks', $powerdoorlocks, PDO::PARAM_INT);
            $query->bindParam(':antilockbrakingsys', $antilockbrakingsys, PDO::PARAM_INT);
            $query->bindParam(':brakeassist', $brakeassist, PDO::PARAM_INT);
            $query->bindParam(':powersteering', $powersteering, PDO::PARAM_INT);
            $query->bindParam(':driverairbag', $driverairbag, PDO::PARAM_INT);
            $query->bindParam(':passengerairbag', $passengerairbag, PDO::PARAM_INT);
            $query->bindParam(':powerwindow', $powerwindow, PDO::PARAM_INT);
            $query->bindParam(':cdplayer', $cdplayer, PDO::PARAM_INT);
            $query->bindParam(':centrallocking', $centrallocking, PDO::PARAM_INT);
            $query->bindParam(':crashcensor', $crashcensor, PDO::PARAM_INT);
            $query->bindParam(':leatherseats', $leatherseats, PDO::PARAM_INT);
            $query->bindParam(':cartype_name', $cartype_name, PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $msg = "Vehicle posted successfully.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    } catch (Exception $e) {
        // Handle exceptions
        $error = $e->getMessage();
    }
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

    <title>Car Rental Portal | Staff Post Vehicle</title>

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

        .required {
            color: red;
        }

        .hr-dashed {
            border-top: 1px dashed #ccc;
            margin: 20px 0;
        }

        .panel-body {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .form-control {
            margin-bottom: 10px;
        }

        h4 {
            margin-bottom: 15px;
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

                        <h2 class="page-title">Post A Vehicle</h2>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Basic Info</div>
                                    <?php if (isset($error) && !empty($error)) { ?>
                                        <div class="errorWrap">
                                            <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                        </div>
                                    <?php } else if (isset($msg) && !empty($msg)) { ?>
                                            <div class="succWrap">
                                                <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                            </div>
                                    <?php } ?>


                                    <div class="panel-body">
                                        <h2 class="text-center">Post Your Vehicle</h2>
                                        <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Vehicle Model<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="vehicletitle" class="form-control"
                                                        required>
                                                </div>
                                                <label class="col-sm-2 col-form-label">Select Brand<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <select class="selectpicker" name="brandname" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        $ret = "SELECT id, BrandName FROM tblbrands";
                                                        $query = $dbh->prepare($ret);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) {
                                                                ?>
                                                                <option value="<?php echo htmlentities($result->id); ?>">
                                                                    <?php echo htmlentities($result->BrandName); ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Select Car Type<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <select class="selectpicker" name="cartype_name" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        $ret = "SELECT cartype_id, cartype_name FROM tblcartype";
                                                        $query = $dbh->prepare($ret);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) {
                                                                ?>
                                                                <option
                                                                    value="<?php echo htmlentities($result->cartype_id); ?>">
                                                                    <?php echo htmlentities($result->cartype_name); ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Plate Number<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="plate" maxlength="6" minlength="6"
                                                        class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="hr-dashed"></div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Vehicle Overview<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <textarea class="form-control" name="vehicalorcview" rows="3"
                                                        required></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Price Per Day<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="priceperday" class="form-control" required>
                                                </div>
                                                <label class="col-sm-2 col-form-label">Select Fuel Type<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <select class="selectpicker" name="fueltype" required>
                                                        <option value="">Select</option>
                                                        <option value="Gas97">euro5 GAS 97</option>
                                                        <option value="Gas95">euro5 GAS 95</option>
                                                        <option value="Gas91">euro5 GAS 91</option>
                                                        <option value="Petrol">Petrol</option>
                                                        <option value="Diesel">Diesel</option>
                                                        <option value="CNG">CNG</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Select Car Transmission<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <select class="selectpicker" name="cartype" required>
                                                        <option value="">Select</option>
                                                        <option value="Manual">Manual</option>
                                                        <option value="Automatic">Automatic</option>
                                                        <option value="CVT">CVT</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 col-form-label">Select Vehicle Type<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <select class="selectpicker" name="carspecs" required>
                                                        <option value="">Select</option>
                                                        <option value="Standard">Standard</option>
                                                        <option value="Convertible">Convertible</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Model Year<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="modelyear" class="form-control" required>
                                                </div>
                                                <label class="col-sm-2 col-form-label">Seating Capacity<span
                                                        class="required">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="seatingcapacity" class="form-control"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="hr-dashed"></div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <h4><b>Upload Images</b></h4>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    Main Image<span class="required">*</span>
                                                    <input type="file" name="img1" class="form-control" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    Front Image<span class="required">*</span>
                                                    <input type="file" name="img2" class="form-control" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    Back Image<span class="required">*</span>
                                                    <input type="file" name="img3" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    Right Image
                                                    <input type="file" name="img4" class="form-control">
                                                </div>
                                                <div class="col-sm-4">
                                                    Left Image
                                                    <input type="file" name="img5" class="form-control">
                                                </div>
                                            </div>

                                            <div class="hr-dashed"></div>

                                            <div class="form-group row">
                                                <div class="col-sm-12 text-center">
                                                    <button type="submit" class="btn btn-custom" name="submit">Post
                                                        Vehicle</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        onclick="window.history.back();">Back</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Select Accessories</h4>
                                        </div>
                                        <div class="panel-body">

                                            <p>Please select the accessories that are available for this vehicle:</p>

                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="airconditioner" name="airconditioner"
                                                            value="1">
                                                        <label for="airconditioner">
                                                            <i class="fa fa-snowflake-o" aria-hidden="true"></i> Air
                                                            Conditioner
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="powerdoorlocks" name="powerdoorlocks"
                                                            value="1">
                                                        <label for="powerdoorlocks">
                                                            <i class="fa fa-lock" aria-hidden="true"></i> Power Door
                                                            Locks
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="antilockbrakingsys"
                                                            name="antilockbrakingsys" value="1">
                                                        <label for="antilockbrakingsys">
                                                            <i class="fa fa-car" aria-hidden="true"></i> AntiLock
                                                            Braking System
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="brakeassist" name="brakeassist"
                                                            value="1">
                                                        <label for="brakeassist">
                                                            <i class="fa fa-hand-paper-o" aria-hidden="true"></i> Brake
                                                            Assist
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="powersteering" name="powersteering"
                                                            value="1">
                                                        <label for="powersteering">
                                                            <i class="fa fa-automobile" aria-hidden="true"></i> Power
                                                            Steering
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="driverairbag" name="driverairbag"
                                                            value="1">
                                                        <label for="driverairbag">
                                                            <i class="fa fa-user-circle" aria-hidden="true"></i> Driver
                                                            Airbag
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="passengerairbag"
                                                            name="passengerairbag" value="1">
                                                        <label for="passengerairbag">
                                                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                                            Passenger Airbag
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="powerwindow" name="powerwindow"
                                                            value="1">
                                                        <label for="powerwindow">
                                                            <i class="fa fa-window-maximize" aria-hidden="true"></i>
                                                            Power Windows
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="cdplayer" name="cdplayer" value="1">
                                                        <label for="cdplayer">
                                                            <i class="fa fa-music" aria-hidden="true"></i> CD Player
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="centrallocking" name="centrallocking"
                                                            value="1">
                                                        <label for="centrallocking">
                                                            <i class="fa fa-unlock-alt" aria-hidden="true"></i> Central
                                                            Locking
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="crashcensor" name="crashcensor"
                                                            value="1">
                                                        <label for="crashcensor">
                                                            <i class="fa fa-exclamation-triangle"
                                                                aria-hidden="true"></i> Crash Sensor
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" id="leatherseats" name="leatherseats"
                                                            value="1">
                                                        <label for="leatherseats">
                                                            <i class="fa fa-couch" aria-hidden="true"></i> Leather Seats
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-12 text-center">
                                                    <button class="btn btn-default" type="reset">Cancel</button>
                                                    <button class="btn btn-primary" name="submit" type="submit">Save
                                                        Changes</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
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