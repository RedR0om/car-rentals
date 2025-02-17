<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Car Rental Portal | Chat</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
   <!-- Google Fonts -->
   <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700"
      rel="stylesheet">

    <!-- Bootstrap CSS File -->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Libraries CSS Files -->
    <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<header id="header" >
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo Section -->
            <div id="logo">
                <h1>
                    <img src="logo.png" alt="TMTCRS Logo" title="TMTCRS" width="40" />
                    <a href="index.php" id="body" class="scrollto" style="color: white;">
                        <span style="color: red;">T.M.T.C.R.S</span>
                    </a>
                </h1>
            </div>

            <!-- Navigation Section -->
            <nav id="nav-menu-container" class="ml-auto" >
                <ul class="nav-menu d-flex align-items-center">
                    <li class="menu-active"><a href="index.php" style="color: white;">Home</a></li>
                    <li class="menu-active"><a href="about.php" style="color: white;">About Us</a></li>
                    <li class="menu-active"><a href="car_list.php" style="color: white;">Car List</a></li>
                    <li class="menu-active"><a href="contact.php" style="color: white;">Contact</a></li>
                    <li class="menu-active"><a href="gallery.php" style="color: white;">Gallery</a></li>

                    <!-- Login / Register Button -->
                    <li>
                        <div class="header_info">
                            <?php if (strlen($_SESSION['login']) == 0) { ?>
                                <div class="pull-left ml-2">
                                    <!-- SEARCH FORM -->
                                    <form class="form-inline " action="search.php" method="post">
                                        <div class="input-group input-group-sm">
                                            <input class="form-control form-control-navbar" type="text" name="searchdata"
                                                placeholder="Search Car" aria-label="Search" required="true">
                                            <div class="input-group-append">
                                                <button class="btn btn-navbar" style="background-color: #fa2837;"
                                                    type="submit">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php } else { ?>
                                <!-- User Dropdown Menu -->
                                <?php
                                $email = $_SESSION['login'];
                                $sql = "SELECT FullName FROM tblusers WHERE EmailId=:email ";
                                $query = $dbh->prepare($sql);
                                
                                $query->bindParam(':email', $email, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                        ?>
                                <li class="menu-has-children">
                                    <a href="" style="color: #FF0000;"><?php echo htmlentities($result->FullName); ?></a>
                                    <ul>
                                        <li><a href="profile.php">Profile Settings</a></li>
                                        <li><a href="update_password.php">Update Password</a></li>
                                        <li><a href="my_booking.php">My Booking</a></li>
                                        <!-- <li><a href="chatting.php">Messaging</a></li> -->
                                        <li><a href="logout.php">Sign Out</a></li>
                                    </ul>
                                </li>
                            <?php }
                                }
                                ?>
                    <?php } ?>
        </div>
        </li>
        </ul>
        </nav><!-- #nav-menu-container -->
    </div>
    </div>
</header><!-- #header -->

<style>
    /* Styling for Header */
    #header {
        padding: 10px 0;
    }

    #logo h1 {
        margin: 0;
        font-size: 22px;
        color: #fff;
        font-weight: bold;
    }

    #nav-menu-container ul {
        list-style: none;
        margin: 0;
        padding: 0;
        align-items: center;
    }

    #nav-menu-container ul li {
        margin-right: 25px;
    }

    #nav-menu-container ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
    }

    .btn-banner {
        padding: 10px 20px;
        background-color: red;
        color: white;
        border: 1px solid red;
        transition: background-color 0.3s ease;
    }

    .btn-banner:hover {
        background-color: #fff;
        color: red;
    }

    /* Dropdown Styling */
    .menu-has-children ul {
        display: flex;
        background-color: #333;
        padding: 10px;
        border-radius: 5px;
    }

    .menu-has-children:hover ul {
        display: block;
    }

    .menu-has-children ul li {
        margin: 0;
    }

    .menu-has-children ul li a {
        color: white;
        padding: 5px 10px;
        display: block;
        text-decoration: none;
    }

    .menu-has-children ul li a:hover {
        background-color: red;
    }
</style>