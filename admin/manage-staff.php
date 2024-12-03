<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if user is logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Add new staff member logic
    if (isset($_POST['submit'])) {
        $userName = $_POST['UserName'];
        $name = $_POST['Name'];
        $email = $_POST['Email'];
        $password = $_POST['Password'];
        $contactNo = $_POST['ContactNo'];

        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new staff into the database
        $sql = "INSERT INTO tblstaff (UserName, Name, Email, Password, ContactNo) 
                VALUES (:userName, :name, :email, :password, :contactNo)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':userName', $userName, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $query->bindParam(':contactNo', $contactNo, PDO::PARAM_STR);
        $query->execute();

        // Show success message
        $msg = "Staff added successfully!";
    }

    // Update staff member logic
    if (isset($_POST['update'])) {
        $staffId = $_POST['StaffId'];
        $userName = $_POST['EditUserName'];
        $name = $_POST['EditName'];
        $email = $_POST['EditEmail'];
        $contactNo = $_POST['EditContactNo'];

        // Update staff details in the database
        $sql = "UPDATE tblstaff SET UserName=:userName, Name=:name, Email=:email, ContactNo=:contactNo WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':userName', $userName, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':contactNo', $contactNo, PDO::PARAM_STR);
        $query->bindParam(':id', $staffId, PDO::PARAM_STR);
        $query->execute();

        $msg = "Staff updated successfully!";
    }

    // Delete staff member logic
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "DELETE FROM tblstaff WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Staff deleted successfully!";
    }

    ?>
    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Car Rental Portal | Admin Manage Staff</title>

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
    </head>

    <body>
        <?php include('includes/header.php'); ?>
        <div class="ts-main-content">
            <?php include('includes/leftbar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="page-title">Manage Staff</h2>
                            <!-- Button to Open Add Staff Modal -->
                            <div style="margin-bottom: 20px; font-size: 12px;">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addStaffModal" style="padding: 10px;">
                                    Add New Staff
                                </button>
                            </div>
                            <!-- Staff Management Table -->
                            <div class="panel panel-default">
                                <div class="panel-heading">Staff List</div>
                                <div class="panel-body">
                                    <?php if ($error) { ?>
                                        <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                        </div>
                                    <?php } else if ($msg) { ?>
                                            <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
                                    <?php } ?>

                                    <table id="staffTable" class="display table table-striped table-bordered table-hover"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>UserName</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact No</th>
                                                <th>Updation Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>UserName</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact No</th>
                                                <th>Updation Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            // Fetch staff details from database
                                            $sql = "SELECT * FROM tblstaff";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($result->UserName); ?></td>
                                                        <td><?php echo htmlentities($result->Name); ?></td>
                                                        <td><?php echo htmlentities($result->Email); ?></td>
                                                        <td><?php echo htmlentities($result->ContactNo); ?></td>
                                                        <td><?php echo htmlentities($result->UpdationDate); ?></td>
                                                        <td>
                                                            <a href="javascript:void(0);" onclick="openEditModal(
                                                                    '<?php echo $result->id; ?>',
                                                                    '<?php echo htmlentities($result->UserName); ?>',
                                                                    '<?php echo htmlentities($result->Name); ?>',
                                                                    '<?php echo htmlentities($result->Email); ?>',
                                                                    '<?php echo htmlentities($result->ContactNo); ?>'
                                                                )" class="btn btn-primary">Edit</a>
                                                            &nbsp;&nbsp;
                                                            <a href="manage-staff.php?del=<?php echo $result->id; ?>"
                                                                onclick="return confirm('Do you really want to delete this staff?');"
                                                                class="btn btn-danger">Delete</a>
                                                        </td>

                                                    </tr>
                                                    <?php $cnt++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- End panel -->
                        </div> <!-- End col-md-12 -->
                    </div> <!-- End row -->
                </div> <!-- End container-fluid -->
                <!-- Modal to Add Staff -->
                <div id="addStaffModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addStaffModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addStaffModalLabel">Add New Staff</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="manage-staff.php">
                                    <div class="form-group">
                                        <label for="userName">Username</label>
                                        <input type="text" class="form-control" id="userName" name="UserName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="Password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contactNo">Contact Number</label>
                                        <input type="text" class="form-control" id="contactNo" name="ContactNo" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary">Add Staff</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal to Edit Staff -->
                <div id="editStaffModal" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="editStaffModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editStaffModalLabel">Edit Staff Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="manage-staff.php">
                                    <input type="hidden" id="editStaffId" name="StaffId"> <!-- Hidden input for staff ID -->
                                    <div class="form-group">
                                        <label for="editUserName">Username</label>
                                        <input type="text" class="form-control" id="editUserName" name="EditUserName"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editName">Full Name</label>
                                        <input type="text" class="form-control" id="editName" name="EditName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editEmail">Email Address</label>
                                        <input type="email" class="form-control" id="editEmail" name="EditEmail" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editContactNo">Contact Number</label>
                                        <input type="text" class="form-control" id="editContactNo" name="EditContactNo"
                                            required>
                                    </div>
                                    <button type="submit" name="update" class="btn btn-primary">Update Staff</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div> <!-- End content-wrapper -->
        </div> <!-- End ts-main-content -->
        <script>
            function openEditModal(id, userName, name, email, contactNo) {
                document.getElementById('editStaffId').value = id;
                document.getElementById('editUserName').value = userName;
                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editContactNo').value = contactNo;
                $('#editStaffModal').modal('show');
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