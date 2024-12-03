<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
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
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <div class="ts-main-content">
            <?php include('includes/leftbar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">

                            <h2 class="page-title">Dashboard</h2>




                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-primary text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql = "SELECT user_id from users ";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $regusers = $query->rowCount();
                                                        ?>
                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($regusers); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Chat Users</div>
                                                    </div>
                                                </div>
                                                <a href="chat-user.php" class="block-anchor panel-footer">Full Detail <i
                                                        class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-success text-light">
                                                    <div class="stat-panel text-center">

                                                        <?php
                                                        $sql = "SELECT id from tblusers ";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $regusers = $query->rowCount();
                                                        ?>
                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($regusers); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Reg Users</div>
                                                    </div>
                                                </div>
                                                <a href="reg-users.php" class="block-anchor panel-footer">Full Detail <i
                                                        class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-success text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql1 = "SELECT id from tblvehicles ";
                                                        $query1 = $dbh->prepare($sql1);
                                                        ;
                                                        $query1->execute();
                                                        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                                        $totalvehicle = $query1->rowCount();
                                                        ?>
                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($totalvehicle); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Listed Vehicles</div>
                                                    </div>
                                                </div>
                                                <a href="manage-vehicles.php"
                                                    class="block-anchor panel-footer text-center">Full Detail &nbsp; <i
                                                        class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-warning text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql3 = "SELECT id from tblbrands ";
                                                        $query3 = $dbh->prepare($sql3);
                                                        $query3->execute();
                                                        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);
                                                        $brands = $query3->rowCount();
                                                        ?>
                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($brands); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Listed Brands</div>
                                                    </div>
                                                </div>
                                                <a href="manage-brands.php"
                                                    class="block-anchor panel-footer text-center">Full Detail &nbsp; <i
                                                        class="fa fa-arrow-right"></i></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-info text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql2 = "SELECT id from tblbooking ";
                                                        $query2 = $dbh->prepare($sql2);
                                                        $query2->execute();
                                                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                        $bookings = $query2->rowCount();
                                                        ?>

                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($bookings); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Total Bookings</div>
                                                    </div>
                                                </div>
                                                <a href="manage-bookings.php"
                                                    class="block-anchor panel-footer text-center">Full Detail &nbsp; <i
                                                        class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-primary text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql4 = "SELECT id from tblsubscribers ";
                                                        $query4 = $dbh->prepare($sql4);
                                                        $query4->execute();
                                                        $results4 = $query4->fetchAll(PDO::FETCH_OBJ);
                                                        $subscribers = $query4->rowCount();
                                                        ?>
                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($subscribers); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Subscibers</div>
                                                    </div>
                                                </div>
                                                <a href="manage-subscribers.php" class="block-anchor panel-footer">Full
                                                    Detail <i class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-success text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql6 = "SELECT id from tblcontactusquery ";
                                                        $query6 = $dbh->prepare($sql6);
                                                        ;
                                                        $query6->execute();
                                                        $results6 = $query6->fetchAll(PDO::FETCH_OBJ);
                                                        $query = $query6->rowCount();
                                                        ?>
                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($query); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Queries</div>
                                                    </div>
                                                </div>
                                                <a href="manage-conactusquery.php"
                                                    class="block-anchor panel-footer text-center">Full Detail &nbsp; <i
                                                        class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-info text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql5 = "SELECT id from tbltestimonial ";
                                                        $query5 = $dbh->prepare($sql5);
                                                        $query5->execute();
                                                        $results5 = $query5->fetchAll(PDO::FETCH_OBJ);
                                                        $testimonials = $query5->rowCount();
                                                        ?>

                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($testimonials); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">Testimonials</div>
                                                    </div>
                                                </div>
                                                <a href="testimonials.php"
                                                    class="block-anchor panel-footer text-center">Full Detail &nbsp; <i
                                                        class="fa fa-arrow-right"></i></a>

                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="panel panel-default">
                                                <div class="panel-body bk-info text-light">
                                                    <div class="stat-panel text-center">
                                                        <?php
                                                        $sql6 = "SELECT id from tblbooking WHERE Status='3' ";
                                                        $query6 = $dbh->prepare($sql6);
                                                        $query6->execute();
                                                        $results6 = $query6->fetchAll(PDO::FETCH_OBJ);
                                                        $ongoing = $query6->rowCount();
                                                        ?>

                                                        <div class="stat-panel-number h1 ">
                                                            <?php echo htmlentities($ongoing); ?>
                                                        </div>
                                                        <div class="stat-panel-title text-uppercase">On-Going Cars</div>
                                                    </div>
                                                </div>
                                                <a href="manage-bookings.php"
                                                    class="block-anchor panel-footer text-center">Full Detail &nbsp; <i
                                                        class="fa fa-arrow-right"></i></a>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <?php
                                $con = mysqli_connect("localhost", "root", "", "carrental");
                                if (!$con) {
                                    # code...
                                    echo "Problem in database connection! Contact administrator!" . mysqli_error();
                                } else {
                                    $sql = "SELECT COUNT(*) VehiclesBrand FROM tblvehicles GROUP BY VehiclesBrand";
                                    $result = mysqli_query($con, $sql);
                                    $chart_data = "";
                                    while ($row = mysqli_fetch_array($result)) {
                                        $userid[] = $row['VehiclesBrand'];
                                    }
                                    $sql = "SELECT * FROM tblbrands";
                                    $result = mysqli_query($con, $sql);
                                    $chart_data = "";
                                    while ($row = mysqli_fetch_array($result)) {

                                        $names[] = $row['BrandName'];
                                    }


                                }
                                ?>


                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"> </script>
                                <script>
                                    var ctx = document.getElementById("chartjs_bar").getContext('2d');
                                    var myChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: <?php echo json_encode($names); ?>,
                                            datasets: [{
                                                backgroundColor: [
                                                    "#5969ff",
                                                    "#ff407b",
                                                    "#25d5f2",
                                                    "#ffc750",
                                                    "#2ec551",
                                                    "#7040fa",
                                                    "#ff004e"
                                                ],
                                                data: <?php echo json_encode($userid); ?>,
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            title: {
                                                display: true,
                                                text: "Vehicles brands"
                                            }
                                        }
                                    });
                                </script>

                                <?php
                                $con = mysqli_connect("localhost", "root", "", "carrental");
                                if (!$con) {
                                    echo "Problem in database connection! Contact administrator!" . mysqli_error($con);
                                } else {
                                    // Existing code for counting bookings and fetching brands...
                            
                                    // Get the current year and previous year
                                    $currentYear = date('Y');
                                    $previousYear = $currentYear - 3;

                                    // Prepare array of years for dropdown (last 5 years)
                                    $years = [];
                                    for ($i = $currentYear; $i >= $previousYear; $i--) {
                                        $years[] = $i;
                                    }

                                    // Default query for the current year
                                    $yearToQuery = $currentYear; // Default to current year
                                    $monthlyBookingsQuery = "SELECT MONTH(PostingDate) AS month, COUNT(*) AS totalBookings 
                             FROM tblbooking 
                             WHERE YEAR(PostingDate) = $yearToQuery 
                             GROUP BY MONTH(PostingDate)";

                                    $monthlyBookingsResult = mysqli_query($con, $monthlyBookingsQuery);

                                    // Prepare an array for months and initialize monthly data
                                    $months = [
                                        'January',
                                        'February',
                                        'March',
                                        'April',
                                        'May',
                                        'June',
                                        'July',
                                        'August',
                                        'September',
                                        'October',
                                        'November',
                                        'December'
                                    ];
                                    $monthlyData = array_fill(0, 12, 0); // Initialize all months to zero
                            
                                    // Fill monthly data from query
                                    while ($row = mysqli_fetch_assoc($monthlyBookingsResult)) {
                                        $monthlyData[$row['month'] - 1] = $row['totalBookings']; // -1 for zero-index
                                    }
                                }
                                ?>

                                <div class="col-md-6">
                                    <div class="panel panel-default" style="width: 990px;">
                                        <div class="panel-heading">Monthly Bookings</div>
                                        <div class="panel-body">
                                            <label for="yearSelect">Select Year:</label>
                                            <select id="yearSelect" class="form-control"
                                                style="width: auto; display: inline-block;">
                                                <?php foreach ($years as $year): ?>
                                                    <option value="<?php echo $year; ?>" <?php if ($year == $yearToQuery)
                                                           echo 'selected'; ?>><?php echo $year; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <canvas id="monthlyBookingsChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
                                <script>
                                    var monthlyData = <?php echo json_encode($monthlyData); ?>;
                                    var months = <?php echo json_encode($months); ?>;

                                    // Define an array of colors for each month
                                    var colors = [
                                        "#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0",
                                        "#9966FF", "#FF9F40", "#FF5733", "#33FF57",
                                        "#3357FF", "#9D33FF", "#FF33C4", "#33FFC4"
                                    ];

                                    var ctx = document.getElementById("monthlyBookingsChart").getContext('2d');
                                    var monthlyBookingsChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: months,
                                            datasets: [{
                                                label: 'Monthly Bookings',
                                                backgroundColor: colors, // Assign different colors for each month
                                                data: monthlyData
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: true
                                            },
                                            title: {
                                                display: true,
                                                text: "Monthly Bookings for the Year <?php echo $yearToQuery; ?>"
                                            }
                                        }
                                    });

                                    // Update chart when year is changed
                                    document.getElementById('yearSelect').addEventListener('change', function () {
                                        var selectedYear = this.value;

                                        // Fetch monthly bookings for the selected year via AJAX
                                        fetch('controller/fetch_monthly_bookings.php?year=' + selectedYear)
                                            .then(response => response.json())
                                            .then(data => {
                                                // Update the chart
                                                monthlyBookingsChart.data.datasets[0].data = data.monthlyData;
                                                monthlyBookingsChart.options.title.text = "Monthly Bookings for the Year " + selectedYear;
                                                monthlyBookingsChart.update();
                                            });
                                    });
                                </script>


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