<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Code to delete an inspection type
    if (isset($_GET['del'])) {
        $inspectiontype_id = $_GET['del'];
        $sql = "DELETE FROM tblinspectiontype WHERE inspectiontype_id=:inspectiontype_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':inspectiontype_id', $inspectiontype_id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Inspection type deleted successfully";
    }

    // Code to add an inspection type
    if (isset($_POST['add'])) {
        $inspectiontype_name = $_POST['inspectiontype_name'];
        $sql = "INSERT INTO tblinspectiontype (inspectiontype_name) VALUES (:inspectiontype_name)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':inspectiontype_name', $inspectiontype_name, PDO::PARAM_STR);
        $query->execute();
        $msg = "Inspection type added successfully";
    }

    // Code to update an inspection type
    if (isset($_POST['update'])) {
        $inspectiontype_id = $_POST['edit_inspectiontype_id'];
        $inspectiontype_name = $_POST['edit_inspectiontype_name'];

        $sql = "UPDATE tblinspectiontype SET inspectiontype_name=:inspectiontype_name WHERE inspectiontype_id=:inspectiontype_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':inspectiontype_id', $inspectiontype_id, PDO::PARAM_INT);
        $query->bindParam(':inspectiontype_name', $inspectiontype_name, PDO::PARAM_STR);
        $query->execute();

        $msg = "Inspection type updated successfully";
    }

    ?>

    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Car Rental Portal | Staff Manage Inspection Type</title>
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
                            <h2 class="page-title">Manage Inspection Types</h2>
                            <div class="panel panel-default">
                                <div class="panel-heading">Listed Inspection Types</div>
                                <div class="panel-body">
                                    <?php if ($msg) { ?>
                                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?></div>
                                    <?php } ?>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#addTypeModal">Add
                                        Inspection Type</button>

                                    <table id="zctb" class="display table table-striped table-bordered table-hover"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Inspection Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM tblinspectiontype";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($result->inspectiontype_name); ?></td>
                                                        <td>
                                                            <a href="#"
                                                                onclick="editType(<?php echo $result->inspectiontype_id; ?>, '<?php echo htmlentities($result->inspectiontype_name); ?>')"
                                                                data-toggle="modal" data-target="#editTypeModal">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <a href="manage-inspectiontype.php?del=<?php echo $result->inspectiontype_id; ?>"
                                                                onclick="return confirm('Do you want to delete');"><i
                                                                    class="fa fa-close"></i></a>
                                                        </td>

                                                    </tr>
                                                    <?php $cnt++;
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

        <!-- Modal for Adding Inspection Type -->
        <div id="addTypeModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Create Inspection Type</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="inspectiontype_name">Type</label>
                                <input type="text" class="form-control" name="inspectiontype_name" placeholder="Enter type"
                                    required>
                            </div>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                            <button type="submit" name="add" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Editing Inspection Type -->
        <div id="editTypeModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Inspection Type</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="edit_inspectiontype_id" id="edit_inspectiontype_id">
                            <div class="form-group">
                                <label for="edit_inspectiontype_name">Type</label>
                                <input type="text" class="form-control" name="edit_inspectiontype_name"
                                    id="edit_inspectiontype_name" required>
                            </div>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                            <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function editType(id, name) {
                document.getElementById('edit_inspectiontype_id').value = id;
                document.getElementById('edit_inspectiontype_name').value = name;
            }
        </script>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script src="js/main.js"></script>
    </body>

    </html>
<?php } ?>