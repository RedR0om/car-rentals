<?php
session_start();
error_reporting(E_ALL);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

if (isset($_GET['id'])) {
    $vehicle_id = $_GET['id'];

    // Fetch existing vehicle details based on the vehicle ID
    $sql = "SELECT * FROM tblvehicles WHERE id = :vehicle_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':vehicle_id', $vehicle_id, PDO::PARAM_INT);
    $query->execute();
    $vehicle = $query->fetch(PDO::FETCH_ASSOC);

    if (!$vehicle) {
        echo "Vehicle not found.";
        exit;
    }

    if (isset($_POST['submit'])) {
        // Collect and update form data
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

        // Handle image updates (use current images if not updated)
        $vimage1 = $_FILES["img1"]["name"] ? $_FILES["img1"]["name"] : $_POST['current_img1'];
        $vimage2 = $_FILES["img2"]["name"] ? $_FILES["img2"]["name"] : $vehicle['Vimage2'];
        $vimage3 = $_FILES["img3"]["name"] ? $_FILES["img3"]["name"] : $vehicle['Vimage3'];
        $vimage4 = $_FILES["img4"]["name"] ? $_FILES["img4"]["name"] : $vehicle['Vimage4'];
        $vimage5 = $_FILES["img5"]["name"] ? $_FILES["img5"]["name"] : $vehicle['Vimage5'];

        // Handle accessory options
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
            // Check if the plate number already exists for other vehicles
            $sql = "SELECT * FROM tblvehicles WHERE plate = :plate AND id != :vehicle_id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':plate', $plate, PDO::PARAM_STR);
            $query->bindParam(':vehicle_id', $vehicle_id, PDO::PARAM_INT);
            $query->execute();

            if ($query->fetch(PDO::FETCH_ASSOC)) {
                $error = "A vehicle with the same plate number already exists.";
            } else {
                // Handle image uploads if updated
                for ($i = 1; $i <= 5; $i++) {
                    if (!empty($_FILES["img$i"]["name"])) {
                        $targetPath = "img/vehicleimages/" . basename($_FILES["img$i"]["name"]);
                        if (move_uploaded_file($_FILES["img$i"]["tmp_name"], $targetPath)) {
                            ${"vimage$i"} = $_FILES["img$i"]["name"];
                        } else {
                            throw new Exception("Failed to upload image $i.");
                        }
                    }
                }

                // Update vehicle data in the database
                $sql = "UPDATE tblvehicles 
                        SET VehiclesTitle = :vehicletitle, plate = :plate, CarType = :cartype, 
                        VehiclesBrand = :brand, VehiclesOverview = :vehicleoverview, 
                        PricePerDay = :priceperday, FuelType = :fueltype, ModelYear = :modelyear, 
                        SeatingCapacity = :seatingcapacity, Vimage1 = :vimage1, Vimage2 = :vimage2, 
                        Vimage3 = :vimage3, Vimage4 = :vimage4, Vimage5 = :vimage5, 
                        AirConditioner = :airconditioner, PowerDoorLocks = :powerdoorlocks, 
                        AntiLockBrakingSystem = :antilockbrakingsys, BrakeAssist = :brakeassist, 
                        PowerSteering = :powersteering, DriverAirbag = :driverairbag, 
                        PassengerAirbag = :passengerairbag, PowerWindows = :powerwindow, 
                        CDPlayer = :cdplayer, CentralLocking = :centrallocking, 
                        CrashSensor = :crashcensor, LeatherSeats = :leatherseats, 
                        TypeCar = :cartype_name 
                        WHERE id = :vehicle_id";

                $query = $dbh->prepare($sql);
                // Bind parameters (including images dynamically handled above)
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
                $query->bindParam(':vehicle_id', $vehicle_id, PDO::PARAM_INT);

                if ($query->execute()) {
                    $msg = "Vehicle details updated successfully!";
                } else {
                    throw new Exception("Vehicle update failed.");
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
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

	<title>Car Rental Portal | Admin Post Vehicle</title>

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

                        <h2 class="page-title">Edit A Vehicle</h2>

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
											<h2 class="text-center">Edit Vehicle</h2>
											<!-- Form part -->
											<form method="post" class="form-horizontal" enctype="multipart/form-data">
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Vehicle Model<span class="required">*</span></label>
													<div class="col-sm-4">
														<input type="text" name="vehicletitle" class="form-control"
															value="<?php echo htmlentities($vehicle['VehiclesTitle']); ?>" required>
													</div>
													<label class="col-sm-2 col-form-label">Vehicle Plate<span class="required">*</span></label>
													<div class="col-sm-4">
														<input type="text" name="plate" class="form-control"
															value="<?php echo htmlentities($vehicle['plate']); ?>" required>
													</div>
												</div>

												

												<!-- Car Type -->
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Select Fuel Type<span class="required">*</span></label>
													<div class="col-sm-4">
														<select class="form-control" name="fueltype" required style="background:#3e3f3a; color: white;">
															<option value="">Select</option>
															<option value="Gas97" <?php echo ($vehicle['FuelType'] == 'Gas97') ? 'selected' : ''; ?>>euro5 GAS 97</option>
															<option value="Gas95" <?php echo ($vehicle['FuelType'] == 'Gas95') ? 'selected' : ''; ?>>euro5 GAS 95</option>
															<option value="Gas91" <?php echo ($vehicle['FuelType'] == 'Gas91') ? 'selected' : ''; ?>>euro5 GAS 91</option>
															<option value="Petrol" <?php echo ($vehicle['FuelType'] == 'Petrol') ? 'selected' : ''; ?>>Petrol</option>
															<option value="Diesel" <?php echo ($vehicle['FuelType'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
															<option value="CNG" <?php echo ($vehicle['FuelType'] == 'CNG') ? 'selected' : ''; ?>>CNG</option>
														</select>
													</div>
											

													<label class="col-sm-2 col-form-label">Select Brand<span style="color:red">*</span></label>
													<div class="col-sm-4">
														<select class="form-control" name="brandname" style="background:#3e3f3a; color: white;">
															<?php
															// Get the brand name using the VehiclesBrand ID
															$brandId = $vehicle['VehiclesBrand'];
															$ret = "SELECT id, BrandName FROM tblbrands WHERE id = :brandId";
															$query = $dbh->prepare($ret);
															$query->bindParam(':brandId', $brandId, PDO::PARAM_INT);
															$query->execute();
															$brand = $query->fetch(PDO::FETCH_ASSOC);
															?>
															<option value="<?php echo htmlentities($brand['id']); ?>">
																<?php echo htmlentities($brand['BrandName']); ?>
															</option>

															<?php
															// Get all other brands for selection
															$ret = "SELECT id, BrandName FROM tblbrands";
															$query = $dbh->prepare($ret);
															$query->execute();
															$brands = $query->fetchAll(PDO::FETCH_OBJ);

															foreach ($brands as $brand) {
																if ($brand->id != $vehicle['VehiclesBrand']) {
																	?>
																	<option value="<?php echo htmlentities($brand->id); ?>">
																		<?php echo htmlentities($brand->BrandName); ?>
																	</option>
																	<?php
																}
															}
															?>
														</select>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Car Type<span class="required">*</span></label>
													<div class="col-sm-4">
														<select class="form-control" name="cartype_name" required style="background:#3e3f3a; color: white;">
															<option value="">Select</option>
															<?php
															// Fetch car types from the tblcartype table
															$ret = "SELECT cartype_id, cartype_name FROM tblcartype";
															$query = $dbh->prepare($ret);
															$query->execute();
															$results = $query->fetchAll(PDO::FETCH_OBJ);

															// Check if any results were returned
															if ($query->rowCount() > 0) {
																// Loop through each result and display as an option
																foreach ($results as $result) {
																	?>
																	<option value="<?php echo htmlentities($result->cartype_id); ?>" <?php echo ($result->cartype_id == $vehicle['CarType']) ? 'selected' : ''; ?>>
																		<?php echo htmlentities($result->cartype_name); ?>
																	</option>
																	<?php
																}
															}
															?>
														</select>
													</div>

													<label class="col-sm-2 col-form-label">Select Vehicle Type<span style="color:red">*</span></label>
													<div class="col-sm-4">
														<select class="form-control" name="cartype" required style="background:#3e3f3a; color: white;">
															<option value="<?php echo htmlentities($vehicle['CarType']); ?>">
																<?php echo htmlentities($vehicle['CarType']); ?>
															</option>
															<option value="">Select</option>
															<option value="Manual">Manual</option>
															<option value="Automatic">Automatic</option>
															<option value="CVT">CVT</option>
														</select>
													</div>
												</div>
												<div class="hr-dashed"></div>
												<div class="form-group">
													<label class="col-sm-2 col-form-label">Vehicle Overview<span style="color:red">*</span></label>
													<div class="col-sm-10">
														<textarea class="form-control" name="vehicalorcview"
															rows="3"><?php echo htmlentities($vehicle['VehiclesOverview']); ?></textarea>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Price Per Day (in PHP)<span style="color:red">*</span></label>
													<div class="col-sm-4">
														<input type="text" name="priceperday" class="form-control"
															value="<?php echo htmlentities($vehicle['PricePerDay']); ?>">
													</div>
												
													<label class="col-sm-2 col-form-label">Model Year<span style="color:red">*</span></label>
													<div class="col-sm-4">
														<input type="text" name="modelyear" class="form-control"
															value="<?php echo htmlentities($vehicle['ModelYear']); ?>" required>
													</div>
												</div>

												<!-- Seating Capacity Input -->
												<div class="form-group">
													<label class="col-sm-2 col-form-label">Seating Capacity<span style="color:red">*</span></label>
													<div class="col-sm-4">
														<input type="text" name="seatingcapacity" class="form-control"
															value="<?php echo htmlentities($vehicle['SeatingCapacity']); ?>" required>
													</div>
												</div>
												<div class="hr-dashed"></div>
												<!-- Display Vehicle Images -->
												<div class="form-group">
													<div class="col-sm-4">
														<label>Main Image</label><br>
														<img src="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage1']); ?>" width="350" height="200" style="border:solid 1px #000">
														<input type="file" name="img1" class="form-control" style="width:350px;">
														<!-- Hidden input to retain current image -->
														<input type="hidden" name="current_img1" value="<?php echo htmlentities($vehicle['Vimage1']); ?>">
													</div>
													<div class="col-sm-4">
														<label>Front Image</label><br>
														<img src="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage2']); ?>" width="350" height="200"
															style="border:solid 1px #000">
														<input type="file" name="img2" class="form-control" style="width:350px;" value="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage2']); ?>">
													</div>
													<div class="col-sm-4">
														<label>Back Image</label><br>
														<img src="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage3']); ?>" width="350" height="200"
															style="border:solid 1px #000">
														<input type="file" name="img3" class="form-control" style="width:350px;" value="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage3']); ?>">

													</div>
												</div>

												<!-- Display Vehicle Images -->
												<div class="form-group">
													<div class="col-sm-4">
														<label>Side Image</label><br>
														<img src="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage4']); ?>" width="350" height="200"
															style="border:solid 1px #000">
														<input type="file" name="img4" class="form-control" style="width:350px;" value="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage4']); ?>">

													</div>
													<div class="col-sm-4">
														<label>Other Side Image</label><br>
														<img src="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage5']); ?>" width="350" height="200"
															style="border:solid 1px #000">
														<input type="file" name="img5" class="form-control" style="width:350px;" value="img/vehicleimages/<?php echo htmlentities($vehicle['Vimage5']); ?>">

													</div>
												</div>
												<div class="hr-dashed"></div>
												<div class="row">
													<div class="col-md-12">
														<div class="panel panel-default">
															<div class="panel-heading">Accessories</div>
															<div class="panel-body">
																<?php

																// Set up database connection parameters
																$host = 'ballast.proxy.rlwy.net:35637'; // Change as needed
																$dbname = 'carrental'; // Change to your database name
																$username = 'BobDdBAPBobrKyzYicQYaJhDpujZqoKa'; // Your database username
																$password = 'railway'; // Your database password
																
																try {
																	// Establish the PDO connection
																	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
																	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
																} catch (PDOException $e) {
																	// If the connection fails, catch the exception and display an error message
																	echo "Connection failed: " . $e->getMessage();
																}

																$query = "SELECT AirConditioner, PowerDoorLocks, AntiLockBrakingSystem, BrakeAssist, 
																PowerSteering, DriverAirbag, PassengerAirbag, Powerwindows, CDPlayer, 
																CentralLocking, CrashSensor, LeatherSeats
																FROM tblvehicles WHERE id = :id";
																$stmt = $pdo->prepare($query);
																$stmt->bindParam(':id', $vehicle_id, PDO::PARAM_INT);
																$stmt->execute();
																$result = $stmt->fetch(PDO::FETCH_OBJ);
																?>

																<!-- Air Conditioner -->
																<div class="form-group">
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1" name="airconditioner" value="1" <?php if ($result->AirConditioner == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox1">Air Conditioner</label>
																		</div>
																	</div>

																	<!-- Power Door Locks -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox2" name="powerdoorlocks" value="1" <?php if ($result->PowerDoorLocks == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox2">Power Door Locks</label>
																		</div>
																	</div>

																	<!-- AntiLock Braking System -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox3" name="antilockbrakingsys" value="1"
																				<?php if ($result->AntiLockBrakingSystem == 1)
																					echo "checked"; ?>>
																			<label for="inlineCheckbox3">AntiLock Braking System</label>
																		</div>
																	</div>

																	<!-- Brake Assist -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox4" name="brakeassist" value="1" <?php if ($result->BrakeAssist == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox4">Brake Assist</label>
																		</div>
																	</div>
																</div>

																<!-- Power Steering -->
																<div class="form-group">
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox5" name="PowerSteering" value="1" <?php if ($result->PowerSteering == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox5">Power Steering</label>
																		</div>
																	</div>

																	<!-- Driver Airbag -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox6" name="DriverAirbag" value="1" <?php if ($result->DriverAirbag == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox6">Driver Airbag</label>
																		</div>
																	</div>

																	<!-- Passenger Airbag -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox7" name="PassengerAirbag" value="1" <?php if ($result->PassengerAirbag == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox7">Passenger Airbag</label>
																		</div>
																	</div>

																	<!-- Power Windows -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox8" name="powerwindow" value="1" <?php if ($result->Powerwindows == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox8">Power Windows</label>
																		</div>
																	</div>
																</div>

																<!-- CD Player -->
																<div class="form-group">
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox9" name="cdplayer" value="1" <?php if ($result->CDPlayer == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox9">CD Player</label>
																		</div>
																	</div>

																	<!-- Central Locking -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox10" name="centrallocking" value="1" <?php if ($result->CentralLocking == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox10">Central Locking</label>
																		</div>
																	</div>

																	<!-- Crash Sensor -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox11" name="crashcensor" value="1" <?php if ($result->CrashSensor == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox11">Crash Sensor</label>
																		</div>
																	</div>

																	<!-- Leather Seats -->
																	<div class="col-sm-3">
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox12" name="leatherseats" value="1" <?php if ($result->LeatherSeats == 1)
																				echo "checked"; ?>>
																			<label for="inlineCheckbox12">Leather Seats</label>
																		</div>
																	</div>
																</div>

															</div>
														</div>
													</div>
												</div>


												<!-- Add additional fields as needed -->
												<button type="submit" name="submit" class="btn btn-primary">Update Vehicle</button>
											</form>

											<?php if (isset($error)) {
												echo '<div class="error">' . $error . '</div>';
											} ?>
											<?php if (isset($msg)) {
												echo '<div class="success">' . $msg . '</div>';
											} ?>
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