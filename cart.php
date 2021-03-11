<?php
    require_once("./bootstrap.php");

    $templateParams["nome"]="template/cartTemplate.php";
    $templateParams["titolo"]="Kaiyinda Handmade - Carrello";
    $templateParams["categoriaMegaMenu"] = $dbh->getMegamenuCategories();
    $array = array();
    //getImageArticleById($id)
    //notifiche
    if(isUserLoggedIn()){
        $temp = $dbh->getNumberOfNotificationUnread($_SESSION["email"])[0]["COUNT(notifica.Id_Notifica)"];
        $temp2 = $dbh->getNumberOfItemInCart($_SESSION["email"])[0]["COUNT(carrello.Codice_Articolo)"];
        if($temp2 > 0){
            $templateParams["cartBedge"] = $temp2;
        }
        $templateParams["carrellata"] = $dbh->getArticleInCart($_SESSION["email"]);
        $templateParams["subtotale"] = 0;
        foreach($templateParams["carrellata"] as $articolo){
            $templateParams["subtotale"] = $templateParams["subtotale"] + $articolo["Prezzo"];
        }
        $templateParams["totale"] = $templateParams["subtotale"] + 6.90;
        if($temp > 0){
            $templateParams["bedge"] = $temp;
        }

        $templateParams["indirizzi"] = $dbh->getAddressUser($_SESSION["email"]);

    } else {
        header("Location: login.php");
    }
    
    foreach($templateParams["categoriaMegaMenu"] as $categoria){
        $array[$categoria["Nome_Categoria"]] = $dbh->getSomeArticleByCategory($categoria["Nome_Categoria"],4);
    }

    
    require("template/base.php");


?>