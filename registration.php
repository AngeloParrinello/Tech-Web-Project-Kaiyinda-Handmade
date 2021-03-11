<?php 
    require_once("./bootstrap.php");
    $templateParams["nome"]="template/registration-form.php";
    if(!empty($_POST["email"]) && !empty($_POST["password1"]) && !empty($_POST["password2"])){
        if(!strcmp($_POST["password1"], $_POST["password2"])){
            $dbh->addNewUsr($_POST["email"], $_POST["password1"]);
            header("Location: login.php");
        } else {
            $templateParams["err"] = "le password inserite non coincidono";
        }
    }   
    require("template/base.php");




?>