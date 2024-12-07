<?php
// error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include('includes/config.php');

if (isset($_POST['submitResponse'])) {
  $email = $_POST['email'];
  $response = htmlspecialchars($_POST['response']);
  $name = $_POST['name'];
  $id = $_POST['id'];

  // PHPMailer setup
  $mail = new PHPMailer(true);

  try {

    $sql = "UPDATE tblcontactusquery SET is_responded = 1 WHERE id = :id";

    $query = $dbh->prepare($sql);

    $query->bindParam(':id', $id, PDO::PARAM_STR);

    $query->execute();


    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'temporental2020@gmail.com';
    $mail->Password = 'poxmfhhclnpaqvip';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('temporental2020@gmail.com', 'temporental2020@gmail.com');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'T.M.T.C.R.S Support';

    $mail->Body = '
            <p>Dear ' . $name . ',</p>
            
            <p>' . $response . '</p>

            <p>Best regards,</p>

            <p>Your Support Team<br>
            Triple Mike Transport Services.</p>';

    $mail->send();

    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit;
  } catch (Exception $e) {
    echo "<script>alert('Mail Error: " . $mail->ErrorInfo . "');</script>";
    $error = "Something went wrong. Please try again";
  }
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

  <?php include('includes/header.php'); ?>
  <div class="ts-main-content">

    <?php include('includes/leftbar.php'); ?>
    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h2 class="page-title">Client Inquiries</h2>
            <div style="margin-bottom: 20px; font-size: 12px;"></div>

            <?php if (isset($error) && $error) { ?>
              <div class="alert alert-danger" role="alert"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
            <?php }
            // Display success message if redirected after submission
            else if (isset($_GET['success']) && $_GET['success'] == 1) { ?>
              <div class="alert alert-success" role="alert" style="color: black;"><strong>SUCCESS:</strong> Email sent successfully!</div>
            <?php } ?>
            <div class="table-responsive">
              <table id="zctb" class="display table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th scope="col" class='text-center'>Fullname</th>
                    <th scope="col" class='text-center'>Email</th>
                    <th scope="col" class='text-center'>Contact No.</th>
                    <th scope="col" class="message-column text-center">Message</th>
                    <th scope="col" class='text-center'>Date</th>
                    <th scope="col" class='text-center'>Is Responded</th>
                    <th scope="col" class='text-center'>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM tblcontactusquery ORDER BY PostingDate DESC";

                  $query = $dbh->prepare($sql);

                  $query->execute();

                  $results = $query->fetchAll(PDO::FETCH_ASSOC);
                  // Check if there are results

                  if ($results) {
                    $cnt = 1;
                    foreach ($results as $row) {
                      echo "<tr>";
                      echo "<td>" . htmlentities($cnt) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['EmailId']) . "</td>";
                      echo "<td class='text-center'>" . htmlspecialchars($row['ContactNumber']) . "</td>";
                      echo "<td class='message-column' title='" . htmlspecialchars($row['Message']) . "'>"
                        . htmlspecialchars(substr($row['Message'], 0, 50)) . (strlen($row['Message']) > 50 ? '...' : '') . "</td>";
                      echo "<td class='text-center'>" . htmlspecialchars($row['PostingDate']) . "</td>";
                      if ($row['is_responded']) {
                        echo "<td class='text-center'><span class='badge bg-success'>Responded</span></td>";
                      } else {
                        echo "<td class='text-center'><span class='badge bg-warning'>Pending</span></td>";
                      }
                      echo "<td class='text-center'>";
                      echo "<button class='btn btn-primary btn-view btn-sm' 
                                data-name='" . htmlspecialchars($row['name']) . "'
                                data-email='" . htmlspecialchars($row['EmailId']) . "'
                                data-contact='" . htmlspecialchars($row['ContactNumber']) . "'
                                data-message='" . htmlspecialchars($row['Message']) . "'
                                data-date='" . htmlspecialchars($row['PostingDate']) . "'
                                data-id='" . htmlspecialchars($row['id']) . "'
                                data-toggle='modal' 
                                data-target='#viewDetailsModal'>
                                <i class='fas fa-eye'></i> View Details
                            </button>";
                      echo "</td>";
                      echo "</tr>";
                      $cnt++;
                    }
                  } else {
                    echo "<tr><td colspan='6' class='text-center'>No messages found.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>



            <!-- View Details Modal -->
            <div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title" id="viewDetailsModalLabel">Inquiry Details</h3>
                  </div>

                  <form action="" method="post" style="font-family:'Tahoma',sans-serif">
                    <div class="modal-body">
                      <div class="row">
                        <input type="hidden" name="id" id="messageId">
                        <div class="col-md-6 form-group">
                          <strong>Full Name:</strong>
                          <p id="modalName"></p>
                          <input type="hidden" name="name" id="hiddenName">
                        </div>
                        <div class="col-md-6">
                          <strong>Email:</strong>
                          <p id="modalEmail"></p>
                          <input type="hidden" name="email" id="hiddenEmail">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <strong>Contact Number:</strong>
                          <p id="modalContact"></p>
                        </div>
                        <div class="col-md-6">
                          <strong>Date:</strong>
                          <p id="modalDate"></p>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <strong>Message:</strong>
                          <p id="modalMessage"></p>
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <strong>Response:</strong>
                          <textarea class="form-control white_bg" name="response" rows="10" required></textarea>
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-primary" type="submit" name="submitResponse" type="submit">Submit Response</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
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

  <script>
    $(document).ready(function() {
      // Handle view details modal
      $('#viewDetailsModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var name = button.data('name');
        var email = button.data('email');
        var contact = button.data('contact');
        var message = button.data('message');
        var date = button.data('date');
        var id = button.data('id');

        var modal = $(this);
        modal.find('#modalName').text(name);
        modal.find('#modalEmail').text(email);
        modal.find('#modalContact').text(contact);
        modal.find('#modalMessage').text(message);
        modal.find('#modalDate').text(date);

        $('#messageId').val(id);
      });
    });

    document.querySelectorAll('.btn-view').forEach(button => {
      button.addEventListener('click', function() {
        // Get data attributes from the clicked button
        const name = this.getAttribute('data-name');
        const email = this.getAttribute('data-email');

        // Populate the modal fields
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalEmail').textContent = email;

        // Populate the hidden input fields
        document.getElementById('hiddenName').value = name;
        document.getElementById('hiddenEmail').value = email;
      });
    });
  </script>

</body>

<style>
  .table-responsive {
    overflow-x: auto;
  }

  .message-column {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  @media (max-width: 768px) {
    .message-column {
      max-width: 100px;
    }
  }

  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .pagination a,
  .pagination span {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    margin: 0 4px;
  }

  .pagination a:hover {
    background-color: #ddd;
  }

  .pagination .active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
  }

  .badge.bg-success {
    background-color: green !important;
  }

  .badge.bg-warning {
    background-color: orange !important;
  }
</style>

</html>