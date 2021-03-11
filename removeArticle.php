<?php
    require_once("./bootstrap.php");

    if(isset($_GET["id"])){
       $idarticolo = $_GET["id"];
       $dbh->deleteFromAppartieneAdmin($idarticolo);
       $dbh->deleteFromCarrelloAdmin($idarticolo);
       $dbh->deleteFromDettaglioOrdineAdmin($idarticolo);
       $dbh->deleteFromImmaginiAdmin($idarticolo);
       $dbh->deleteFromArticleAdmin($idarticolo);
    }
    
    header("location: admin.php);

    
?>