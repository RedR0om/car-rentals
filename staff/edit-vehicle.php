<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {

	if (isset($_POST['submit'])) {
		$vehicletitle = $_POST['vehicletitle'];
		$plate = $_POST['plate'];
		$cartype = $_POST['cartype'];
		$carspecs = $_POST['carspecs'];
		$brand = $_POST['brandname'];
		$vehicleoverview = $_POST['vehicleoverview'];
		$priceperday = $_POST['priceperday'];
		$fueltype = $_POST['fueltype'];
		$modelyear = $_POST['modelyear'];
		$seatingcapacity = $_POST['seatingcapacity'];
		$airconditioner = $_POST['airconditioner'];
		$powerdoorlocks = $_POST['powerdoorlocks'];
		$antilockbrakingsys = $_POST['antilockbrakingsys'];
		$brakeassist = $_POST['brakeassist'];
		$powersteering = $_POST['powersteering'];
		$driverairbag = $_POST['driverairbag'];
		$passengerairbag = $_POST['passengerairbag'];
		$powerwindow = $_POST['powerwindow'];
		$cdplayer = $_POST['cdplayer'];
		$centrallocking = $_POST['centrallocking'];
		$crashcensor = $_POST['crashcensor'];
		$leatherseats = $_POST['leatherseats'];
		$id = intval($_GET['id']);

		$sql = "update tblvehicles set VehiclesTitle=:vehicletitle,plate=:plate,carType=:cartype,carspecs=:carspecs,VehiclesBrand=:brand,VehiclesOverview=:vehicleoverview,PricePerDay=:priceperday,FuelType=:fueltype,ModelYear=:modelyear,SeatingCapacity=:seatingcapacity,AirConditioner=:airconditioner,PowerDoorLocks=:powerdoorlocks,AntiLockBrakingSystem=:antilockbrakingsys,BrakeAssist=:brakeassist,PowerSteering=:powersteering,DriverAirbag=:driverairbag,PassengerAirbag=:passengerairbag,PowerWindows=:powerwindow,CDPlayer=:cdplayer,CentralLocking=:centrallocking,CrashSensor=:crashcensor,LeatherSeats=:leatherseats where id=:id ";
		$query = $dbh->prepare($sql);
		$query->bindParam(':vehicletitle', $vehicletitle, PDO::PARAM_STR);
		$query->bindParam(':plate', $plate, PDO::PARAM_STR);
		$query->bindParam(':cartype', $cartype, PDO::PARAM_STR);
		$query->bindParam(':carspecs', $carspecs, PDO::PARAM_STR);
		$query->bindParam(':brand', $brand, PDO::PARAM_STR);
		$query->bindParam(':vehicleoverview', $vehicleoverview, PDO::PARAM_STR);
		$query->bindParam(':priceperday', $priceperday, PDO::PARAM_STR);
		$query->bindParam(':fueltype', $fueltype, PDO::PARAM_STR);
		$query->bindParam(':modelyear', $modelyear, PDO::PARAM_STR);
		$query->bindParam(':seatingcapacity', $seatingcapacity, PDO::PARAM_STR);
		$query->bindParam(':airconditioner', $airconditioner, PDO::PARAM_STR);
		$query->bindParam(':powerdoorlocks', $powerdoorlocks, PDO::PARAM_STR);
		$query->bindParam(':antilockbrakingsys', $antilockbrakingsys, PDO::PARAM_STR);
		$query->bindParam(':brakeassist', $brakeassist, PDO::PARAM_STR);
		$query->bindParam(':powersteering', $powersteering, PDO::PARAM_STR);
		$query->bindParam(':driverairbag', $driverairbag, PDO::PARAM_STR);
		$query->bindParam(':passengerairbag', $passengerairbag, PDO::PARAM_STR);
		$query->bindParam(':powerwindow', $powerwindow, PDO::PARAM_STR);
		$query->bindParam(':cdplayer', $cdplayer, PDO::PARAM_STR);
		$query->bindParam(':centrallocking', $centrallocking, PDO::PARAM_STR);
		$query->bindParam(':crashcensor', $crashcensor, PDO::PARAM_STR);
		$query->bindParam(':leatherseats', $leatherseats, PDO::PARAM_STR);
		$query->bindParam(':id', $id, PDO::PARAM_STR);
		$query->execute();

		$lastInsertId = $dbh->lastInsertId();
		$msg = "Data updated successfully";


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

		<title>Car Rental Portal | Staff Edit Vehicle Info</title>

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

							<h2 class="page-title">Edit Vehicle</h2>

							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading">Basic Info</div>
										<div class="panel-body">
											<?php if ($msg) { ?>
												<div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
												</div><?php } ?>
											<?php
											$id = $_GET['id'];
											$sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.id=:id";
											$query = $dbh->prepare($sql);
											$query->bindParam(':id', $id, PDO::PARAM_STR);
											$query->execute();
											$results = $query->fetchAll(PDO::FETCH_OBJ);
											$cnt = 1;
											if ($query->rowCount() > 0) {
												foreach ($results as $result) { ?>

													<form method="post" class="form-horizontal" enctype="multipart/form-data">

														<div class="form-group">
															<label class="col-sm-2 control-label">Vehicle Title<span
																	style="color:red">*</span></label>
															<div class="col-sm-4">
																<input type="text" name="vehicletitle" class="form-control"
																	value="<?php echo htmlentities($result->VehiclesTitle) ?>">
															</div>
															<div class="hr-dashed"></div>



															<div class="form-group">
																<label class="col-sm-2 control-label">Plate Number<span
																		style="color:red">*</span></label>
																<div class="col-sm-4">
																	<input type="text" name="plate" maxlength="6" minlength="6"
																		class="form-control "
																		value="<?php echo htmlentities($result->plate) ?>" required>

																</div>
																<label class="col-sm-2 control-label">Select Brand<span
																		style="color:red">*</span></label>
																<div class="col-sm-4">
																	<select class="selectpicker" name="brandname">
																		<option value="<?php echo htmlentities($result->bid); ?>">
																			<?php echo htmlentities($bdname = $result->BrandName); ?>
																		</option>
																		<?php $ret = "select id,BrandName from tblbrands";
																		$query = $dbh->prepare($ret);
																		//$query->bindParam(':id',$id, PDO::PARAM_STR);
																		$query->execute();
																		$resultss = $query->fetchAll(PDO::FETCH_OBJ);
																		if ($query->rowCount() > 0) {
																			foreach ($resultss as $results) {
																				if ($results->BrandName == $bdname) {
																					continue;
																				} else {
																					?>
																					<option value="<?php echo htmlentities($results->id); ?>">
																						<?php echo htmlentities($results->BrandName); ?></option>
																				<?php }
																			}
																		} ?>

																	</select>
																</div>
															</div>

															<div class="hr-dashed"></div>
															<div class="form-group">
																<label class="col-sm-2 control-label">Vehicle Overview<span
																		style="color:red">*</span></label>
																<div class="col-sm-10">
																	<textarea class="form-control" name="vehicleoverview"
																		rows="3"><?php echo htmlentities($result->VehiclesOverview); ?></textarea>
																</div>
															</div>

															<div class="form-group">
																<label class="col-sm-2 control-label">Price Per Day(in PHP)<span
																		style="color:red">*</span></label>
																<div class="col-sm-4">
																	<input type="text" name="priceperday" class="form-control"
																		value="<?php echo htmlentities($result->PricePerDay); ?>">
																</div>
																<label class="col-sm-2 control-label">Select Fuel Type<span
																		style="color:red">*</span></label>
																<div class="col-sm-4">
																	<select class="selectpicker" name="fueltype">
																		<option
																			value="<?php echo htmlentities($results->FuelType); ?>">
																			<?php echo htmlentities($result->FuelType); ?> </option>
																		<option value="Gas97">euro5 GAS 97</option>
																		<option value="Gas95">euro5 GAS 95</option>
																		<option value="Gas91">euro5 GAS 91</option>
																		<option value="Petrol">Petrol</option>
																		<option value="Diesel">Diesel</option>
																		<option value="CNG">CNG</option>
																	</select>
																</div>
															</div>

															<div class="form-group">

																<label class="col-sm-2 control-label">Select Vehicle Type<span
																		style="color:red">*</span></label>
																<div class="col-sm-4">
																	<select class="selectpicker" name="cartype" required>
																		<option
																			value="<?php echo htmlentities($results->carType); ?>">
																			<?php echo htmlentities($result->carType); ?> </option>
																		<option value=""> Select </option>
																		<option value="Manual">Manual</option>
																		<option value="Automatic">Automatic</option>
																		<option value="CVT">CVT</option>
																	</select>
																</div>
																<label class="col-sm-2 control-label"
																	style="width: 200px; margin-right: -1.5%;">Select Car
																	Transimission<span style="color:red">*</span></label>
																<div class="col-sm-4"
																	style="width: 200px; float: left; padding-right: 10px;">
																	<select class="selectpicker" name="carspecs" required>
																		<option
																			value="<?php echo htmlentities($results->carspecs); ?>">
																			<?php echo htmlentities($result->carspecs); ?> </option>
																		<option value=""> Select </option>

																		<option value="Standard">Standard</option>
																		<option value="Convertible">Convertible</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-2 control-label">Model Year<span
																		style="color:red">*</span></label>
																<div class="col-sm-4">
																	<input type="text" name="modelyear" class="form-control"
																		value="<?php echo htmlentities($result->ModelYear); ?>">
																</div>
																<label class="col-sm-2 control-label">Seating Capacity<span
																		style="color:red">*</span></label>
																<div class="col-sm-4">
																	<input type="text" name="seatingcapacity" class="form-control"
																		value="<?php echo htmlentities($result->SeatingCapacity); ?>">
																</div>
															</div>
															<div class="hr-dashed"></div>
															<div class="form-group">
																<div class="col-sm-12">
																	<h4><b>Vehicle Images</b></h4>
																</div>
															</div>


															<div class="form-group">
																<div class="col-sm-4">
																	Image 1 <img
																		src="img/vehicleimages/<?php echo htmlentities($result->Vimage1); ?>"
																		width="300" height="200" style="border:solid 1px #000">
																	<a
																		href="changeimage1.php?imgid=<?php echo htmlentities($result->id) ?>">Change
																		Image 1</a>
																</div>
																<div class="col-sm-4">
																	Image 2<img
																		src="img/vehicleimages/<?php echo htmlentities($result->Vimage2); ?>"
																		width="300" height="200" style="border:solid 1px #000">
																	<a
																		href="changeimage2.php?imgid=<?php echo htmlentities($result->id) ?>">Change
																		Image 2</a>
																</div>
																<div class="col-sm-4">
																	Image 3<img
																		src="img/vehicleimages/<?php echo htmlentities($result->Vimage3); ?>"
																		width="300" height="200" style="border:solid 1px #000">
																	<a
																		href="changeimage3.php?imgid=<?php echo htmlentities($result->id) ?>">Change
																		Image 3</a>
																</div>
															</div>


															<div class="form-group">
																<div class="col-sm-4">
																	Image 4<img
																		src="img/vehicleimages/<?php echo htmlentities($result->Vimage4); ?>"
																		width="300" height="200" style="border:solid 1px #000">
																	<a
																		href="changeimage4.php?imgid=<?php echo htmlentities($result->id) ?>">Change
																		Image 4</a>
																</div>
																<div class="col-sm-4">
																	Image 5
																	<?php if ($result->Vimage5 == "") {
																		echo htmlentities("File not available");
																	} else { ?>
																		<img src="img/vehicleimages/<?php echo htmlentities($result->Vimage5); ?>"
																			width="300" height="200" style="border:solid 1px #000">
																		<a
																			href="changeimage5.php?imgid=<?php echo htmlentities($result->id) ?>">Change
																			Image 5</a>
																	<?php } ?>
																</div>

															</div>
															<div class="hr-dashed"></div>
														</div>
												</div>
											</div>
										</div>




										<div class="row">
											<div class="col-md-12">
												<div class="panel panel-default">
													<div class="panel-heading">Accessories</div>
													<div class="panel-body">


														<div class="form-group">
															<div class="col-sm-3">
																<?php if ($result->AirConditioner == 1) { ?>
																	<div class="checkbox checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1"
																			name="airconditioner" checked value="1">
																		<label for="inlineCheckbox1"> Air Conditioner </label>
																	</div>
																<?php } else { ?>
																	<div class="checkbox checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1"
																			name="airconditioner" value="1">
																		<label for="inlineCheckbox1"> Air Conditioner </label>
																	</div>

																<?php } ?>
															</div>
															<div class="col-sm-3">
																<?php if ($result->PowerDoorLocks == 1) { ?>
																	<div class="checkbox checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1"
																			name="powerdoorlocks" checked value="1">
																		<label for="inlineCheckbox2"> Power Door Locks </label>
																	</div>
																<?php } else { ?>
																	<div class="checkbox checkbox-success checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1"
																			name="powerdoorlocks" value="1">
																		<label for="inlineCheckbox2"> Power Door Locks </label>
																	</div>
																<?php } ?>
															</div>
															<div class="col-sm-3">
																<?php if ($result->AntiLockBrakingSystem == 1) { ?>
																	<div class="checkbox checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1"
																			name="antilockbrakingsys" checked value="1">
																		<label for="inlineCheckbox3"> AntiLock Braking System </label>
																	</div>
																<?php } else { ?>
																	<div class="checkbox checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1"
																			name="antilockbrakingsys" value="1">
																		<label for="inlineCheckbox3"> AntiLock Braking System </label>
																	</div>
																<?php } ?>
															</div>
															<div class="col-sm-3">
																<?php if ($result->BrakeAssist == 1) {
																	?>
																	<div class="checkbox checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1" name="brakeassist"
																			checked value="1">
																		<label for="inlineCheckbox3"> Brake Assist </label>
																	</div>
																<?php } else { ?>
																	<div class="checkbox checkbox-inline">
																		<input type="checkbox" id="inlineCheckbox1" name="brakeassist"
																			value="1">
																		<label for="inlineCheckbox3"> Brake Assist </label>
																	</div>
																<?php } ?>
															</div>

															<div class="form-group">
																<div class="col-sm-3">
																	<?php if ($result->PowerSteering == 1) { ?>
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="PowerSteering" checked value="1">
																			<label for="inlineCheckbox1"> Power Steering </label>
																		</div>
																	<?php } else { ?>
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="PowerSteering" value="1">
																			<label for="inlineCheckbox1"> Power Steering </label>
																		</div>

																	<?php } ?>
																</div>
																<div class="col-sm-3">
																	<?php if ($result->DriverAirbag == 1) { ?>
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="DriverAirbag" checked value="1">
																			<label for="inlineCheckbox2">Driver Airbag</label>
																		</div>
																	<?php } else { ?>
																		<div class="checkbox checkbox-success checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="DriverAirbag" value="1">
																			<label for="inlineCheckbox2">Driver Airbag </label>
																		</div>
																	<?php } ?>
																</div>
																<div class="col-sm-3">
																	<?php if ($result->PassengerAirbag == 1) { ?>
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="PassengerAirbag" checked value="1">
																			<label for="inlineCheckbox3"> Passenger Airbag</label>
																		</div>
																	<?php } else { ?>
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="PassengerAirbag" value="1">
																			<label for="inlineCheckbox3"> Passenger Airbag</label>
																		</div>
																	<?php } ?>
																</div>
																<div class="col-sm-3">
																	<?php if ($result->powerwindow == 1) {
																		?>
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="powerwindow" checked value="1">
																			<label for="inlineCheckbox3">Power Windows </label>
																		</div>
																	<?php } else { ?>
																		<div class="checkbox checkbox-inline">
																			<input type="checkbox" id="inlineCheckbox1"
																				name="powerwindow" value="1">
																			<label for="inlineCheckbox3"> Power Windows </label>
																		</div>
																	<?php } ?>
																</div>


																<div class="form-group">
																	<div class="col-sm-3">
																		<?php if ($result->CDPlayer == 1) {
																			?>
																			<div class="checkbox checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="cdplayer" checked value="1">
																				<label for="inlineCheckbox1"> CD Player </label>
																			</div>
																		<?php } else { ?>
																			<div class="checkbox checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="cdplayer" value="1">
																				<label for="inlineCheckbox1"> CD Player </label>
																			</div>
																		<?php } ?>
																	</div>
																	<div class="col-sm-3">
																		<?php if ($result->CentralLocking == 1) {
																			?>
																			<div class="checkbox  checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="centrallocking" checked value="1">
																				<label for="inlineCheckbox2">Central Locking</label>
																			</div>
																		<?php } else { ?>
																			<div class="checkbox checkbox-success checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="centrallocking" value="1">
																				<label for="inlineCheckbox2">Central Locking</label>
																			</div>
																		<?php } ?>
																	</div>
																	<div class="col-sm-3">
																		<?php if ($result->CrashSensor == 1) {
																			?>
																			<div class="checkbox checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="crashcensor" checked value="1">
																				<label for="inlineCheckbox3"> Crash Sensor </label>
																			</div>
																		<?php } else { ?>
																			<div class="checkbox checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="crashcensor" value="1">
																				<label for="inlineCheckbox3"> Crash Sensor </label>
																			</div>
																		<?php } ?>
																	</div>
																	<div class="col-sm-3">
																		<?php if ($result->CrashSensor == 1) {
																			?>
																			<div class="checkbox checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="leatherseats" checked value="1">
																				<label for="inlineCheckbox3"> Leather Seats </label>
																			</div>
																		<?php } else { ?>
																			<div class="checkbox checkbox-inline">
																				<input type="checkbox" id="inlineCheckbox1"
																					name="leatherseats" value="1">
																				<label for="inlineCheckbox3"> Leather Seats </label>
																			</div>
																		<?php } ?>
																	</div>
																</div>

															<?php }
											} ?>


														<div class="form-group">
															<div class="col-sm-8 col-sm-offset-2">

																<button class="btn btn-primary" name="submit" type="submit"
																	style="margin-top:4%">Save changes</button>
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