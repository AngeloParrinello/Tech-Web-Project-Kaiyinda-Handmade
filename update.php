<?php
    require_once("./bootstrap.php");
    $templateParams["nome"]="template/updateArticleTemplate.php";

    //megamenu
    $templateParams["categoriaMegaMenu"] = $dbh->getMegamenuCategories();
    $array = array();
    foreach($templateParams["categoriaMegaMenu"] as $categoria){
        $array[$categoria["Nome_Categoria"]] = $dbh->getSomeArticleByCategory($categoria["Nome_Categoria"],4);
    }


        $templateParams["infobase"] = $dbh->getArticleById($_GET["id"])[0];

        if ( !empty($_POST["prezzo"]) && !empty($_POST["peso"]) && !empty($_POST["scorta"]) && !empty($_POST["descrizione"]) && !empty($_POST["materiale"])) {
            //caricamento articolo
            $dbh->updateArticleAdmin($_POST["prezzo"], $_POST["peso"], $_POST["scorta"], $_POST["sconto"], $_POST["descrizione"], $_POST["materiale"], $_GET["id"]);

            //caricamento immagine
            if(isset($_FILES["imgarticolo"]) && strlen($_FILES["imgarticolo"]["name"])>0){
                list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["imgarticolo"]);


                if($result != 0){
                    $imgarticolo = $msg;
                    $dbh->updateImgAdmin($imgarticolo, $_GET["id"]);
                } else{
                        $msg = "Errore in inserimento!";
                    }
                    
                }
                $templateParams["msg"] = $msg;
    
            //parte la notifica
            $i = $dbh->getNotificationNextId($_SESSION["email"])[0]["Id_Notifica"];
            if(empty($i)){
                $i = 0;
            }
            $dbh->createNotification($_SESSION["email"], $i + 1, "Modifica Articolo","Articolo aggiornato", "Hai modificato l'articolo ".$_GET["id"]);
            header("Location: admin.php");
        }

    require("template/base.php");

?>