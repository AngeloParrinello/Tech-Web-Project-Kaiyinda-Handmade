<?php
    require_once("./bootstrap.php");
    $idarticolo = -1;
    if(isset($_GET["id"])){
        $idarticolo=$_GET["id"];
    }
    $templateParams["titolo"]="Kaiyinda Handmade - Articolo";
    $templateParams["nome"]="template/articleTemplate.php";
    $templateParams["articoloImmagini"] = $dbh->getImageArticleById($idarticolo)[0];
    $templateParams["articoloSingolo"] = $dbh->getArticleById($idarticolo)[0]; 
    $templateParams["categoriaMegaMenu"] = $dbh->getMegamenuCategories();
    $array = array();

    //notifiche
    if(isUserLoggedIn()){

        if(!empty($_POST["quantity"]) && !empty($_POST["taglia"])){
            //aggiungo al carrello
            if( (count($dbh->inCart($_SESSION["email"], $templateParams["articoloSingolo"]["Nome_Articolo"], $_POST["taglia"]))) > 0){
                $dbh->updateQuantityInCart($_POST["quantity"], $_SESSION["email"], $templateParams["articoloSingolo"]["Nome_Articolo"], $_POST["taglia"]);
            } else {
                $dbh->addArticleToCart($_SESSION["email"], $templateParams["articoloSingolo"]["Nome_Articolo"], $_POST["taglia"], $_POST["quantity"]);
            }

        }

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

    
    require("template/base.php");


?>