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
                                    WHERE v.status = 1
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
                    <!-- filtering feature -->
                    <form method="get" action="">
                        <div class="input-group">
                            
                            <select class="custom-select ml-2" id="segment" name="segment">
                                <option value="">Select Segment</option>
                                <?php
                                    $segmentQuery = $dbh->query("SELECT DISTINCT Segment FROM tblvehicles ORDER BY Segment ASC");
                                    while ($segment = $segmentQuery->fetch(PDO::FETCH_OBJ)) {
                                        $selected = isset($_GET['segment']) && $_GET['segment'] == $segment->Segment ? 'selected' : '';
                                        echo '<option value="' . htmlentities($segment->Segment) . '" ' . $selected . '>' . htmlentities($segment->Segment) . '</option>';
                                    }
                                ?>
                            </select>

                            <select class="custom-select" id="brand" name="brand">
                                <option value="">Select Brand</option>
                                <?php
                                    $brandQuery = $dbh->query("SELECT DISTINCT BrandName, id FROM tblbrands ORDER BY BrandName ASC");
                                    while ($brand = $brandQuery->fetch(PDO::FETCH_OBJ)) {
                                        $selected = isset($_GET['brand']) && $_GET['brand'] == $brand->id ? 'selected' : '';
                                        echo '<option value="' . htmlentities($brand->id) . '" ' . $selected . '>' . htmlentities($brand->BrandName) . '</option>';
                                    }
                                ?>
                            </select>

                            <input type="number" name="min_price" class="form-control ml-2" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? htmlentities($_GET['min_price']) : ''; ?>">
                            <input type="number" name="max_price" class="form-control ml-2" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlentities($_GET['max_price']) : ''; ?>">

                            <select class="custom-select ml-2" id="rating" name="rating">
                                <option value="">Select Rating</option>
                                <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        $selected = isset($_GET['rating']) && $_GET['rating'] == $i ? 'selected' : '';
                                        echo '<option value="' . $i . '" ' . $selected . '>' . str_repeat('★', $i) . ' - up</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    
                        <div class="sort-search-bar mb-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by car name or brand"
                                value="<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>"
                                style=" height: 40px; margin-top: 16px; width: 685px;">
                            <button type="submit" class="btn btn-primary ml-2" style="height: 40px; margin-top: 16px; width: 132px;">Filter</button>
                            <button type="reset" class="btn btn-secondary ml-2" style="background-color:cornflowerblue; height: 40px; margin-top: 16px; width: 132px;"
                                onclick="window.location.href = window.location.pathname;">Reset</button>
                        </div>
                    </form>

                    <!-- Car Cards -->
                    <div class="row">
                        <?php

                        function buildQueryString($params) {
                            $filteredParams = array_filter($params, function($value) {
                                return $value !== '' && $value !== null;
                            });
                            unset($filteredParams['page']);
                            return http_build_query($filteredParams) ? '&' . http_build_query($filteredParams) : '';
                        }

                        try {

                            $conditions = [];
                            $params = [];

                            // Handle Segment filter
                            if (!empty($_GET['segment'])) {
                                $conditions[] = "(v.Segment = :segment)";
                                $params[':segment'] = $_GET['segment'];
                            }

                            // Handle brand filter
                            if (!empty($_GET['brand'])) {
                                $conditions[] = "(v.VehiclesBrand = :brand)";
                                $params[':brand'] = $_GET['brand'];
                            }

                            // Handle price range filter
                            if (!empty($_GET['min_price'])) {
                                $conditions[] = "v.PricePerDay >= :min_price";
                                $params[':min_price'] = $_GET['min_price'];
                            }
                            if (!empty($_GET['max_price'])) {
                                $conditions[] = "v.PricePerDay <= :max_price";
                                $params[':max_price'] = $_GET['max_price'];
                            }

                            // Handle rating filter
                            if (!empty($_GET['rating'])) {
                                $conditions[] = "COALESCE((SELECT AVG(rating) FROM tblbooking WHERE VehicleId = v.id), 0) >= :rating";
                                $params[':rating'] = $_GET['rating'];
                            }

                            // Handle search filter
                            if (!empty($_GET['search'])) {
                                $conditions[] = "(v.VehiclesTitle LIKE :search OR b.BrandName LIKE :search)";
                                $params[':search'] = '%' . $_GET['search'] . '%';
                            }

                            // Combine all conditions
                            $whereSQL = '';
                            if (!empty($conditions)) {
                                $whereSQL = ' AND ' . implode(' AND ', $conditions);
                            }

                            $itemsPerPage = 12;
                            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                            $offset = ($page - 1) * $itemsPerPage;

                            // Get the total count for pagination
                            $countSQL = "SELECT COUNT(*) FROM tblvehicles v JOIN tblbrands b ON b.id = v.VehiclesBrand WHERE 1=1 $whereSQL";
                            $countQuery = $dbh->prepare($countSQL);

                            foreach ($params as $key => &$value) {
                                $countQuery->bindParam($key, $value);
                            }

                            if (!empty($_GET['search'])) {
                                $searchParam = "%" . $_GET['search'] . "%";
                                $countQuery->bindParam(':search', $searchParam, PDO::PARAM_STR);
                            }
                            
                            $countQuery->execute();
                            $totalItems = $countQuery->fetchColumn();
                            $totalPages = ceil($totalItems / $itemsPerPage);

                            // Fetch cars for the current page
                            $sql = "SELECT v.id, v.VehiclesTitle, v.PricePerDay, v.Vimage1, v.SeatingCapacity, v.ModelYear, 
                            v.FuelType, b.BrandName, v.VehiclesBrand,
                            COALESCE((SELECT AVG(rating) FROM tblbooking WHERE VehicleId = v.id), 0) AS avg_rating
                            FROM tblvehicles v
                            JOIN tblbrands b ON b.id = v.VehiclesBrand
                            WHERE 1=1 AND v.status = 1 $whereSQL
                            ORDER BY v.id ASC
                            LIMIT :offset, :itemsPerPage";
                    

                            $query = $dbh->prepare($sql);

                            foreach ($params as $key => &$value) {
                                $query->bindParam($key, $value);
                            }

                            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                            $query->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);

                            $query->execute();

                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                        } catch (PDOException $e) {
                            error_log("Query Error: " . $e->getMessage());
                            die("A database error occurred.");
                        }
                       
                        

                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) {
                                ?>
                                <div class="col-md-4 mb-4">
                                    <div class="car-card">
                                        <div class="car-image">
                                            <img src="admin/img/vehicleimages/<?php echo !empty($result->Vimage1) ? htmlentities($result->Vimage1) : 'Civicside.jpg'; ?>"
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
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <nav aria-label="Page navigation">
                            <div class="pagination">
                                <!-- Previous Button -->
                                <a href="<?php if ($page > 1) echo '?page=' . ($page - 1) . buildQueryString($_GET); else echo '#'; ?>" class="<?php if ($page <= 1) echo 'disabled'; ?>">&laquo;</a>

                                <!-- Page Numbers -->
                                <?php
                                $maxPagesToShow = 5; // Limit the number of page links to 5
                                $startPage = max(1, $page - floor($maxPagesToShow / 2)); // Determine the starting page
                                $endPage = min($totalPages, $startPage + $maxPagesToShow - 1); // Determine the ending page

                                // Adjust the startPage if the total pages are less than maxPagesToShow
                                if ($endPage - $startPage < $maxPagesToShow - 1) {
                                    $startPage = max(1, $endPage - $maxPagesToShow + 1);
                                }

                                // Loop through and display the page numbers
                                for ($i = $startPage; $i <= $endPage; $i++) { ?>
                                    <a href="?page=<?php echo $i . buildQueryString($_GET); ?>" class="<?php if ($i == $page) echo 'active'; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php } ?>

                                <!-- Next Button -->
                                <a href="<?php if ($page < $totalPages) echo '?page=' . ($page + 1) . buildQueryString($_GET); else echo '#'; ?>" class="<?php if ($page >= $totalPages) echo 'disabled'; ?>">&raquo;</a>
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