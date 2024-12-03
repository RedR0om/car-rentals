<?php
  session_unset();
  session_destroy();
  header("Location: login1.php");
  exit();
?>
