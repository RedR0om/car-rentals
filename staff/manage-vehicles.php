<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	if (isset($_POST['deleteVehicle'])) {
		$vehicleId = intval($_POST['vehicleId']);
		$remarks = trim($_POST['remarks']); // Get the remarks input

		try {
			// Start transaction
			$dbh->beginTransaction();

			// Copy the vehicle record to the archive table with remarks
			$archiveSql = "INSERT INTO tblvehicles_archive (id, VehiclesTitle, plate, CarType, VehiclesBrand, VehiclesOverview, FuelType, 
                           ModelYear, SeatingCapacity, Transaction_Type, Value, Segment, Contract_Duration, PricePerDay, Vimage1, Vimage2, 
                           Vimage3, Vimage4, Vimage5, AirConditioner, PowerDoorLocks, AntiLockBrakingSystem, BrakeAssist, PowerSteering, 
                           DriverAirbag, PassengerAirbag, PowerWindows, CDPlayer, CentralLocking, CrashSensor, LeatherSeats, RegDate, 
                           UpdationDate, status, TypeCar, deleted_at, remarks)
                           SELECT id, VehiclesTitle, plate, CarType, VehiclesBrand, VehiclesOverview, FuelType, ModelYear, SeatingCapacity, 
                           Transaction_Type, Value, Segment, Contract_Duration, PricePerDay, Vimage1, Vimage2, Vimage3, Vimage4, Vimage5, 
                           AirConditioner, PowerDoorLocks, AntiLockBrakingSystem, BrakeAssist, PowerSteering, DriverAirbag, PassengerAirbag, 
                           PowerWindows, CDPlayer, CentralLocking, CrashSensor, LeatherSeats, RegDate, UpdationDate, status, TypeCar, 
                           NOW(), :remarks
                           FROM tblvehicles WHERE id = :vehicleId";

			$archiveQuery = $dbh->prepare($archiveSql);
			$archiveQuery->bindParam(':vehicleId', $vehicleId, PDO::PARAM_INT);
			$archiveQuery->bindParam(':remarks', $remarks, PDO::PARAM_STR);
			$archiveQuery->execute();

			// Check if the archive was successful
			if ($archiveQuery->rowCount() > 0) {
				// Delete the vehicle record from the original table
				$deleteSql = "DELETE FROM tblvehicles WHERE id = :vehicleId";
				$deleteQuery = $dbh->prepare($deleteSql);
				$deleteQuery->bindParam(':vehicleId', $vehicleId, PDO::PARAM_INT);
				$deleteQuery->execute();

				$dbh->commit(); // Commit the transaction

				$msg = "Vehicle archived and deleted successfully.";
			} else {
				$dbh->rollBack(); // Rollback if archiving failed
				$error = "Failed to archive the vehicle.";
			}
		} catch (Exception $e) {
			$dbh->rollBack(); // Rollback in case of any error
			$error = "Error: " . $e->getMessage();
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

		<title>Car Rental Portal |Staff Manage Vehicles </title>

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

							<h2 class="page-title">Manage Vehicles</h2>
							<div style="margin-bottom: 20px; font-size: 12px;">
								<a href="post-avehical.php" class="btn btn-primary">Add Vehicle</a>
							</div>

							<!-- Zero Configuration Table -->
							<div class="panel panel-default">
								<div class="panel-heading">Vehicle Details</div>
								<div class="panel-body">
									<?php if ($error) { ?>
										<div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
									<?php } else if ($msg) { ?>
											<div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
									<?php } ?>
									<table id="zctb" class="display table table-striped table-bordered table-hover"
										cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>#</th>
												<th>Vehicle Title</th>
												<th>Plate Number </th>
												<th>Brand </th>
												<th>Price Per day</th>
												<th>Fuel Type</th>
												<th>Model Year</th>
												<th>Action</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>#</th>
												<th>Vehicle Title</th>
												<th>Plate Number </th>
												<th>Brand </th>
												<th>Price Per day</th>
												<th>Fuel Type</th>
												<th>Model Year</th>
												<th>Action</th>
											</tr>
											</tr>
										</tfoot>
										<tbody>

											<?php $sql = "SELECT tblvehicles.VehiclesTitle,tblvehicles.plate,tblbrands.BrandName,tblvehicles.PricePerDay,tblvehicles.FuelType,tblvehicles.ModelYear,tblvehicles.id from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand";
											$query = $dbh->prepare($sql);
											$query->execute();
											$results = $query->fetchAll(PDO::FETCH_OBJ);
											$cnt = 1;
											if ($query->rowCount() > 0) {
												foreach ($results as $result) { ?>
													<tr>
														<td><?php echo htmlentities($cnt); ?></td>
														<td><?php echo htmlentities($result->VehiclesTitle); ?></td>
														<td><?php echo htmlentities($result->plate); ?></td>
														<td><?php echo htmlentities($result->BrandName); ?></td>
														<td>₱<?php echo htmlentities($result->PricePerDay); ?></td>
														<td><?php echo htmlentities($result->FuelType); ?></td>
														<td><?php echo htmlentities($result->ModelYear); ?></td>
														<td><a href="edit-vehicle.php?id=<?php echo $result->id; ?>"
																class="btn btn-primary">Edit</a>&nbsp;&nbsp;
															<a href="#" onclick="openDeleteModal(<?php echo $result->id; ?>)"
																class="btn btn-danger">Delete</a>
														</td>
													</tr>
													<?php $cnt = $cnt + 1;
												}
											} ?>

										</tbody>
									</table>



								</div>
							</div>



						</div>
					</div>

				</div>
			</div>
		</div>
		<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form method="post" action="manage-vehicles.php">
						<div class="modal-header">
							<h5 class="modal-title">Delete Vehicle</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<input type="hidden" name="vehicleId" id="vehicleId">
							<div class="form-group">
								<label for="remarks">Remarks (reason for deletion):</label>
								<textarea class="form-control" name="remarks" id="remarks" rows="3" required></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" name="deleteVehicle" class="btn btn-danger">Delete</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script>
			function openDeleteModal(vehicleId) {
				document.getElementById('vehicleId').value = vehicleId;
				$('#deleteModal').modal('show');
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
	</body>

	</html>
<?php } ?>