<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['signup'])) {

    $gender = $_POST['gender'];
    $fname = $_POST['fullname'];
    $dob = $_POST['dob'];
    $street = $_POST['street'];
    $brgy = $_POST['brgy'];
    $city = $_POST['city'];
    $email = $_POST['emailid'];
    $mobile = $_POST['mobileno'];
    $password = md5($_POST['password']);

    // PHPMailer setup
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'temporental2020@gmail.com';
        $mail->Password = 'poxmfhhclnpaqvip';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('temporental2020@gmail.com', 'temporental2020@gmail.com');
        $mail->addAddress($email, $fname);
        $mail->isHTML(true);
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $mail->Subject = 'Email verification';
        $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
        $mail->send();

        echo "<script>alert('Registration successful. Please check your email for verification.');</script>";
        echo "<script>window.location.href='email-verification.php?email=" . $email . "';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Something went wrong. Please try again');</script>";
    }

    $image = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $random_name = uniqid() . '.' . $extension;
    $target_folder = "uploads/";
    $target_file = $target_folder . $random_name;

    if (move_uploaded_file($image, $target_file)) {
        echo "Image uploaded successfully.";
    } else {
        echo "Error uploading image.";
    }

    // Insert user data into the database
    $sql = "INSERT INTO tblusers(FullName, EmailId, ContactNo, Password, Gender, dob, Address, City, Country, image, verification_code, email_verified_at) 
            VALUES (:fname, :email, :mobile, :password, :gender, :dob, :street, :brgy, :city, :image, :verification_code, NULL)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':dob', $dob, PDO::PARAM_STR);
    $query->bindParam(':street', $street, PDO::PARAM_STR);
    $query->bindParam(':brgy', $brgy, PDO::PARAM_STR);
    $query->bindParam(':city', $city, PDO::PARAM_STR);
    $query->bindParam(':image', $target_file);
    $query->bindParam(':verification_code', $verification_code);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if ($lastInsertId) {
        // Log registration action
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $log_action = "User Registration Successfully";
        $log_sql = "INSERT INTO user_logs (username, name, action, ip_address) 
                    VALUES (:email, :fname, :action, :ip_address)";
        $log_query = $dbh->prepare($log_sql);
        $log_query->bindParam(':email', $email, PDO::PARAM_STR);
        $log_query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $log_query->bindParam(':action', $log_action, PDO::PARAM_STR);
        $log_query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
        $log_query->execute();

        echo "<script>alert('Registration successful. Now you can log in');</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again');</script>";
    }
}
?>



<script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'emailid=' + $("#emailid").val(),
            type: "POST",
            success: function (data) {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function () { }
        });
    }
</script>
<script type="text/javascript">
    function valid() {
        if (document.signup.password.value != document.signup.confirmpassword.value) {
            alert("Password and Confirm Password Field do not match  !!");
            document.signup.confirmpassword.focus();
            return false;
        }
        return true;
    }
</script>

