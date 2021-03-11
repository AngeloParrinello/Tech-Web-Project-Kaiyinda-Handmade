<?php
    require_once("./bootstrap.php");
 
    $dbh->removeNotification($_SESSION["email"], $_GET["id"]);
   
    header("Location: login.php");

    
?>