<?php
if (isset($_POST['update'])) {
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $newpassword = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);

  $sql = "SELECT EmailId FROM tblusers WHERE EmailId=:email AND ContactNo=:mobile";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
  $query->execute();

  if ($query->rowCount() > 0) {
    $con = "UPDATE tblusers SET Password=:newpassword WHERE EmailId=:email AND ContactNo=:mobile";
    $chngpwd1 = $dbh->prepare($con);
    $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
    $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $chngpwd1->execute();
    echo "<script>alert('Your Password has been successfully changed');</script>";
  } else {
    echo "<script>alert('Invalid Email or Mobile Number');</script>";
  }
}
?>

<script type="text/javascript">
  function valid() {
    if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
      alert("New Password and Confirm Password fields do not match!");
      document.chngpwd.confirmpassword.focus();
      return false;
    }
    return true;
  }
</script>

<div class="modal fade" id="forgotpassword" tabindex="-1" role="dialog" aria-labelledby="forgotpasswordLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotpasswordLabel"
          style="font-size: calc(20px + 0.390625vw); margin-top: 2%; margin-left: 21%; ">Password Recovery</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="chngpwd" method="post" onSubmit="return valid();">
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Your Email address*" required>
          </div>
          <div class="form-group">
            <label for="mobile">Registered Mobile Number</label>
            <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Your Registered Mobile*"
              required>
          </div>
          <div class="form-group">
            <label for="newpassword">New Password</label>
            <input type="password" name="newpassword" class="form-control" id="newpassword" placeholder="New Password*"
              required>
          </div>
          <div class="form-group">
            <label for="confirmpassword">Confirm New Password</label>
            <input type="password" name="confirmpassword" class="form-control" id="confirmpassword"
              placeholder="Confirm Password*" required>
          </div>
          <button type="submit" name="update" class="btn btn-primary btn-block">Reset My Password</button>
        </form>
      </div>
    </div>
  </div>
</div>