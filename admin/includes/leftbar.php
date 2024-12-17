<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$username = isset($_SESSION['alogin']) ? $_SESSION['alogin'] : 'Admin';
?>

<nav class="ts-sidebar">
	<ul class="ts-sidebar-menu" style=" font-family: Arial, sans-serif;">

		<li class="ts-label">Home</li>

		<li><a href="dashboard.php"></i>Dashboard</a></li>

		<?php if (strtolower($username) === 'admin') { ?>
		<li><a href="#">User Management</a>
			<ul>
				<li><a href="manage-staff.php"></i>Manage Staffs</a></li>
				<li><a href="reg-users.php"> Registered Users</a></li>
				<li><a href="manage-userlogs.php">Logged History</a></li>
			</ul>
		</li>
		<?php }?>

		<li class="ts-label">Business Management</li>

		<li><a href="#">Driver</a>
			<ul>
				<li><a href="manage-driver.php"></i> Manage Driver</a></li>
				<li><a href="add-driver.php">Add Driver</a></li>
			</ul>
		</li>

		<li><a href="#">Booking</a>
			<ul>
				<li><a href="manage-bookings.php"></i> Manage Booking</a></li>
				<li><a href="calendar.php">Schedule</a></li>
				<li><a href="manage-archive-bookings.php">Archived Booking</a></li>
			</ul>
		</li>
		<li><a href="#"></i> Brands</a>
			<ul>
				<li><a href="create-brand.php">Add Brands</a></li>
				<li><a href="manage-brands.php">Manage Brands</a></li>
			</ul>

		<li><a href="#">Vehicles</a>
			<ul>

				<li><a href="manage-vehicles.php">All Vehicles</a></li>
				<li><a href="manage-inspection.php">All Inspection</a>
				<li><a href="manage-archived-vehicles.php">All Deleted Vehicles</a></li>
		</li>
	</ul>
	</li>
	<li><a href="chatting.php">Client Inquiries</a></li>






	<li class="ts-label">System Setup</li>
	<li><a href="#"></i> Car Type</a>
		<ul>
			<li><a href="create-cartype.php">Add Car Type</a></li>
			<li><a href="manage-cartype.php">Manage Car Type</a></li>
		</ul>
	</li>
	<li><a href="#"></i> Inspection Type</a>
		<ul>
			<li><a href="manage-inspection-type.php">Manage Inspection Type</a></li>
		</ul>
	</li>
	<li><a href="#"></i> Booking Type</a>
		<ul>
			<li><a href="manage-places.php">Places</a></li>
		</ul>
	</li>

	<li class="ts-label">System Settings</li>

	<?php if (strtolower($username) === 'admin') { ?>
	<li><a href="#">Settings</a>
		<ul>
			<li><a href="#"></i>Contact Info</a>
				<ul>
					<li><a href="admin-contact.php">Manage Admin Contact Info</a></li>
					<li><a href="staff-contact.php">Manage Staff Contact Info</a></li>
				</ul>
			</li>

			<li><a href="#">Banner</a>
				<ul>
					<li><a href="post-abanner.php">Post a banner</a></li>
					<li><a href="manage-banner.php">Manage banner</a></li>
				</ul>
			</li>
			<li><a href="#"></i> Terms and Conditions</a>
				<ul>
					<li><a href="manage-tcrenting.php">Manage T&C for Renting</a></li>
					<li><a href="manage-tcwdriver.php">Manage T&C with Driver</a></li>
					<li><a href="manage-tcwodriver.php">Manage T&C without Driver</a></li>
					<li><a href="manage-requirements.php">Manage T&C Requirements</a></li>
				</ul>
			</li>
			<li><a href="add.php">Gallery</a></li>
			<li><a href="testimonials.php"> Manage Testimonials</a></li>
			<li><a href="manage-conactusquery.php">View Contact Us Query</a></li>

		</ul>
	</li>
	<?php } ?>
	<li><a href="#"></i>Report</a>
		<ul>
			<li><a href="reportbooking.php">Booking History</a></li>
		</ul>
		</ul>
</nav>