<script>
    function previewImage() {
        var preview = document.getElementById('preview');
        var file = document.getElementById('image').files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
</script>

<div class="modal fade" id="signupform">

    <div class="modal-dialog" role="document">

        <div class="modal-content" style=" width: 100%; background: #fff;">

            <div class="modal-header">
                <h3 class="modal-title" style="font-size: calc(25px + 0.390625vw); margin-top: 2%; margin-left: 35%; ">
                    Sign Up</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                        style="font-size: calc(50px + 0.390625vw); ">&times;</span></button>
            </div>

            <div class="modal-body"
                style=" background: #fff; overflow-y: auto; max-height: calc(100vh - 200px); overflow-y: auto;">

                <div class="row">

                    <div class="signup_wrap">

                        <div class="col-md-12 col-sm-6">

                            <form method="post" name="signup" onSubmit="return valid();" enctype="multipart/form-data"
                                style="width: 100%;">

                                <div class="form-group">
                                    <label for="image">Choose Image:</label>
                                    <input type="file" class="form-control-file" id="image" name="image"
                                        onchange="previewImage();" required>
                                    <center>
                                        <img id="preview"
                                            style="padding: 3%; width: 50%; height: 50%; border-radius:50%;">

                                    </center>
                                </div>
                                <!-- FORM FOR NAME -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="text" class="form-control" name="fullname" placeholder="Full Name"
                                        required="required">

                                </div>
                                <!-- FORM FOR GENDER -->
                                <div class="form-group" style="width: 100%; ">

                                    <select class="selectpicker" name="gender" required>
                                        <option class="gender"> Gender<br><br></option>
                                        <?php $ret = "select id,Gender from tblgender";
                                        $query = $dbh->prepare($ret);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {
                                                ?>
                                                <option value="<?php echo htmlentities($result->id); ?>">
                                                    <?php echo htmlentities($result->Gender); ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="date" class="form-control" name="dob" required="required">
                                </div>

                                <!-- FORM FOR MOBILE NO. -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="tel" class="form-control" name="mobileno" placeholder="Mobile Number"
                                        minlength="11" maxlength="11" required="required">

                                </div>

                                <!-- FORM FOR STREET NO. -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="text" class="form-control" name="street" placeholder="Street"
                                        required="required">
                                </div>
                                <!-- FORM FOR BARANGGAY -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="text" class="form-control" name="brgy" placeholder="Barangay"
                                        required="required">

                                </div>

                                <!-- FORM FOR CITY -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="text" class="form-control" name="city" placeholder="City"
                                        required="required">

                                </div>

                                <!-- FORM FOR EMAIL -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="email" class="form-control" name="emailid" id="emailid"
                                        onBlur="checkAvailability()" placeholder="Email Address" required="required">
                                    <span id="user-availability-status" style="font-size:12px;"></span>

                                </div>

                                <!-- FORM FOR PASSWORD -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="password" class="form-control" name="password" placeholder="Password"
                                        required="required">

                                </div>

                                <!-- FORM FOR CONFIRM PASS -->
                                <div class="form-group" style="width: 100%; ">

                                    <input type="password" class="form-control" name="confirmpassword"
                                        placeholder="Confirm Password" required="required">

                                </div>

                                <!-- FORM FOR TERMS -->
                                <div class="form-group checkbox" style="width: 100%; ">

                                    <input type="checkbox" id="terms_agree" required="required">
                                    <label for="terms_agree">I Agree with <a href="#" data-toggle="modal"
                                            data-target="#exampleModalLong">Terms and Conditions</a></label>

                                </div>

                                <!-- TERMS -->
                                <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="
    max-width: calc(500vh - 200px);">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content" style=" width: 100%; background: rgb(33,150,243);">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle"
                                                    style="font-size: 25px;">Terms And Condition
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"></button>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="background: rgb(33,150,243); background: linear-gradient(180deg, rgba(33,150,243,1) 0%, rgba(255,255,255,1) 100%); 
    max-height: calc(100vh - 200px);
    overflow-y: auto;">
                                                <p>All Visitors/Users/Customers Of Paras Car Rental are Permitted to
                                                    Visit/Use/Book On And Via Our Website Solely Subject to full
                                                    acceptance of these Terms AND Conditions. Please Note That, All
                                                    links herein are an Integral Part of These Terms And Conditions. We
                                                    rerserve the right, at our discretion, to update and/or revise the
                                                    conditions and terms herein. Therefore, Please Check Often For Any
                                                    Updates And Amendments.<br><br><b>TERMS OF USE</b><br>

                                                    Subject to all the terms and conditions contained herein and in
                                                    consideration thereof, the RENTER acknowledges and agrees to the
                                                    following:
                                                    <br><br>
                                                    <b>1. PRIVACY POLICY:</b> Paras Car Rental is committed to
                                                    protecting your privacy. We promise to keep all the information that
                                                    you share with us confidential. We only collect personal information
                                                    from customers in order to process billing, fulfill service and
                                                    provide a personal and customised experience. We do not share your
                                                    information with any outside parties.<br>

                                                    <b>2. CUSTOMER IDENTIFICATION POLICY: </b>Paras Car Rental collects
                                                    and processes your personal information, which refers to any freely
                                                    given, specific, informed indication of will, whereby you agree to
                                                    the collection and processing of your personal, sensitive or
                                                    privileged information. The Company processes your personal
                                                    information in accordance with the law, this Privacy Policy, Terms
                                                    and Conditions, and other legal instruments which you may have
                                                    entered into with Paras Car Rental.<br>

                                                    <b>3. RESERVATION:</b> Paras Car Rental will require 50% reservation
                                                    fee, depending on the service availed.<br>

                                                    <b>4. CANCELLATION POLICY:</b>
                                                    *Cancellation to rental schedule or No Show shall be charged 100% of
                                                    the total rental.<br>

                                                    <b>5. REFUND AND FINANCE</b> charges will be shouldered by the
                                                    RENTER.<br>
                                                    <b>6. TERMS OF PAYMENT</b> – Cash, Credit Card or Prepaid Payment
                                                    Only.<br>
                                                    <b>7. All UNCONSUMED</b> will not be refunded.<br>
                                                    <b>8. RENTAL REQUIREMENTS</b> will be collected on the rental date.
                                                    Lacking requirement will forfeit your reservation. Our
                                                    representative may ask for an additional Identification Card for
                                                    security purposes.<br>
                                                    <b>9. RENTAL HOURS</b> – A day’s rental is equivalent to 24 hours.
                                                    Excess hours shall be charged at 1/6 of the daily rate. If rental
                                                    covers 6 hours or more (up to 24 hours) the daily rate shall be
                                                    applied. Weekly or monthly rentals cut-off time is 12:00 midnight.
                                                    For weekly or long period rentals, every endeavor will be made to
                                                    retain the same car and chauffeur. During such period Paras Car
                                                    Rental reserves the right to substitute any other similar category
                                                    car and chauffeur according to the exigencies of the services (e.g.
                                                    Car due for check-up shall be recalled and replaced by any available
                                                    unit.)<br>

                                                    <b>10. Gasoline, Parking Fees, Traffic Violations and Toll Fees</b>
                                                    are shouldered by the Renter.<br>

                                                    <b>11. RENTER </b>shall be liable to pay interest at the rate of
                                                    twenty-four percent (24%) per anum in case of delay in payment of
                                                    rental fee within the period provided. Immediately upon return of
                                                    the vehicle. Should the matter be referred to a lawyer, it is agreed
                                                    that the RENTER shall pay attorney’s fees at the rate of 25% of the
                                                    amount due but in the case shall it be less than P5,000.00, in
                                                    addition to the cost of suit.<br>

                                                    <b>12. RENTER</b> hereby consents that in case of suit arising out
                                                    of or in connection with the agreement, the venue is agreed to be at
                                                    the place where the unit in question was initially transacted, i.e.
                                                    the Paras Car Rental branch/office where the unit was rented
                                                    from.<br><br>

                                                    <b>That said vehicle shall not be operated in the following cases to
                                                        with:</b><br>

                                                    *To travel to the mountain trail and other areas where roads are
                                                    impassable for cars or van and to ferry the rented vehicle.<br>
                                                    *High speed or negligent driving that provokes vehicular accident,
                                                    damage or breakdown.<br>
                                                    *To carry passengers or property or other forms of personal
                                                    belongings or goods for consideration.<br>
                                                    *Carrying items with foul smell or that can stain the interior of
                                                    the vehicle.<br>
                                                    *By any person who has a pending case or charges otherwise convicted
                                                    of a crime against the public property or to carry persons with
                                                    dubious character.<br>
                                                    *To propel or tow any other vehicles, trailer or other heavy things
                                                    with or without wheels.
                                                    *By any person who is or have a symptom or previous record of mental
                                                    illness, or by any person who is under the influence of liquor and
                                                    drugs.<br>
                                                    *To transport goods in violation of customs rules and regulations or
                                                    in any other manner deemed illegal.<br>
                                                    *By any person other than the RENTER or declared driver in this
                                                    agreement, unless Paras Car Rental permission is first obtained in
                                                    writing. It is understood that in the absence of written consent
                                                    from the Paras Car Rental the RENTER is ipso facto deemed to have
                                                    violated this agreement.<br>
                                                    *A person who is less than 23 years of age, whether he or she has a
                                                    driver’s license or not.

                                                <p style="float: right;"><b>Click Anywhere to Close</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- SUBMIT BUTTON  -->
                                <center>
                                    <div class="form-group" style="width: 50%;">
                                        <input type="submit" value="Sign Up" name="signup" id="submit"
                                            class="btn btn-block">
                                    </div>
                                </center>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>