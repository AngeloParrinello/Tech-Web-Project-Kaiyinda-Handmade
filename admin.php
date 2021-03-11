<?php 
    require_once("./bootstrap.php");
    $templateParams["titolo"]="Kaiyinda Handmade - Admin";
    $templateParams["nome"]="template/adminTemplate.php";
    $idcategoria = -1;
    $templateParams["categorietotali"] = $dbh->getAllCategories();
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

    foreach($templateParams["categorietotali"] as $categorie){
        $array = $dbh->getArticleByCategory($categorie["Nome_Categoria"]);
        $templateParams[$categorie["Nome_Categoria"]] = $array;
    }

    if(isset($_GET["id"])){
        $idcategoria=$_GET["id"];
    }
    $templateParams["categoriaMegaMenu"] = $dbh->getMegamenuCategories();
    $array = array();
    foreach($templateParams["categoriaMegaMenu"] as $categoria){
        $array[$categoria["Nome_Categoria"]] = $dbh->getSomeArticleByCategory($categoria["Nome_Categoria"],4);
    }
    $templateParams["imgCategoria"] = $dbh->getImgForCategory($idcategoria);
    $templateParams["articoliCategorie"] = $dbh->getArticleByCategory($idcategoria);


    $temp = $dbh->getNumberOfNotificationUnread($_SESSION["email"])[0]["COUNT(notifica.Id_Notifica)"];
        if($temp > 0){
            $templateParams["bedge"] = $temp;
        }
        $temp2 = $dbh->getNumberOfItemInCart($_SESSION["email"])[0]["COUNT(carrello.Codice_Articolo)"];
        if($temp2 > 0){
            $templateParams["cartBedge"] = $temp2;
        }


    require("template/base.php");




?>