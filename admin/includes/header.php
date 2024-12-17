<?php
session_start();
$username = isset($_SESSION['alogin']) ? $_SESSION['alogin'] : 'Admin';
?>

<div class="brand clearfix">
	<div class="logo">
		<img src="img/logo.png" alt="">
	</div>
	<a href="dashboard.php" style="font-size: 30px; font-family: Arial, sans-serif; color:red; position: absolute; margin-top: 10px; margin-left: -200px;">T.M.T.C.R.S</a>  
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		<ul class="ts-profile-nav">
			
			<li class="ts-account">
				<a href="#"><img src="admin.jpg" class="ts-avatar hidden-side" alt="">
				<?php echo htmlspecialchars($username); ?> 
				<i class="fa fa-angle-down hidden-side"></i></a>
				<ul>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>

		</ul>
	</div>
