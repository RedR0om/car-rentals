<?php
session_start(); // Start the session if it hasn't been started already

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $remember = isset($_POST['remember']) ? true : false; // Check if "Remember Password" checkbox is selected

  $sql = "SELECT EmailId, Password, FullName FROM tblusers WHERE EmailId=:email and Password=:password";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':password', $password, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetch(PDO::FETCH_OBJ); // Use fetch instead of fetchAll since you expect a single row

  if ($results) { // Check if a matching user was found
    $_SESSION['login'] = $results->EmailId; // Store the logged-in user's email in the session
    $_SESSION['fname'] = $results->FullName;

    // Log the login action
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $log_action = "User Login Successfully";
    $log_sql = "INSERT INTO user_logs (username, name, action, ip_address) VALUES (:email, :name, :action, :ip_address)";
    $log_query = $dbh->prepare($log_sql);
    $log_query->bindParam(':email', $email, PDO::PARAM_STR);
    $log_query->bindParam(':name', $results->FullName, PDO::PARAM_STR);
    $log_query->bindParam(':action', $log_action, PDO::PARAM_STR);
    $log_query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
    $log_query->execute();

    if ($remember) {
      // Set email and password cookies with 30 days expiration
      setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), '/');
      setcookie('remember_password', $password, time() + (30 * 24 * 60 * 60), '/');
    } else {
      // If "Remember Password" checkbox is not selected, clear the cookies
      setcookie('remember_email', '', time() - 3600, '/');
      setcookie('remember_password', '', time() - 3600, '/');
    }

    $currentpage = $_SERVER['REQUEST_URI'];
    echo "<script type='text/javascript'> document.location = '$currentpage'; </script>";
    exit(); // Exit the script after redirecting to the current page
  } else {
    echo "<script>alert('Invalid Details');</script>";
  }
}
?>


<div class="modal fade" id="loginform">
  <div class="modal-dialog" role="document" style="">
    <div class="modal-content" style=" width: 100%; background: #fff;">
      <div class="modal-header">
        <h3 class="modal-title" style="font-size: calc(25px + 0.390625vw); margin-top: 2%; margin-left: 40%; ">
          Login</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
            style="font-size: calc(50px + 0.390625vw); ">&times;</span></button>
      </div>
      <div class="modal-body" style=" background: #fff">
        <div class="row">
          <div class="login_wrap">
            <div class="col-md-12 col-sm-6">
              <form method="post" style="width: 100%;">
                <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Email address*"
                    value="<?php echo isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : ''; ?>">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Password*"
                    value="<?php echo isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : ''; ?>">
                </div>
                <div class="form-group checkbox">
                  <input type="checkbox" id="remember" name="remember" <?php echo isset($_COOKIE['remember_password']) ? 'checked' : ''; ?>>
                  <label for="remember">Remember Password</label>
                </div>
                <div class="form-group">
                  <input type="submit" name="login" value="Login" class="btn btn-block">
                </div>
                <div class="form-group">
                  <p>Don't have an account? <a href="#signupform" data-toggle="modal" data-dismiss="modal">Signup
                      Here</a></p>
                  <p style="">Don't Remember Password? <a href="#forgotpassword" data-toggle="modal"
                      data-dismiss="modal">Forgot Password</a></p>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>