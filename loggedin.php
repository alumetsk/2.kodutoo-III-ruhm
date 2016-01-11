<?php  

    if(isset($_GET["logout"])){
     session_destroy();
      header("Location: index.php");
  }
  ?>
 <p><a href="?logout=1"> Logout </a>