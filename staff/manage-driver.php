<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    $msg = ""; // Initialize the $msg variable

    try {
        // Set PDO error mode to exception
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['submit'])) {
            // Gather form data for adding a driver
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $gender = $_POST['gender'];
            $age = $_POST['age'];
            $joining_date = $_POST['joining_date'];
            $address = $_POST['address'];
            $license_number = $_POST['license_number'];
            $issue_date = $_POST['issue_date'];
            $expiration_date = $_POST['expiration_date'];
            $reference = $_POST['reference'] ?? null;
            $notes = $_POST['notes'] ?? null;

            // Define upload directories
            $documentDir = __DIR__ . "/driveruploads/document/";
            $licenseDir = __DIR__ . "/driveruploads/license/";

            if (!is_dir($documentDir))
                mkdir($documentDir, 0777, true);
            if (!is_dir($licenseDir))
                mkdir($licenseDir, 0777, true);

            // Handle document file upload
            $documentPath = null;
            if ($_FILES['document']['error'] == 0) {
                $documentPath = $documentDir . basename($_FILES['document']['name']);
                move_uploaded_file($_FILES['document']['tmp_name'], $documentPath);
            }

            // Handle license picture upload
            $licensePath = null;
            if ($_FILES['license_picture']['error'] == 0) {
                $licensePath = $licenseDir . basename($_FILES['license_picture']['name']);
                move_uploaded_file($_FILES['license_picture']['tmp_name'], $licensePath);
            }

            // Insert data into database
            $sql = "INSERT INTO tbldrivers (Name, Email, Phone, Gender, Age, JoiningDate, Address, LicenseNumber, IssueDate, ExpirationDate, Document, LicensePicture, Reference, Notes)
                    VALUES (:name, :email, :phone, :gender, :age, :joining_date, :address, :license_number, :issue_date, :expiration_date, :document, :license_picture, :reference, :notes)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':phone', $phone, PDO::PARAM_STR);
            $query->bindParam(':gender', $gender, PDO::PARAM_STR);
            $query->bindParam(':age', $age, PDO::PARAM_INT);
            $query->bindParam(':joining_date', $joining_date, PDO::PARAM_STR);
            $query->bindParam(':address', $address, PDO::PARAM_STR);
            $query->bindParam(':license_number', $license_number, PDO::PARAM_STR);
            $query->bindParam(':issue_date', $issue_date, PDO::PARAM_STR);
            $query->bindParam(':expiration_date', $expiration_date, PDO::PARAM_STR);
            $query->bindParam(':document', $documentPath, PDO::PARAM_STR);
            $query->bindParam(':license_picture', $licensePath, PDO::PARAM_STR);
            $query->bindParam(':reference', $reference, PDO::PARAM_STR);
            $query->bindParam(':notes', $notes, PDO::PARAM_STR);
            $query->execute();
            $msg = "Driver added successfully";
        }

        if (isset($_POST['update'])) {
            $driverID = $_POST['driverID'];
            $name = $_POST['edit_name'];
            $email = $_POST['edit_email'];
            $phone = $_POST['edit_phone'];
            $gender = $_POST['edit_gender'];
            $age = $_POST['edit_age'];
            $joining_date = $_POST['edit_joining_date'];
            $address = $_POST['edit_address'];
            $license_number = $_POST['edit_license_number'];
            $issue_date = $_POST['edit_issue_date'];
            $expiration_date = $_POST['edit_expiration_date'];
            $reference = $_POST['edit_reference'];
            $notes = $_POST['edit_notes'];

            // Prepare SQL for update
            $sql = "UPDATE tbldrivers SET 
                        Name = :name,
                        Email = :email,
                        Phone = :phone,
                        Gender = :gender,
                        Age = :age,
                        JoiningDate = :joining_date,
                        Address = :address,
                        LicenseNumber = :license_number,
                        IssueDate = :issue_date,
                        ExpirationDate = :expiration_date,
                        Reference = :reference,
                        Notes = :notes 
                    WHERE DriverID = :driverID";

            $query = $dbh->prepare($sql);
            $query->bindParam(':driverID', $driverID, PDO::PARAM_INT);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':phone', $phone, PDO::PARAM_STR);
            $query->bindParam(':gender', $gender, PDO::PARAM_STR);
            $query->bindParam(':age', $age, PDO::PARAM_INT);
            $query->bindParam(':joining_date', $joining_date, PDO::PARAM_STR);
            $query->bindParam(':address', $address, PDO::PARAM_STR);
            $query->bindParam(':license_number', $license_number, PDO::PARAM_STR);
            $query->bindParam(':issue_date', $issue_date, PDO::PARAM_STR);
            $query->bindParam(':expiration_date', $expiration_date, PDO::PARAM_STR);
            $query->bindParam(':reference', $reference, PDO::PARAM_STR);
            $query->bindParam(':notes', $notes, PDO::PARAM_STR);

            $query->execute();
            $msg = "Driver updated successfully";
        }

    } catch (PDOException $e) {
        $msg = "Error: " . $e->getMessage();
    }
    ?>
    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Car Rental Portal | Staff Manage Drivers</title>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <link rel="stylesheet" href="css/fileinput.min.css">
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                    <h2 class="page-title">Manage Drivers</h2>


                    <div class="panel-body">
                        <?php if (isset($error) && !empty($error)) { ?>
                            <div class="errorWrap">
                                <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                            </div>
                        <?php } else if (isset($msg) && !empty($msg)) { ?>
                                <div class="succWrap">
                                    <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                </div>
                        <?php } ?>

                    </div>

                    <!-- Display Driver Table -->
                    <div class="panel panel-default">
                        <div class="panel-heading">Driver List</div>

                        <div class="panel-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>DriverID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>License Number</th>
                                        <th>Issue Date</th>
                                        <th>Expiration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT DriverID, Name, Email, Phone, Gender, Age, JoiningDate, Address, LicenseNumber, IssueDate, ExpirationDate, Reference, Notes FROM tbldrivers";

                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                            ?>
                                            <tr>
                                                <td><?php echo htmlentities($result->DriverID); ?></td>
                                                <td><?php echo htmlentities($result->Name); ?></td>
                                                <td><?php echo htmlentities($result->Email); ?></td>
                                                <td><?php echo htmlentities($result->Phone); ?></td>
                                                <td><?php echo htmlentities($result->LicenseNumber); ?></td>
                                                <td><?php echo htmlentities($result->IssueDate); ?></td>
                                                <td><?php echo htmlentities($result->ExpirationDate); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-primary edit-btn" data-toggle="modal"
                                                        data-target="#editDriverModal"
                                                        data-id="<?php echo htmlentities($result->DriverID); ?>"
                                                        data-name="<?php echo htmlentities($result->Name); ?>"
                                                        data-email="<?php echo htmlentities($result->Email); ?>"
                                                        data-phone="<?php echo htmlentities($result->Phone); ?>"
                                                        data-gender="<?php echo htmlentities($result->Gender); ?>"
                                                        data-age="<?php echo htmlentities($result->Age); ?>"
                                                        data-joining_date="<?php echo htmlentities($result->JoiningDate); ?>"
                                                        data-address="<?php echo htmlentities($result->Address); ?>"
                                                        data-license_number="<?php echo htmlentities($result->LicenseNumber); ?>"
                                                        data-issue_date="<?php echo htmlentities($result->IssueDate); ?>"
                                                        data-expiration_date="<?php echo htmlentities($result->ExpirationDate); ?>"
                                                        data-reference="<?php echo htmlentities($result->Reference); ?>"
                                                        data-notes="<?php echo htmlentities($result->Notes); ?>">Edit</a>
                                                    |
                                                    <a href="#" class="btn btn-info view-btn" data-toggle="modal"
                                                        data-target="#viewDriverModal"
                                                        data-id="<?php echo htmlentities($result->DriverID); ?>"
                                                        data-name="<?php echo htmlentities($result->Name); ?>"
                                                        data-email="<?php echo htmlentities($result->Email); ?>"
                                                        data-phone="<?php echo htmlentities($result->Phone); ?>"
                                                        data-gender="<?php echo htmlentities($result->Gender); ?>"
                                                        data-age="<?php echo htmlentities($result->Age); ?>"
                                                        data-joining_date="<?php echo htmlentities($result->JoiningDate); ?>"
                                                        data-address="<?php echo htmlentities($result->Address); ?>"
                                                        data-license_number="<?php echo htmlentities($result->LicenseNumber); ?>"
                                                        data-issue_date="<?php echo htmlentities($result->IssueDate); ?>"
                                                        data-expiration_date="<?php echo htmlentities($result->ExpirationDate); ?>"
                                                        data-reference="<?php echo htmlentities($result->Reference); ?>"
                                                        data-notes="<?php echo htmlentities($result->Notes); ?>">View</a>
                                                    |
                                                    <a href="manage-drivers.php?del=<?php echo htmlentities($result->DriverID); ?>"
                                                        onclick="return confirm('Do you really want to delete this driver?');"
                                                        class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Driver Modal -->
        <div class="modal fade" id="editDriverModal" tabindex="-1" role="dialog" aria-labelledby="editDriverModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDriverModalLabel">Edit Driver</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="editDriverForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="driverID" id="driverID">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_name" class="form-label">Name</label>
                                        <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_email" class="form-label">Email</label>
                                        <input type="email" name="edit_email" id="edit_email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_phone" class="form-label">Phone Number</label>
                                        <input type="text" name="edit_phone" id="edit_phone" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_gender" class="form-label">Gender</label>
                                        <select name="edit_gender" id="edit_gender" class="form-control" required>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_age" class="form-label">Age</label>
                                        <input type="number" name="edit_age" id="edit_age" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_joining_date" class="form-label">Joining Date</label>
                                        <input type="date" name="edit_joining_date" id="edit_joining_date"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_address" class="form-label">Address</label>
                                        <textarea name="edit_address" id="edit_address" class="form-control"
                                            required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_license_number" class="form-label">License Number</label>
                                        <input type="text" name="edit_license_number" id="edit_license_number"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_issue_date" class="form-label">Issue Date</label>
                                        <input type="date" name="edit_issue_date" id="edit_issue_date" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_expiration_date" class="form-label">Expiration Date</label>
                                        <input type="date" name="edit_expiration_date" id="edit_expiration_date"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_reference" class="form-label">Reference</label>
                                <textarea name="edit_reference" id="edit_reference" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_notes" class="form-label">Notes</label>
                                <textarea name="edit_notes" id="edit_notes" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="update" class="btn btn-primary">Update Driver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Driver Modal -->
        <div class="modal fade" id="viewDriverModal" tabindex="-1" role="dialog" aria-labelledby="viewDriverModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="viewDriverModalLabel">View Driver Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4><i class="fa fa-user" style="font-size: 15px;"></i> Name:</h4>
                                <p id="view_name" style="font-size: 15px;"></p>

                                <h4><i class="fa fa-envelope"></i> Email:</h4>
                                <p id="view_email"></p>

                                <h4><i class="fa fa-phone"></i> Phone Number:</h4>
                                <p id="view_phone"></p>

                                <h4><i class="fa fa-venus-mars"></i> Gender:</h4>
                                <p id="view_gender"></p>

                                <h4><i class="fa fa-calendar"></i> Age:</h4>
                                <p id="view_age"></p>

                                <h4><i class="fa fa-calendar-check"></i> Joining Date:</h4>
                                <p id="view_joining_date"></p>
                            </div>
                            <div class="col-md-6">
                                <h4><i class="fa fa-map-marker-alt"></i> Address:</h4>
                                <p id="view_address"></p>

                                <h4><i class="fa fa-id-card"></i> License Number:</h4>
                                <p id="view_license_number"></p>

                                <h4><i class="fa fa-clock"></i> Issue Date:</h4>
                                <p id="view_issue_date"></p>

                                <h4><i class="fa fa-clock"></i> Expiration Date:</h4>
                                <p id="view_expiration_date"></p>

                                <h4><i class="fa fa-user-friends"></i> Reference:</h4>
                                <p id="view_reference"></p>

                                <h4><i class="fa fa-comment"></i> Notes:</h4>
                                <p id="view_notes"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.edit-btn').click(function () {
                    $('#driverID').val($(this).data('id'));
                    $('#edit_name').val($(this).data('name'));
                    $('#edit_email').val($(this).data('email'));
                    $('#edit_phone').val($(this).data('phone'));
                    $('#edit_gender').val($(this).data('gender'));
                    $('#edit_age').val($(this).data('age'));
                    $('#edit_joining_date').val($(this).data('joining_date'));
                    $('#edit_address').val($(this).data('address'));
                    $('#edit_license_number').val($(this).data('license_number'));
                    $('#edit_issue_date').val($(this).data('issue_date'));
                    $('#edit_expiration_date').val($(this).data('expiration_date'));
                    $('#edit_reference').val($(this).data('reference'));
                    $('#edit_notes').val($(this).data('notes'));
                });
                // View button click handler
                $('.view-btn').click(function () {
                    $('#view_name').text($(this).data('name'));
                    $('#view_email').text($(this).data('email'));
                    $('#view_phone').text($(this).data('phone'));
                    $('#view_gender').text($(this).data('gender'));
                    $('#view_age').text($(this).data('age'));
                    $('#view_joining_date').text($(this).data('joining_date'));
                    $('#view_address').text($(this).data('address'));
                    $('#view_license_number').text($(this).data('license_number'));
                    $('#view_issue_date').text($(this).data('issue_date'));
                    $('#view_expiration_date').text($(this).data('expiration_date'));
                    $('#view_reference').text($(this).data('reference'));
                    $('#view_notes').text($(this).data('notes'));
                });
            });
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