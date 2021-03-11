<?php
    require_once("./bootstrap.php");
    if(isset($_GET["id"])){
        $cose = explode(",", $_GET["id"]);
        if($dbh->InCart($_SESSION["email"], $cose[0], $cose[1])[0]["Quantità"] > 1){
            $dbh->oneLessInCart($_SESSION["email"], $cose[0], $cose[1]);
        } else {
            $dbh->removeFromCart($_SESSION["email"], $cose[0], $cose[1]);
        }
    }
    header("Location: cart.php");
?>