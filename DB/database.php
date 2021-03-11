<?php


class DatabaseHelper{

    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);

        if($this->db->connect_error){
            die("Connesione al db fallita");
        }
    }

    public function getSomeArticleByCategory($category, $n=4){
        $stmt = $this->db->prepare(
            "SELECT articolo.Nome_Articolo, immagini.Codice_Immagine FROM articolo, immagini, appartiene WHERE articolo.Nome_Articolo = immagini.Codice_Articolo AND appartiene.Nome_Articolo = articolo.Nome_Articolo AND immagini.Frontend = 1 AND appartiene.Nome_Categoria = ? ORDER BY articolo.Punteggio DESC LIMIT ?");
        $stmt->bind_param("si", $category, $n);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getImgForCategory($category){
        $stmt = $this->db->prepare("SELECT immagini.Codice_Immagine, immagini.Nome_Categoria FROM immagini WHERE immagini.Nome_Categoria = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticleById($id){
        $stmt = $this->db->prepare("SELECT articolo.Nome_Articolo, articolo.Prezzo, articolo.Peso, articolo.Sconto, articolo.Scorta, articolo.Descrizione, articolo.Materiale, immagini.Codice_Immagine FROM articolo, immagini WHERE articolo.Nome_Articolo = ? AND articolo.Nome_Articolo = immagini.Codice_Articolo AND immagini.Frontend = 1");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getImageArticleById($id){
        $stmt = $this->db->prepare("SELECT immagini.Codice_Immagine FROM immagini WHERE immagini.Codice_Articolo = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMegamenuCategories(){
        $stmt = $this->db->prepare("SELECT categoria.Nome_Categoria FROM categoria WHERE categoria.Nome_Categoria NOT IN ('Kaiyinda\'s choice', 'Novità', 'Più venduti')");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticleByCategory($category){
        $stmt = $this->db->prepare(
            "SELECT articolo.Nome_Articolo, articolo.Data_Inserimento, articolo.Prezzo, articolo.Peso, articolo.Scorta, articolo.Sconto, articolo.Descrizione, articolo.Punteggio, articolo.Materiale, immagini.Codice_Immagine FROM articolo, immagini, appartiene WHERE articolo.Nome_Articolo = immagini.Codice_Articolo AND appartiene.Nome_Articolo = articolo.Nome_Articolo AND appartiene.Nome_Categoria = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function checkUser($email, $password){
        $stmt = $this->db->prepare("SELECT usr.E_mail, usr.Admin FROM usr WHERE usr.E_mail = ? AND usr.Password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    public function getAddressUser($email){
        $stmt = $this->db->prepare("SELECT indirizzo.Nome_Indirizzo, indirizzo.Progressivo, indirizzo.Via, indirizzo.CAP, indirizzo.Civico, indirizzo.Citta, indirizzo.Provincia FROM indirizzo WHERE indirizzo.E_mail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    public function insertNewAddress($email, $progressivo, $via, $CAP, $civico, $citta, $provincia, $nome){
        $stmt = $this->db->prepare("INSERT INTO indirizzo (`E_mail`, `Progressivo`, `Via`, `CAP`, `Civico`, `Citta`, `Provincia`, `Nome_Indirizzo`) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sisiisss",$email, $progressivo, $via, $CAP, $civico, $citta, $provincia, $nome);
        $stmt->execute();
    }

    public function getProgressive($email){
        $stmt = $this->db->prepare("SELECT indirizzo.Progressivo FROM indirizzo WHERE indirizzo.E_mail = ? ORDER BY indirizzo.Progressivo DESC LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function changePassword($newpsw, $email){
        $stmt = $this->db->prepare("UPDATE usr SET usr.Password = ? WHERE usr.E_mail = ?");
        $stmt->bind_param("ss", $newpsw, $email);
        $stmt->execute();
    }

    public function getOrdersUser($email){
        $stmt = $this->db->prepare("SELECT ordine.Data_Ordine, ordine.Id_Ordine, ordine.Data_Consegna, dettaglio_ordine.Codice_Articolo, dettaglio_ordine.Taglia, dettaglio_ordine.Quantità, indirizzo.Nome_Indirizzo FROM ordine, indirizzo, dettaglio_ordine WHERE ordine.E_mail = indirizzo.E_mail AND ordine.Progressivo = indirizzo.Progressivo AND ordine.Data_Ordine = dettaglio_ordine.Data_Ordine AND ordine.Id_Ordine = dettaglio_ordine.Id_Ordine AND ordine.E_mail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    public function removeAddress($email, $progressivo){
        $stmt = $this->db->prepare("DELETE FROM indirizzo WHERE indirizzo.E_mail = ? AND indirizzo.Progressivo = ?");
        $stmt->bind_param("si", $email, $progressivo);
        $stmt->execute();
    }

    public function modifyAddress($via, $CAP, $civico, $citta, $provincia, $nome, $email, $progressivo){
        $stmt = $this->db->prepare("UPDATE indirizzo SET indirizzo.Via = ?, indirizzo.CAP = ?, indirizzo.Civico = ?, indirizzo.Citta = ?, indirizzo.Provincia = ?, indirizzo.Nome_Indirizzo = ? WHERE indirizzo.E_mail = ? AND indirizzo.Progressivo = ?");
        $stmt->bind_param("siissssi",$via, $CAP, $civico, $citta, $provincia, $nome, $email, $progressivo);
        $stmt->execute();
    }

    public function findAddress($email, $progressivo){
        $stmt = $this->db->prepare("SELECT indirizzo.Nome_Indirizzo, indirizzo.Via, indirizzo.CAP, indirizzo.Civico, indirizzo.Citta, indirizzo.Provincia FROM indirizzo WHERE indirizzo.E_mail = ? AND indirizzo.Progressivo = ?");
        $stmt->bind_param("si",$email, $progressivo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNotification($email){
        $stmt = $this->db->prepare("SELECT notifica.Id_Notifica, notifica.Tipo, notifica.Anteprima_Descrizione, notifica.Descrizione FROM notifica WHERE notifica.E_mail = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumberOfNotificationUnread($email){
        $stmt = $this->db->prepare("SELECT COUNT(notifica.Id_Notifica) FROM notifica WHERE notifica.E_mail = ? AND notifica.Letto = 0");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);     
    }

    public function readAllNotifications($email){
        $stmt = $this->db->prepare("UPDATE notifica SET notifica.Letto = 1 WHERE notifica.E_mail = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();   
    }
    
    public function createNotification($email, $id, $tipo, $anteprima, $descrizione){
        $stmt = $this->db->prepare("INSERT INTO notifica (`E_mail`, `Id_Notifica`, `Tipo`, `Anteprima_Descrizione`, `Descrizione`, `Letto`) VALUES (?,?,?,?,?,0)");
        $stmt->bind_param("sisss", $email, $id, $tipo, $anteprima, $descrizione);
        $stmt->execute();
    }

    public function getNotificationNextId($email){
        $stmt = $this->db->prepare("SELECT notifica.Id_Notifica FROM notifica WHERE notifica.E_mail = ? ORDER BY notifica.Id_Notifica DESC LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function removeNotification($email, $progressivo){
        $stmt = $this->db->prepare("DELETE FROM notifica WHERE notifica.E_mail = ? AND notifica.Id_Notifica = ?");
        $stmt->bind_param("si", $email, $progressivo);
        $stmt->execute();
    }

    public function addArticleToCart($email, $codiceArticolo, $taglia, $quantità){
        $stmt = $this->db->prepare("INSERT INTO carrello (`E_mail`, `Codice_Articolo`, `Taglia`, `Quantità`) VALUES (?,?,?,?)");
        $stmt->bind_param("sssi", $email, $codiceArticolo, $taglia, $quantità);
        $stmt->execute();
    }

    public function updateStocks($quantity, $codiceArticolo){
        $stmt = $this->db->prepare("UPDATE articolo SET articolo.Scorta = (articolo.Scorta - ?) WHERE articolo.Nome_Articolo = ?");
        $stmt->bind_param("is", $quantity, $codiceArticolo);
        $stmt->execute();
    }

    public function updateQuantityInCart($quantity, $email, $codiceArticolo, $taglia){
        $stmt = $this->db->prepare("UPDATE carrello SET carrello.Quantità = (carrello.Quantità + ?) WHERE carrello.E_mail = ? AND carrello.Codice_Articolo = ? AND carrello.Taglia = ?");
        $stmt->bind_param("isss", $quantity, $email, $codiceArticolo, $taglia);
        $stmt->execute();
    }

    public function inCart($email, $codiceArticolo, $taglia){
        $stmt = $this->db->prepare("SELECT carrello.Codice_Articolo, carrello.Quantità FROM carrello WHERE carrello.E_mail = ? AND carrello.Codice_Articolo = ? AND carrello.Taglia = ?");
        $stmt->bind_param("sss", $email, $codiceArticolo, $taglia);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    public function getNumberOfItemInCart($email){
        $stmt = $this->db->prepare("SELECT COUNT(carrello.Codice_Articolo) FROM carrello WHERE carrello.E_mail = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);     
    }

    public function getArticleInCart($email){
        $stmt = $this->db->prepare("SELECT * FROM carrello, articolo, immagini WHERE articolo.Nome_Articolo = carrello.Codice_Articolo AND carrello.E_mail = ? AND immagini.Codice_Articolo = articolo.Nome_Articolo AND immagini.Frontend = 1");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    public function oneLessInCart($email, $codiceArticolo, $taglia){
        $stmt = $this->db->prepare("UPDATE carrello SET carrello.Quantità = carrello.Quantità - 1 WHERE carrello.E_mail = ? AND carrello.Codice_Articolo = ? AND carrello.Taglia = ?");
        $stmt->bind_param("sss", $email, $codiceArticolo, $taglia);
        $stmt->execute();
    }

    public function removeFromCart($email, $codiceArticolo, $taglia){
        $stmt = $this->db->prepare("DELETE FROM carrello WHERE carrello.E_mail = ? AND carrello.Codice_Articolo = ? AND carrello.Taglia = ?");
        $stmt->bind_param("sss", $email, $codiceArticolo, $taglia);
        $stmt->execute();
    }

    public function createOrder($idordine, $email, $progressivo){
        $stmt = $this->db->prepare("INSERT INTO ordine(`Data_Ordine`, `Id_Ordine`, `Data_Consegna`, `E_mail`, `Progressivo`) VALUES (CURDATE(), ?, NULL, ?, ?)");
        $stmt->bind_param("isi", $idordine, $email, $progressivo);
        $stmt->execute();
    }

    public function getIdOrder($email){
        $stmt = $this->db->prepare("SELECT ordine.Id_Ordine FROM ordine WHERE ordine.Data_Ordine = CURDATE() AND ordine.E_mail = ? ORDER BY ordine.Id_Ordine DESC LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createOrderDetail($idordine, $codiceArticolo, $taglia, $quantità, $email){
        $stmt = $this->db->prepare("INSERT INTO dettaglio_ordine(`Data_Ordine`, `Id_Ordine`, `Codice_Articolo`, `Taglia`, `Quantità`, `E_mail`) VALUES (CURDATE(), ?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $idordine, $codiceArticolo, $taglia, $quantità, $email);
        $stmt->execute();
    }

    public function resetCart($email){
        $stmt = $this->db->prepare("DELETE FROM carrello WHERE carrello.E_mail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }

    public function addNewUsr($password, $email){
        $stmt = $this->db->prepare("INSERT INTO usr(`Password`, `E_mail`, `Admin`) VALUES (?,?,0) ");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
    }

    public function getAllCategories(){
        $stmt = $this->db->prepare("SELECT categoria.Nome_Categoria FROM categoria");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteFromAppartieneAdmin($idarticolo){
        $stmt = $this->db->prepare("DELETE FROM appartiene WHERE appartiene.Nome_Articolo = ?");
        $stmt->bind_param("s", $idarticolo);
        $stmt->execute();
    }

    public function deleteFromCarrelloAdmin($idarticolo){
        $stmt = $this->db->prepare("DELETE FROM carrello WHERE carrello.Codice_Articolo = ?");
        $stmt->bind_param("s", $idarticolo);
        $stmt->execute();
    }

    public function deleteFromDettaglioOrdineAdmin($idarticolo){
        $stmt = $this->db->prepare("DELETE FROM dettaglio_ordine WHERE dettaglio_ordine.Codice_Articolo = ?");
        $stmt->bind_param("s", $idarticolo);
        $stmt->execute();
    }

    public function deleteFromImmaginiAdmin($idarticolo){
        $stmt = $this->db->prepare("DELETE FROM immagini WHERE immagini.Codice_Articolo = ?");
        $stmt->bind_param("s", $idarticolo);
        $stmt->execute();
    }

    public function deleteFromArticleAdmin($idarticolo){
        $stmt = $this->db->prepare("DELETE FROM articolo WHERE articolo.Nome_Articolo = ?");
        $stmt->bind_param("s", $idarticolo);
        $stmt->execute();
    }

    public function updateArticleAdmin($prezzo, $peso, $scorta, $sconto, $descrizione, $materiale, $id){
        $stmt = $this->db->prepare("UPDATE articolo SET articolo.Prezzo = ?, articolo.Peso = ?, articolo.Scorta = ?, articolo.Sconto = ?, articolo.Descrizione = ?, articolo.Materiale = ?  WHERE articolo.Nome_Articolo = ?");
        $stmt->bind_param("iiiisss", $prezzo, $peso, $scorta, $sconto, $descrizione, $materiale, $id);
        $stmt->execute();
    }

    public function updateImgAdmin($imm, $id){
        $stmt = $this->db->prepare("UPDATE immagini SET immagini.Codice_Immagine = ? WHERE immagini.Codice_Articolo = ? AND immagini.Frontend = 1");
        $stmt->bind_param("ss", $imm, $id);
        $stmt->execute();
    }
    
    public function insertNewItemAdmin($nome, $prezzo,$peso,$scorta,$sconto,$descrizione,$materiale){
        $stmt = $this->db->prepare("INSERT INTO `articolo`(`Nome_Articolo`, `Data_Inserimento`, `Prezzo`, `Peso`, `Scorta`, `Sconto`, `Descrizione`, `Punteggio`, `Materiale`) VALUES (?, CURDATE(), ?, ?, ?, ?, ?, 0, ?)");
        $stmt->bind_param("siiiiss", $nome, $prezzo,$peso,$scorta,$sconto,$descrizione,$materiale);
        $stmt->execute();
    }

    public function insertNewItemInCategoryAdmin($categoria, $nome){
        $stmt = $this->db->prepare("INSERT INTO `appartiene`(`Nome_Categoria`, `Nome_Articolo`) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $categoria);
        $stmt->execute();
    }

    public function insertImgAdmin($imm, $des, $art){
        $stmt = $this->db->prepare("INSERT INTO `immagini`(`Codice_Immagine`, `Descrizione`, `Codice_Articolo`, `Frontend`) VALUES (?, ?, ?, 1)");
        $stmt->bind_param("sss", $imm, $des, $art);
        $stmt->execute();
    }

    public function getAllAdmin(){
        $stmt = $this->db->prepare("SELECT usr.E_mail FROM usr WHERE usr.Admin = 1");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updatePunteggio($qta, $articolo){
        $stmt = $this->db->prepare("UPDATE articolo SET articolo.Punteggio = (articolo.Punteggio + ?) WHERE articolo.Nome_Articolo = ?");
        $stmt->bind_param("is", $qta, $articolo);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

?>