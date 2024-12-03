<header id="header" >
    <div class="container" style="float: left; margin-left: 10%;">
        <div class="row "style="width: 100%; ">

            <div id="logo" class="pull-left" style="margin-top: -1.5%; border: 2px solid black; padding: 1%;">
                <h1 style="font-size: 2.5vw;"><a href="index.php" id="body" class="scrollto"><span style="color: red;">Tempo | </span>Rental</a>
                </h1>
                <!-- <a href="#body"><img src="img/logo.png" alt="" title="" /></a>-->
            
            <div class="pull-left ml-2">
                <!-- SEARCH FORM -->
                <form class="form-inline " action="search.php" method="post" style="width: 100%; ">
                    <div class="input-group input-group-sm">
                        <input  style="font-size: 1.5vw; margin-top: 10%; height: 100%;" class="form-control form-control-navbar" type="text" name="searchdata"
                            placeholder="Search Car" aria-label="Search" required="true">
                        <div class="input-group-append">
                            
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
            <nav id="nav-menu-container">
               
                <ul class="nav-menu"  style="margin-right: -2%; margin-top: 1%; width: 100%; float: right;">


                    <li class="menu-active"><a href="index.php"  style="font-size: 1.2vw; ">Home</a></li>
                    <li><a href="about.php"  style="font-size: 1.2vw; ">About Us</a></li>
                    <li><a href="car_list.php"  style="font-size: 1.2vw; ">Car list</a></li>
                    <li><a href="contact.php"  style="font-size: 1.2vw; ">Contact</a></li>
                    <li><a href="gallery.php"  style="font-size: 1.2vw; ">Gallery</a></li>
                    <div class="header_info" style="">
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
                    <li class="menu-has-children"  ><a href=""style="font-size: 1.2vw;"><?php echo htmlentities($result->FullName);?></a>
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