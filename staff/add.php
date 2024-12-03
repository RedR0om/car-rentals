<?php
require 'dbc.php';
if (isset($_POST['btn_upload'])) {
    $filetmp = $_FILES["file_img"]["tmp_name"];
    $filename = $_FILES["file_img"]["name"];
    $filetype = $_FILES["file_img"]["type"];
    $filepath = "img/" . $filename;
    $filetitle = $_POST['img_title'];

    move_uploaded_file($filetmp, $filepath);

    $stmt = $dbc->prepare("INSERT INTO tbl_photos (img_name, img_type, img_path, img_title) VALUES (:iname, :itype, :ipath, :ititle) ");
    $stmt->bindValue(':iname', $filename);
    $stmt->bindValue('itype', $filetype);
    $stmt->bindValue('ipath', $filepath);
    $stmt->bindValue('ititle', $filetitle);
    if ($stmt->execute()) {
        $currentpage = $_SERVER['REQUEST_URI'];
        echo "<script type='text/javascript'> document.location = '$currentpage'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/modernizr.js"></script>

    <title>Car Rental Portal |Staff Add Photo</title>

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

<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">

                        <h2 class="page-title">Manage Gallery</h2>

                        <!-- Zero Configuration Table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Add Photo</div>
                            <div class="panel-body">

                                <nav class="top-bar" data-topbar role="navigation">
                                    <section class="top-bar-section">
                                        <!-- Right Nav Section -->
                                        <ul class="right">
                                            <li><a href="upload-photo.php" data-reveal-id="uploadModal"
                                                    data-reveal-ajax="true">Add Photo</a></li>
                                            <li class="divider"></li>

                                        </ul>
                                    </section>
                                </nav>
                                <br />
                                <!--Content goes here-->
                                <div class="row">
                                    <div class="large-12 columns">
                                        <?php
                                        if (isset($_GET['success'])) {
                                            if ($_GET['success'] == "yes") { ?>
                                                <div class="row">
                                                    <div class="small-6 large-6 columns">
                                                        <div data-alert class="alert-box success radius ">
                                                            Image "<?= $_GET['title']; ?>" uploaded successfully.
                                                            <a href="#" class="close">&times;</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="row">
                                                    <div class="small-6 large-6 columns">
                                                        <div data-alert class="alert-box alert radius ">
                                                            There was a problem uploading the image.
                                                            <a href="#" class="close">&times;</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                require 'dbc.php';
                                                if (isset($_POST['delete_image'])) {
                                                    $image_id = $_POST['image_id'];
                                                    $dbc->query("DELETE FROM tbl_photos WHERE img_id = $image_id");
                                                    echo '<p>Image deleted successfully!</p>';
                                                }
                                                $stmt = $dbc->query("SELECT * FROM tbl_photos ORDER by img_id ASC");
                                                foreach ($stmt as $img) {
                                                    list($width, $height) = getimagesize($img['img_path']);
                                                    $aspect_ratio = $width / $height;
                                                    $new_width = 200;
                                                    $new_height = $new_width / $aspect_ratio;
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?= $img['img_path']; ?>">
                                                                <img style="width: <?= $new_width ?>px; height: <?= $new_height ?>px"
                                                                    data-caption="<?= $img['img_title']; ?>"
                                                                    src="<?= $img['img_path']; ?>">
                                                            </a>
                                                        </td>

                                                        <td>
                                                            <form method="post">
                                                                <input type="hidden" name="image_id"
                                                                    value="<?= $img['img_id'] ?>">
                                                                <button type="submit" name="delete_image">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--End content-->
                                <!--MODALS-->
                                <div id="uploadModal" class="reveal-modal tiny" data-reveal></div>
                                <!--END MODALS-->
                            </div>
                            <script src="js/foundation.min.js"></script>
                            <script src="js/sticky-footer.js"></script>
                            <script src="js/foundation/foundation.topbar.js"></script>
                            <script src="js/foundation/foundation.reveal.js"></script>
                            <script src="js/foundation/foundation.abide.js"></script>
                            <script>
                                $(document).foundation();
                            </script>
                            <a href="dashboard.php"><input type="button" class="w3-button w3-blue" value="Go Back"> </a>
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
</body>

</html>