<?php
    require_once("./bootstrap.php");
    

    $vuoto = 0;
    $today = strtotime(date("Y-m-d"));
    foreach($templateParams["ordiniUser"] as $check){
        if( (strtotime($check["Data_Consegna"]) > $today)  && ($check["Progressivo"] == $_GET["id"]) ){
            $vuoto = 1;
            break;
        }
    }

    if($vuoto != 1){
        $dbh->removeAddress($_SESSION["email"], $_GET["id"]);
    }

    header("Location: login.php");

    
?>