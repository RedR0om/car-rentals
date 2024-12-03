<?php
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="utf-8">
    <title>Car Rental | Car List</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta content="Author" name="WebThemez">
    <!-- Favicons -->
    <link href="img/favicon.png" rel="icon">
    <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700"
        rel="stylesheet">

    <!-- Bootstrap CSS File -->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Libraries CSS Files -->
    <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        /* Base styling for layout */
        .filter-sidebar {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f8f9fa;
        }

        .filter-sidebar h5 {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .filter-button {
            background-color: #d9534f;
            color: #fff;
            border: none;
            padding: 10px 20px;
        }

        /* Car listing cards */
        .car-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
            background-color: #fff;
        }

        .car-card:hover {
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .car-image img {
            width: 100%;
            height: auto;
        }

        .car-details {
            padding: 10px 15px;
        }

        .car-title {
            font-size: 1.1rem;
            color: #333;
        }

        .car-price {
            font-size: 1.2rem;
            color: #d9534f;
        }

        /* Search and sorting */
        .sort-search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-dropdown,
        .search-bar {
            width: 70%;

        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>

<body id="body">
    <?php include('includes/header.php'); ?>

    <section id="innerBanner" style="background: black;">
        <div class="inner-content">
            <h2 style="font-family:'Tahoma',sans-serif"><span style="color: #fa2837;">CAR LISTING</span><br>We provide
                high
                quality cars</h2>
        </div>
    </section><!-- #Page Banner -->

    <main id="main">
        <section id="car-listing" class="container my-5">
            <div class="row">
                <!-- Filter Sidebar -->
                <aside class="col-md-3 filter-sidebar">
                    <h5 class="widget_heading"><i class="fa fa-car"></i> Recently Listed Cars</h5>
                    <div class="recent_addedcars">
                        <ul>
                            <?php
                            $sql = "SELECT v.id, v.VehiclesTitle, v.PricePerDay, v.Vimage1, b.BrandName
                                    FROM tblvehicles v
                                    JOIN tblbrands b ON b.id = v.VehiclesBrand
                                    ORDER BY v.id DESC
                                    LIMIT 10";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $recentCars = $query->fetchAll(PDO::FETCH_OBJ);
                            foreach ($recentCars as $car) {
                                ?>
                                <li class="gray-bg">

                                    <div class="recent_post_title">
                                        <a href="car_details.php?vhid=<?php echo htmlentities($car->id); ?>">
                                            <?php echo htmlentities($car->BrandName); ?>,
                                            <?php echo htmlentities($car->VehiclesTitle); ?>
                                        </a>
                                        <p class="widget_price">
                                            ₱<?php echo number_format(htmlentities($car->PricePerDay), 2); ?> Per
                                            Day</p>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </aside>

                <!-- Car Listings -->
                <div class="col-md-9">
                    <div class="sort-search-bar mb-3">
                        <!--<select class="form-control sort-dropdown">
              <option>Sort by Date</option>
              <option>Sort by Price</option>
            </select>-->
                        <form method="get" action="" class="d-flex mb-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by car name or brand"
                                value="<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>"
                                style=" height: 40px;
    margin-top: 16px; width: 685px;">
                            <button type="submit" class="btn btn-primary ml-2" style="  height: 40px;
    margin-top: 16px;">Search</button>
                        </form>
                    </div>

                    <!-- Car Cards -->
                    <div class="row">
                        <?php
                        $itemsPerPage = 10;
                        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                        $offset = ($page - 1) * $itemsPerPage;
                        $search = isset($_GET['search']) ? $_GET['search'] : '';

                        // Modify SQL based on search input
                        $searchSQL = "";
                        if (!empty($search)) {
                            $searchSQL = "AND (v.VehiclesTitle LIKE :search OR b.BrandName LIKE :search)";
                        }

                        // Get the total count for pagination
                        $countSQL = "SELECT COUNT(*) FROM tblvehicles v JOIN tblbrands b ON b.id = v.VehiclesBrand WHERE 1=1 $searchSQL";
                        $countQuery = $dbh->prepare($countSQL);
                        if (!empty($search)) {
                            $searchParam = "%" . $search . "%";
                            $countQuery->bindParam(':search', $searchParam, PDO::PARAM_STR);
                        }
                        $countQuery->execute();
                        $totalItems = $countQuery->fetchColumn();
                        $totalPages = ceil($totalItems / $itemsPerPage);

                        // Fetch cars for the current page
                        $sql = "SELECT v.id, v.VehiclesTitle, v.PricePerDay, v.Vimage1, v.SeatingCapacity, v.ModelYear, 
                    v.FuelType, b.BrandName,
                    (SELECT AVG(rating) FROM tblbooking WHERE VehicleId = v.id) AS avg_rating
                    FROM tblvehicles v
                    JOIN tblbrands b ON b.id = v.VehiclesBrand
                    WHERE 1=1 $searchSQL
                    ORDER BY v.id ASC
                    LIMIT :offset, :itemsPerPage";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                        $query->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
                        if (!empty($search)) {
                            $query->bindParam(':search', $searchParam, PDO::PARAM_STR);
                        }
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) {
                                ?>
                                <div class="col-md-4 mb-4">
                                    <div class="car-card">
                                        <div class="car-image">
                                            <img src="admin/img/vehicleimages/<?php echo !empty($result->Vimage1) ? htmlentities($result->Vimage1) : '1audiq8.jpg'; ?>"
                                                alt="image" style="width: 254px; height: 142px;">
                                        </div>
                                        <div class="car-details">
                                            <a href="car_details.php?vhid=<?php echo htmlentities($result->id); ?>">
                                                <?php echo htmlentities($result->BrandName); ?>,
                                                <?php echo htmlentities($result->VehiclesTitle); ?>
                                            </a>
                                            <p class="car-price">
                                                ₱<?php echo number_format(htmlentities($result->PricePerDay), 2); ?> Per Day
                                            </p>
                                            <p>Rating: <?php echo htmlentities($result->avg_rating); ?>/5 <i
                                                    class="fa fa-star"></i></p>
                                            <a href="car_details.php?vhid=<?php echo htmlentities($result->id); ?>"
                                                class="btn btn-primary btn-block mt-2">View Details</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>

                    <!-- Pagination -->
                    <!-- Pagination -->
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <nav aria-label="Page navigation">
                                <div class="pagination">
                                    <!-- Previous Button -->
                                    <a href="<?php if ($page > 1)
                                        echo '?page=' . ($page - 1) . '&search=' . htmlentities($search);
                                    else
                                        echo '#'; ?>" class="<?php if ($page <= 1)
                                              echo 'disabled'; ?>">&laquo;</a>

                                    <!-- Page Numbers -->
                                    <?php
                                    $start = max(1, $page - 2);  // Start from 2 pages before the current page
                                    $end = min($totalPages, $page + 2);  // End at 2 pages after the current page
                                    for ($i = $start; $i <= $end; $i++) { ?>
                                        <a href="?page=<?php echo $i; ?>&search=<?php echo htmlentities($search); ?>" class="<?php if ($i == $page)
                                                  echo 'active'; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php } ?>

                                    <!-- Next Button -->
                                    <a href="<?php if ($page < $totalPages)
                                        echo '?page=' . ($page + 1) . '&search=' . htmlentities($search);
                                    else
                                        echo '#'; ?>" class="<?php if ($page >= $totalPages)
                                              echo 'disabled'; ?>">&raquo;</a>
                                </div>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>

    <!-- JavaScript  -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/superfish/hoverIntent.js"></script>
    <script src="lib/superfish/superfish.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/magnific-popup/magnific-popup.min.js"></script>
    <script src="lib/sticky/sticky.js"></script>
    <script src="contact/jqBootstrapValidation.js"></script>
    <script src="contact/contact_me.js"></script>
    <script src="js/main.js"></script>

</body>

</html>