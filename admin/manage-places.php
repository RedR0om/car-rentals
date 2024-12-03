<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_GET['del'])) {
        $place_id = $_GET['del'];
        $sql = "DELETE FROM tblplace WHERE place_id=:place_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':place_id', $place_id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Place data deleted successfully";
    }

    if (isset($_POST['add_place'])) {
        $place_name = $_POST['place_name'];
        $city = $_POST['city'];
        $island = $_POST['island'];
        $price = $_POST['price'];
        $depo_name = $_POST['depo_name'];
        $depoaddress = $_POST['depoaddress'];

        $sql = "INSERT INTO tblplace (PlaceName, City, Island, Price, DepoName, DepoAddress) VALUES (:place_name, :city, :island, :price, :depo_name, :depoaddress)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':place_name', $place_name, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':island', $island, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':depo_name', $depo_name, PDO::PARAM_STR);
        $query->bindParam(':depoaddress', $depoaddress, PDO::PARAM_STR);
        $query->execute();
        $msg = "Place added successfully";
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

        <title>Car Rental Portal | Admin Manage Places</title>

        <!-- Font awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Sandstone Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Bootstrap Datatables -->
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <!-- Bootstrap select -->
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <!-- Awesome Bootstrap checkbox -->
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <!-- Admin Style -->
        <link rel="stylesheet" href="css/style.css">
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
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

                            <h2 class="page-title">Manage Places</h2>

                            <button class="btn btn-primary" data-toggle="modal" data-target="#addPlaceModal">Add
                                Place</button>

                            <!-- Add Place Modal -->
                            <div id="addPlaceModal" class="modal fade" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Add New Place</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post">
                                                <div class="form-group">
                                                    <label for="place_name">Place Name</label>
                                                    <input type="text" class="form-control" name="place_name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control" name="city" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="island">Island</label>
                                                    <input type="text" class="form-control" name="island" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input type="text" class="form-control" name="price" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="depo_name">Depo Name</label>
                                                    <input type="text" class="form-control" name="depo_name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="depo_address">Depo Address</label>
                                                    <input type="text" class="form-control" name="depoaddress" required>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" name="add_place" class="btn btn-primary">Add
                                                Place</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table to Display Places -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Listed Places</div>
                            <div class="panel-body">
                                <?php if ($error) { ?>
                                    <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?></div>
                                <?php } else if ($msg) { ?>
                                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?></div>
                                <?php } ?>
                                <table id="zctb" class="display table table-striped table-bordered table-hover"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Place Name</th>
                                            <th>City</th>
                                            <th>Island</th>
                                            <th>Price</th>
                                            <th>Depo Name</th>
                                            <th>Depo Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Place Name</th>
                                            <th>City</th>
                                            <th>Island</th>
                                            <th>Price</th>
                                            <th>Depo Name</th>
                                            <th>Depo Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM tblplace";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($result->PlaceName); ?></td>
                                                    <td><?php echo htmlentities($result->City); ?></td>
                                                    <td><?php echo htmlentities($result->Island); ?></td>
                                                    <td><?php echo htmlentities($result->Price); ?></td>
                                                    <td><?php echo htmlentities($result->DepoName); ?></td>
                                                    <td><?php echo htmlentities($result->DepoAddress); ?></td>
                                                    <td>
                                                        <a href="edit-places.php?place_id=<?php echo $result->place_id; ?>"
                                                            class="btn btn-primary">Edit</a>&nbsp;&nbsp;
                                                        <a href="manage-places.php?del=<?php echo $result->place_id; ?>"
                                                            onclick="return confirm('Do you want to delete');"
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