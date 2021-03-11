<?php
    require_once("./bootstrap.php");
    //roba nel carrello
    $templateParams["articoloni"] = $dbh->getArticleInCart($_SESSION["email"]);
    //routine per prendere l'ID giusto

    $templateParams["idordine"] = $dbh->getIdOrder($_SESSION["email"]);
    $aiuto = 1;
    if(empty($templateParams["idordine"])){
        $aiuto = 1;
    }else {
        $templateParams["idordine"]= $templateParams["idordine"][0];
        $templateParams["idordine"]["Id_Ordine"]++;
        $aiuto = $templateParams["idordine"]["Id_Ordine"];
    }
    //creo l'ordine
    $dbh->createOrder($aiuto,$_SESSION["email"], $_GET["id"]);
    //per ogni articolo creo un dettaglio ordine
    foreach($templateParams["articoloni"] as $artico){
        $dbh->createOrderDetail($aiuto, $artico["Nome_Articolo"], $artico["Taglia"], $artico["Quantità"], $_SESSION["email"]);
        $dbh->updateStocks($artico["Quantità"], $artico["Nome_Articolo"]);
        $dbh->updatePunteggio($artico["Quantità"], $artico["Nome_Articolo"]);
    }
    //elimino la roba nel carrello
    $dbh->resetCart($_SESSION["email"]);
    //notifico
    $prova = $dbh->getNotificationNextId($_SESSION["email"]);
    if(!empty($prova)){
        $i = $prova[0]["Id_Notifica"];
    } else {
        $i = 0;
    }
    $dbh->createNotification($_SESSION["email"], $i + 1,"Ordine Ricevuto", "L'ordine n ".$templateParams["idordine"]["Id_Ordine"]. " del ".date("d/m/Y")." è stato preso in consegna", "L'ordine comprende sempre tutto quello che c'è nel carrello");
    //cerco admin
    $templateParams["admin"] = $dbh->getAllAdmin();
    //invio notifica ad admin
    foreach($templateParams["admin"] as $admin){
        $prova = $dbh->getNotificationNextId($admin["E_mail"]);
        if(!empty($prova)){
            $i = $prova[0]["Id_Notifica"];
        } else {
            $i = 0;
        }
        $dbh->createNotification($admin["E_mail"], $i + 1,"Ordine Ricevuto", "L'ordine n ".$templateParams["idordine"]["Id_Ordine"]. " del ".date("d/m/Y")." è stato preso in consegna", "L'ordine comprende sempre tutto quello che c'è nel carrello, ed è stata effettuata da ".$_SESSION["email"]);
    }
    header("Location: ./");
?>