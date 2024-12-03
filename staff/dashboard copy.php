<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
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

    <title>Car Rental Portal | Admin Dashboard</title>

 
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
        body {
            background-color: #f7f7f7;
        }

        .card {
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 1px 3px rgb(0 0 0);
            margin: 50px;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .icon{
            display: flex; 
            justify-content: center; 
            align-items: center; 
            margin-top: 10px;
        }

        .title{
            flex-grow: 1; 
            font-size: 14px; 
            font-weight: bold; 
            text-transform: uppercase; 
            color: #004d40;
            text-align: center;
        }

        .icon-style{
            background-color: #e57373; 
            border-radius: 50%; 
            width: 70px; 
            height: 70px; 
            display: flex; 
            justify-content: center; 
            align-items: center;
            margin-top: -210px;
            margin-left: -200px;
            position: absolute;
        }
        .block-anchor{
            text-align: center; 
            display: block; 
            padding: 10px; 
            color: #00796b;
            text-decoration: none;
        }

        .stat-panel-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .stat-panel-number {
            font-size: 32px;
            font-weight: 700;
            color: #e57373;
        }

        .chart-container {
            padding: 20px;
            background: white;
            border-radius: 10px;
            width: 400px;
            display: inline-block;
        }

        /* Better Alignment of Charts */
        .chartjs-size {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Dashboard</h2>
                        
                        <!-- Panels Area -->
                        <div class="row">
                            <!-- Example Panel 1 -->
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                        <?php 
                                            $sql ="SELECT user_id from users ";
                                            $query = $dbh -> prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            $regusers=$query->rowCount();
                                        ?>
                                
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                            <?php echo htmlentities($regusers); ?>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                                Chat Users
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="chat-user.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Manage Detail <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                        <?php 
                                                        $sql ="SELECT id from tblusers ";
                                                        $query = $dbh -> prepare($sql);
                                                        $query->execute();
                                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                        $regusers=$query->rowCount();
                                                        ?>
                                
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                        <?php echo htmlentities($regusers);?>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                           Reg Users
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="reg-users.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Full Detail <i class="fa fa-arrow-left  "></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                    <?php 
                                        $sql2 ="SELECT id from tblbooking ";
                                        $query2= $dbh -> prepare($sql2);
                                        $query2->execute();
                                        $results2=$query2->fetchAll(PDO::FETCH_OBJ);
                                        $bookings=$query2->rowCount();
                                        ?>
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                        <?php echo htmlentities($bookings);?>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                          Total Bookings
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="manage-bookings.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Full Detail <i class="fa fa-arrow-left  "></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                    <?php 
                                        $sql1 ="SELECT id from tblvehicles ";
                                        $query1 = $dbh -> prepare($sql1);;
                                        $query1->execute();
                                        $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                                        $totalvehicle=$query1->rowCount();
                                        ?>
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                        <?php echo htmlentities($totalvehicle);?></div>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                           Total Vehicles
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="manage-vehicles.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Full Detail <i class="fa fa-arrow-left  "></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Example Panel 1 -->
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                        <?php 
                                            $sql ="SELECT user_id from users ";
                                            $query = $dbh -> prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            $regusers=$query->rowCount();
                                        ?>
                                
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                            <?php echo htmlentities($regusers); ?>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                                Chat Users
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="chat-user.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Manage Detail <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                        <?php 
                                                        $sql ="SELECT id from tblusers ";
                                                        $query = $dbh -> prepare($sql);
                                                        $query->execute();
                                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                        $regusers=$query->rowCount();
                                                        ?>
                                
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                        <?php echo htmlentities($regusers);?>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                           Reg Users
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="reg-users.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Full Detail <i class="fa fa-arrow-left  "></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                    <?php 
                                        $sql2 ="SELECT id from tblbooking ";
                                        $query2= $dbh -> prepare($sql2);
                                        $query2->execute();
                                        $results2=$query2->fetchAll(PDO::FETCH_OBJ);
                                        $bookings=$query2->rowCount();
                                        ?>
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                        <?php echo htmlentities($bookings);?>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                          Total Bookings
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="manage-bookings.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Full Detail <i class="fa fa-arrow-left  "></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="stat-panel text-center">
                                    <?php 
                                        $sql1 ="SELECT id from tblvehicles ";
                                        $query1 = $dbh -> prepare($sql1);;
                                        $query1->execute();
                                        $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                                        $totalvehicle=$query1->rowCount();
                                        ?>
                                        <!-- Main Stat Number -->
                                        <div class="stat-panel-number h1" >
                                        <?php echo htmlentities($totalvehicle);?></div>
                                        </div>
                                
                                        <!-- Icon and Title -->
                                        <div class="icon" >
                                            <div class="title">
                                           Total Vehicles
                                            </div>
                                            <div class="icon-style" >
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                        </div>
                                        <!-- Footer Link -->
                                        <a href="manage-vehicles.php" class="block-anchor" ><i class="fa fa-arrow-right"></i>
                                            Full Detail <i class="fa fa-arrow-left  "></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <!-- Chart Section 
                        <div class="chart-container">
                            <canvas id="chartjs_bar" class="chartjs-size"></canvas>
                        </div>

                        <div class="chart-container">
                            <canvas id="chartjs_bar" class="chartjs-size"></canvas>
                        </div>-->

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            var ctx = document.getElementById('chartjs_bar').getContext('2d');
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: <?php echo json_encode($names); ?>,
                                    datasets: [{
                                        backgroundColor: ['#5969ff', '#ff407b', '#25d5f2', '#ffc750'],
                                        data: <?php echo json_encode($userid); ?>,
                                    }]
                                },
                                options: {
                                    legend: { display: false },
                                    title: { display: true, text: 'Vehicle Brands' },
                                    responsive: true
                                }
                            });
                        </script>
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