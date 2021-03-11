<?php

    require_once("./bootstrap.php");
    $templateParams["titolo"]="Kaiyinda Handmade - Homepage";
    $templateParams["nome"]="template/home.php";
    $templateParams["articoliHome"] = $dbh->getSomeArticleByCategory("Anelli", 4);
    $templateParams["articoliCollane"] = $dbh->getSomeArticleByCategory("Collane", 4);
    $templateParams["categorieAnelli"] = $dbh->getImgForCategory("Anelli");
    $templateParams["categorieCollane"] = $dbh->getImgForCategory("Collane");
    $templateParams["categoriaMegaMenu"] = $dbh->getMegamenuCategories();
    $array = array();
    foreach($templateParams["categoriaMegaMenu"] as $categoria){
        $array[$categoria["Nome_Categoria"]] = $dbh->getSomeArticleByCategory($categoria["Nome_Categoria"],4);
    }
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
    require("template/base.php");


?>