<?php
    require_once("./bootstrap.php");
    $templateParams["nome"]="template/aggiungiTemplate.php";
    $templateParams["titolo"]="Kaiyinda Handmade - Aggiungi";

    //megamenu
    $templateParams["categoriaMegaMenu"] = $dbh->getMegamenuCategories();
    $array = array();
    foreach($templateParams["categoriaMegaMenu"] as $categoria){
        $array[$categoria["Nome_Categoria"]] = $dbh->getSomeArticleByCategory($categoria["Nome_Categoria"],4);
    }

    if(!empty($_POST["prezzo"]) && !empty($_POST["peso"]) && !empty($_POST["scorta"]) && !empty($_POST["descrizione"]) && !empty($_POST["materiale"]) && !empty($_POST["nome"])){

        $dbh->insertNewItemAdmin($_POST["nome"], $_POST["prezzo"], $_POST["peso"], $_POST["scorta"], $_POST["sconto"], $_POST["descrizione"], $_POST["materiale"]);
        $dbh->insertNewItemInCategoryAdmin($_POST["nome"], $_POST["categoria"]);

        //caricamento immagine
        if(isset($_FILES["imgarticolo"]) && strlen($_FILES["imgarticolo"]["name"])>0){
            list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["imgarticolo"]);


            if($result != 0){
                $imgarticolo = $msg;
                $dbh->insertImgAdmin($imgarticolo, $_POST["descrizione"], $_POST["nome"]);
            } else{
                    $msg = "Errore in inserimento!";
                }
                
            }
            $templateParams["msg"] = $msg;

            //parte la notifica
            $prova = $dbh->getNotificationNextId($_SESSION["email"]);
            if(!empty($prova)){
                $i = $prova[0]["Id_Notifica"];
            } else {
                $i = 0;
            }
            $dbh->createNotification($_SESSION["email"], $i + 1, "Aggiunto Articolo","Articolo aggiunto", "Hai aggiunto l'articolo ".$_POST["nome"]);
    }

    require("template/base.php");

?>