<?php
    require_once("./bootstrap.php");
    //roba base
    $templateParams["titolo"]="Kaiyinda Handmade - Address";
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
    $templateParams["nome"] ="template/address-input.php";
    
    
if(isset($_GET["id"])){
    
    $templateParams["infobase"] = $dbh->findAddress($_SESSION["email"], $_GET["id"])[0];

    if ( !empty($_POST["Nome"]) && !empty($_POST["Via"]) && !empty($_POST["NumeroCivico"]) && !empty($_POST["Citta"]) && !empty($_POST["Provincia"]) && !empty($_POST["CAP"])) {
        $dbh->modifyAddress($_POST["Via"], $_POST["CAP"], $_POST["NumeroCivico"], $_POST["Citta"], $_POST["Provincia"], $_POST["Nome"] ,$_SESSION["email"],$_GET["id"]);
        //parte la notifica
        $i = $dbh->getNotificationNextId($_SESSION["email"])[0]["Id_Notifica"];
        if(empty($i)){
            $i = 0;
        }
        $dbh->createNotification($_SESSION["email"], $i + 1, "Modifica Indirizzo","Il tuo nuovo indirizzo è ora disponibile", "Hai modificato l'indirizzo ".$_POST["Nome"]);
        header("Location: login.php");
    }

} else {
    if ( !empty($_POST["Nome"]) && !empty($_POST["Via"]) && !empty($_POST["NumeroCivico"]) && !empty($_POST["Citta"]) && !empty($_POST["Provincia"]) && !empty($_POST["CAP"])) {
        $progressivo = $dbh->getProgressive($_SESSION["email"])[0];
        $dbh->insertNewAddress($_SESSION["email"], $progressivo["Progressivo"] + 1, $_POST["Via"], $_POST["CAP"], $_POST["NumeroCivico"], $_POST["Citta"], $_POST["Provincia"], $_POST["Nome"]);
        //parte la notifica
        $i = $dbh->getNotificationNextId($_SESSION["email"])[0]["Id_Notifica"];
        if(empty($i)){
            $i = 0;
        }
        $dbh->createNotification($_SESSION["email"], $i + 1, "Aggiunto Indirizzo","Il tuo nuovo indirizzo è ora disponibile", "Hai aggiunto l'indirizzo ".$_POST["Nome"]);
        header("Location: login.php");
    }
}
        
    require("template/base.php");
?>