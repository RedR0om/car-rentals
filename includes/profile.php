<?php
require 'connection.php';
if(isset($_POST["upload"])){
  $image = $_FILES['image']['tmp_name'];
  $image_name = $_FILES['image']['name'];
  $extension = pathinfo($image_name, PATHINFO_EXTENSION); // Get the file extension
  $random_name = uniqid() . '.' . $extension; // Generate a unique name for the image
  $target_folder = "uploads/"; // change this to your desired folder path
  $target_file = $target_folder . $random_name;
  if(move_uploaded_file($image, $target_file)) {
  // The image has been successfully moved to the target folder
    echo "Image uploaded successfully.";
  } else {
  // There was an error moving the image to the target folder
    echo "Error uploading image.";
  }

  $email=$_SESSION['login'];
  $sql = "UPDATE tblusers SET image = :image WHERE EmailId=:email";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email',$email,PDO::PARAM_STR);
  $query->bindParam(':image', $target_file);
  $query->execute();

  if($query->rowCount() > 0)
  {
    echo "<script>alert('Image uploaded successfully.');</script>";
echo "<script type='text/javascript'> document.location = '$currentpage'; </script>";
  }
  else 
  {
    echo "<script>alert('Error uploading image. Please try again.');</script>";
  }
}
?>


<script>
function previewImage() {
    var preview = document.getElementById('preview');
    var file = document.getElementById('image').files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
}
</script>


<div class="modal fade" id="profile">
    <div class="modal-dialog" role="document" style="margin-right: 2000px;">
        <div class="modal-content" style=" margin-left: 350px; width: 700px;">
            <div class="modal-header">
                <h3 class="modal-title" style="margin-left: 290px;">Upload Valid ID</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                        style="font-size: 50px;">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="login_wrap">
                        <div class="col-md-12 col-sm-6">
                            <form method="post" name="signup" onSubmit="return valid();" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="image">Choose Image:</label>
                                    <input type="file" class="form-control-file" id="image" name="image"
                                        onchange="previewImage();" required>
                                    <center>
                                        <img id="preview" style="width: 150px; height: 150px; border-radius:50%;">

                                    </center>
                                </div>

                                <button type="submit" name="upload">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">

            </div>
        </div>
    </div>
</div>