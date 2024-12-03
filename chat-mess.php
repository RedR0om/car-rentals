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

    <title>Car Rental Portal | Chat Message</title>

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

              <h2 class="page-title">Chat Message</h2>
              <style type="text/css">
                .chat-area header {
                  display: flex;
                  align-items: center;
                  padding: 18px 30px;
                }

                .chat-area header .back-icon {
                  color: #333;
                  font-size: 18px;
                }

                .chat-area header img {
                  height: 45px;
                  width: 45px;
                  margin: 0 15px;
                }

                .chat-area header .details span {
                  font-size: 17px;
                  font-weight: 500;
                }

                .chat-box {
                  position: relative;
                  min-height: 500px;
                  max-height: 500px;
                  overflow-y: auto;
                  padding: 10px 30px 20px 30px;
                  background: #f7f7f7;
                  box-shadow: inset 0 32px 32px -32px rgb(0 0 0 / 5%),
                    inset 0 -32px 32px -32px rgb(0 0 0 / 5%);
                }

                .chat-box .text {
                  position: absolute;
                  top: 45%;
                  left: 50%;
                  width: calc(100% - 50px);
                  text-align: center;
                  transform: translate(-50%, -50%);
                }

                .chat-box .chat {
                  margin: 15px 0;
                }

                .chat-box .chat p {
                  word-wrap: break-word;
                  padding: 8px 16px;
                  box-shadow: 0 0 32px rgb(0 0 0 / 8%),
                    0rem 16px 16px -16px rgb(0 0 0 / 10%);
                }

                .chat-box .outgoing {
                  display: flex;
                }

                .chat-box .outgoing .details {
                  margin-left: auto;
                  max-width: calc(100% - 130px);
                }

                .outgoing .details p {
                  background: #333;
                  color: #fff;
                  border-radius: 18px 18px 0 18px;
                }

                .chat-box .incoming {
                  display: flex;
                  align-items: flex-end;
                }

                .chat-box .incoming img {
                  height: 35px;
                  width: 35px;
                }

                .chat-box .incoming .details {
                  margin-right: auto;
                  margin-left: 10px;
                  max-width: calc(100% - 130px);
                }

                .incoming .details p {
                  background: #fff;
                  color: #333;
                  border-radius: 18px 18px 18px 0;
                }

                .typing-area {
                  padding: 18px 30px;
                  display: flex;
                  justify-content: space-between;
                }

                .typing-area input {
                  height: 45px;
                  width: calc(100% - 58px);
                  font-size: 16px;
                  padding: 0 13px;
                  border: 1px solid #e6e6e6;
                  outline: none;
                  border-radius: 5px 0 0 5px;
                }

                .typing-area button {
                  color: #fff;
                  width: 55px;
                  border: none;
                  outline: none;
                  background: #333;
                  font-size: 19px;
                  cursor: pointer;
                  opacity: 0.7;
                  pointer-events: none;
                  border-radius: 0 5px 5px 0;
                  transition: all 0.3s ease;
                }

                .typing-area button.active {
                  opacity: 1;
                  pointer-events: auto;
                }

                /* Responive media query */
                @media screen and (max-width: 450px) {

                  .form,
                  .users {
                    padding: 20px;
                  }

                  .form header {
                    text-align: center;
                  }

                  .form form .name-details {
                    flex-direction: column;
                  }

                  .form .name-details .field:first-child {
                    margin-right: 0px;
                  }

                  .form .name-details .field:last-child {
                    margin-left: 0px;
                  }

                  .users header img {
                    height: 45px;
                    width: 45px;
                  }

                  .users header .logout {
                    padding: 6px 10px;
                    font-size: 16px;
                  }

                  :is(.users, .users-list) .content .details {
                    margin-left: 15px;
                  }

                  .users-list a {
                    padding-right: 10px;
                  }

                  .chat-area header {
                    padding: 15px 20px;
                  }

                  .chat-box {
                    min-height: 400px;
                    padding: 10px 15px 15px 20px;
                  }

                  .chat-box .chat p {
                    font-size: 15px;
                  }

                  .chat-box .outogoing .details {
                    max-width: 230px;
                  }

                  .chat-box .incoming .details {
                    max-width: 265px;
                  }

                  .incoming .details img {
                    height: 30px;
                    width: 30px;
                  }

                  .chat-area form {
                    padding: 20px;
                  }

                  .chat-area form input {
                    height: 40px;
                    width: calc(100% - 48px);
                  }

                  .chat-area form button {
                    width: 45px;
                  }
              </style>

              <section class="chat-area">
                <header>
                  <?php
                  $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
                  $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
                  if (mysqli_num_rows($sql) > 0) {
                    $row = mysqli_fetch_assoc($sql);
                  } else {
                    header("location: users.php");
                  }
                  ?>
                  <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                  <img src="php/images/<?php echo $row['img']; ?>" alt="">
                  <div class="details">
                    <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
                    <p><?php echo $row['status']; ?></p>
                  </div>
                </header>
                <div class="chat-box">

                </div>
                <form action="#" class="typing-area">
                  <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
                  <input type="text" name="message" class="input-field" placeholder="Type a message here..."
                    autocomplete="off">
                  <button><i class="fab fa-telegram-plane"></i></button>
                </form>
              </section>
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

      window.onload = function () {

        // Line chart from swirlData for dashReport
        var ctx = document.getElementById("dashReport").getContext("2d");
        window.myLine = new Chart(ctx).Line(swirlData, {
          responsive: true,
          scaleShowVerticalLines: false,
          scaleBeginAtZero: true,
          multiTooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
        });

        // Pie Chart from doughutData
        var doctx = document.getElementById("chart-area3").getContext("2d");
        window.myDoughnut = new Chart(doctx).Pie(doughnutData, { responsive: true });

        // Dougnut Chart from doughnutData
        var doctx = document.getElementById("chart-area4").getContext("2d");
        window.myDoughnut = new Chart(doctx).Doughnut(doughnutData, { responsive: true });

      }
    </script>

  </body>

  </html>
<?php } ?>