<?php
    require_once("./bootstrap.php");
    //roba base
    $templateParams["titolo"]="Kaiyinda Handmade - Login";
    $templateParams["categoriaMegaMenu"] = $dbh->getMegamenuCategories();
    $array = array();
    //notifiche
    if(isUserLoggedIn()){
        $temp = $dbh->getNumberOfNotificationUnread($_SESSION["email"])[0]["COUNT(notifica.Id_Notifica)"];
        if($temp > 0){
            $templateParams["bedge"] = $temp;
        }
        $temp2 = $dbh->getNumberOfItemInCart($_SESSION["email"])[0]["COUNT(carrello.Codice_Articolo)"];
        if($temp2 > 0){
            $templateParams["cartBedge"] = $temp2;
        }
    }

    foreach($templateParams["categoriaMegaMenu"] as $categoria){
        $array[$categoria["Nome_Categoria"]] = $dbh->getSomeArticleByCategory($categoria["Nome_Categoria"],4);
    }
    if(isset($_POST["email"]) && isset($_POST["password"])){
        $login_result = $dbh->checkUser($_POST["email"],$_POST["password"]);
        if(count($login_result)==0){
            $templateParams["erroreLogin"] = "Credenziali errate, prego riprova";
        } else{
            registerLoggedUser($login_result[0]);
        }
    }

    if(isUserLoggedIn()){
        if($_SESSION["admin"] == 0){
            $templateParams["nome"]="template/user.php";
            $templateParams["indirizziUser"] = $dbh->getAddressUser($_SESSION["email"]);
            $templateParams["ordiniUser"] = $dbh->getOrdersUser($_SESSION["email"]);
            $templateParams["notificheUser"] = $dbh->getNotification($_SESSION["email"]);
            $dbh->readAllNotifications($_SESSION["email"]);
            if( !empty($_POST["newpassword"]) && !empty($_POST["confermaPassword"]) ){
                
                if( strcmp($_POST["newpassword"], $_POST["confermaPassword"]) == 0 ) {
                    $dbh->changePassword($_POST["newpassword"], $_SESSION["email"]);
                    //notifica
                    $i = $dbh->getNotificationNextId($_SESSION["email"])[0]["Id_Notifica"];
                    if(empty($i)){
                        $i = 0;
                    }
                    $dbh->createNotification($_SESSION["email"], $i + 1, "Modifica Password","La tua password è stata cambiata", "Hai modificato la password correttamente");
                    header("Location: login.php");
                } else {
                    $templateParams["pswwrong"] = "Le password non sono uguali";
                }
            }

        } else {
            header("Location: admin.php");
        }
    } else{
        $templateParams["nome"]="template/login-form.php";
    }

                                    
    require("template/base.php");
?>