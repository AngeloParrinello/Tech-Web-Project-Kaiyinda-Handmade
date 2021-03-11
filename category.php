<?php

    require_once("./bootstrap.php");
    $templateParams["nome"]="template/categoriesTemplate.php";
    $templateParams["titolo"]="Kaiyinda Handmade - Categoria";
    $idcategoria = -1;

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
    
    require("template/base.php");


?>