<header id="header">
    <div class="container">
        <div class="row">

            <div id="logo" class="pull-left">
                <h1><a href="index.php" id="body" class="scrollto"><span style="color: red;">Tempo | </span>Rental</a>
                </h1>
                <!-- <a href="#body"><img src="img/logo.png" alt="" title="" /></a>-->
            </div>
            <div class="pull-left ml-2">
                <!-- SEARCH FORM -->
                <form class="form-inline " action="search.php" method="post">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="text" name="searchdata"
                            placeholder="Search Car" aria-label="Search" required="true">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" style="background-color: black;" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <nav id="nav-menu-container">
                <ul class="nav-menu">


                    <li class="menu-active"><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="car_list.php">Car list</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <div class="header_info" style="margin-top: 1.3%; padding-left: 1%; width: 165px; ">
                        <div class="header_widgets">
                            <?php   if(strlen($_SESSION['login'])==0)
  {
?>
                            <!-- <li> <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login / Register</a></li> -->
                            <?php }
else{

 } ?>
                        </div>
                    </div>
                    <?php   if(strlen($_SESSION['login'])!=0)
      { 
        ?>
                    <?php 
        $email=$_SESSION['login'];
        $sql ="SELECT FullName FROM tblusers WHERE EmailId=:email ";
        $query= $dbh -> prepare($sql);
        $query-> bindParam(':email', $email, PDO::PARAM_STR);
        $query-> execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0)
        {
          foreach($results as $result)
          {
            ?>
                    <li class="menu-has-children"><a href=""><?php echo htmlentities($result->FullName);?></a>
                        <ul>
                            <li><a href="profile.php">Profile Settings</a></li>
                            <li><a href="update_password.php">Update Password</a></li>
                            <li><a href="my_booking.php">My Booking</a></li>
                            <li><a href="chatting.php">Messaging</a></li>
                            <li><a href="logout.php">Sign Out</a></li>
                        </ul>
                    </li>
                    <?php 
          }
        }
      } ?>
                </ul>
            </nav><!-- #nav-menu-container -->
        </div>
    </div>
</header><!-- #header -->