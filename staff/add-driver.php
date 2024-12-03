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

            // Define upload directories (ensure these directories exist!)
            $documentDir = __DIR__ . "/driveruploads/document/";
            $licenseDir = __DIR__ . "/driveruploads/license/";

            // Ensure the directories exist; if not, create them
            if (!is_dir($documentDir)) {
                mkdir($documentDir, 0777, true);
            }
            if (!is_dir($licenseDir)) {
                mkdir($licenseDir, 0777, true);
            }

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

            // Generate Driver ID
            $stmt = $dbh->query("SELECT MAX(DriverID) AS max_id FROM tbldrivers");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $max_id = $result['max_id'];

            // Increment the driver ID
            if ($max_id) {
                $number = (int) substr($max_id, 4) + 1; // Extract the number part and increment
            } else {
                $number = 1; // Starting from 1 if no entries exist
            }

            // Format the new Driver ID
            $driver_id = 'DRV-' . str_pad($number, 4, '0', STR_PAD_LEFT); // Pad the number with zeros

            // Prepare the SQL insert statement
            $sql = "INSERT INTO tbldrivers (DriverID, Name, Email, Phone, Gender, Age, JoiningDate, Address, LicenseNumber, IssueDate, ExpirationDate, Document, LicensePicture, Reference, Notes)
            VALUES (:driver_id, :name, :email, :phone, :gender, :age, :joining_date, :address, :license_number, :issue_date, :expiration_date, :document, :license_picture, :reference, :notes)";

            $query = $dbh->prepare($sql);
            $query->bindParam(':driver_id', $driver_id, PDO::PARAM_STR); // Bind driver_id here
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
            .form-control {
                border-radius: 0.3rem;
            }

            .form-heading {
                font-weight: bold;
                color: #555;
                padding-bottom: 15px;
                border-bottom: 2px solid #eee;
            }

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

            .panel-body {
                background: #f7f7f9;
                border-radius: 8px;
                padding: 25px;
            }

            .form-label {
                font-weight: bold;
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

                    <!-- Add Driver Form -->
                    <div class="panel panel-default">
                        <div class="panel-heading form-heading">Add Driver</div>
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

                            <form method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Driver's full name" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Driver's email address" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" name="phone" class="form-control"
                                                placeholder="Driver's contact number" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Gender</label>
                                            <select name="gender" class="form-control" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Age</label>
                                            <input type="number" name="age" class="form-control" placeholder="Driver's age"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Joining Date</label>
                                            <input type="date" name="joining_date" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Address</label>
                                            <textarea name="address" class="form-control" placeholder="Driver's address"
                                                required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">License Number</label>
                                            <input type="text" name="license_number" class="form-control"
                                                placeholder="Driver's license number" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Issue Date</label>
                                            <input type="date" name="issue_date" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Expiration Date</label>
                                            <input type="date" name="expiration_date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Document (PDF)</label>
                                    <input type="file" name="document" class="form-control" accept=".pdf" required>
                                    <small class="form-text text-muted">Upload a PDF document (e.g., contract, ID
                                        proof).</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">License Picture</label>
                                    <input type="file" name="license_picture" class="form-control" accept="image/*"
                                        required>
                                    <small class="form-text text-muted">Upload a clear picture of the driver's
                                        license.</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Reference</label>
                                    <textarea name="reference" class="form-control"
                                        placeholder="Enter any reference information if available"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control"
                                        placeholder="Additional notes about the driver"></textarea>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Add
                                    Driver</button>
                            </form>
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