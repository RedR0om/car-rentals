<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT UserName, Password, Name FROM tbladmin WHERE UserName=:username";
  $query = $dbh->prepare($sql);
  $query->bindParam(':username', $username, PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetch(PDO::FETCH_OBJ);

  // Check if user exists and password matches
  if ($result && password_verify($password, $result->Password)) {
    $_SESSION['alogin'] = $_POST['username'];
    $_SESSION['name'] = $result->Name; // Save the name in session

    // Get the current IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Insert login log entry into the logs table
    $log_action = "Login successful";
    $log_sql = "INSERT INTO user_logs (username, name, action, ip_address) 
                VALUES (:username, :name, :action, :ip_address)";
    $log_query = $dbh->prepare($log_sql);
    $log_query->bindParam(':username', $_SESSION['alogin'], PDO::PARAM_STR);
    $log_query->bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
    $log_query->bindParam(':action', $log_action, PDO::PARAM_STR);
    $log_query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
    $log_query->execute();

    echo "<script type='text/javascript'> alert('Login successful.'); document.location = 'dashboard.php'; </script>";
  } else {
    echo "<script type='text/javascript'> alert('Invalid username or password. Please try again.'); </script>";
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Car Rental Portal | Admin Login</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-social.css">
  <link rel="stylesheet" href="css/bootstrap-select.css">
  <link rel="stylesheet" href="css/fileinput.min.css">
  <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
  <link rel="stylesheet" href="css/style.css">

  <style>
    body {
      /* Add a background image */
      background-size: cover;
      background-repeat: no-repeat;
      font-family: Arial, sans-serif;
    }

    .login-page {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      /* Dark overlay */
    }

    .form-content {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
      max-width: 400px;
      width: 100%;
    }

    .form-content h1 {
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    .form-content .form-control {
      border-radius: 5px;
    }

    .form-content .btn-primary {
      background-color: #007bff;
      border: none;
      padding: 10px;
      border-radius: 5px;
      font-weight: bold;
    }

    .form-content .btn-primary:hover {
      background-color: #0056b3;
    }

    .form-content .logo {
      display: block;
      margin: 0 auto 20px;
      width: 80px;
    }
  </style>
</head>

<body>

  <div class="login-page">
    <div class="form-content">
      <img src="logo.png" alt="Logo" class="logo"> <!-- Add your logo here -->
      <h1 class="text-center">Admin Login</h1>
      <form method="post">
        <div class="form-group">
          <label for="username" class="text-uppercase">Username</label>
          <input type="text" placeholder="Username" name="username" class="form-control mb" required>
        </div>
        <div class="form-group">
          <label for="password" class="text-uppercase">Password</label>
          <input type="password" placeholder="Password" name="password" class="form-control mb" required>
        </div>
        <button class="btn btn-primary btn-block" name="login" type="submit">LOGIN</button>
      </form>
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