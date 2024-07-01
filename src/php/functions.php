<?php

namespace functions;
require_once("credenziali.php");

class Functions {
    private $connection;

    public function openDBConnection(){
        global $HOST_DB, $USERNAME, $PASSWORD, $DATABASE_NAME;

        $this->connection = mysqli_connect(
            $HOST_DB,
            $USERNAME,
            $PASSWORD,
            $DATABASE_NAME
        );
        $this->connection->set_charset("utf8");
        return mysqli_connect_errno() == 0;
    }

    public function closeConnection(){
        mysqli_close($this->connection);
    }

    public function getConnection(){
        return $this->connection;
    }

    public function executeQuery($query){
        if ($this->openDBConnection()) {
            $risultato = mysqli_query($this->connection, $query) or die("Errore di connessione" . mysqli_error($this->connection));
    
            // Verifica se la query non ha restituito alcuna riga
            if (mysqli_num_rows($risultato) == 0) {
                $risultato = null;
            }
        } else {
            $risultato = null;
        }

        $this->closeConnection();
        return $risultato;
    }

    function pulisciInput($value) {
        $value = trim($value); 
        $value = strip_tags($value); 
        $value = htmlentities($value);
        return $value;
    }

    function validaPrenotazione($pagina){
        if (isset($_GET['data']) && !is_null($_GET['data']) && $_GET['data'] != "") {
            $data = $_GET['data'];
            // Query per verificare se la data esiste
            $stmt = $this->getConnection()->prepare("SELECT * FROM prenotazioni WHERE data=?");
            $stmt->bind_param("s", $data);
            $stmt->execute();
            $risultato = $stmt->get_result();
            $riga = $risultato->fetch_assoc(); // Ottenere la prima riga del risultato
    
            $dataFormattata = date('d/m/Y', strtotime($data)); // Formattazione data
    
    
            if ($risultato->num_rows > 0 && $riga['user_name'] == NULL) { //Se la data esiste ed è disponibile
    
                // Avvia una sessione, se non è già attiva
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }            
    
                //Unico caso in cui va tutto bene
                if(isset($_SESSION['user_name']) && !empty($_SESSION['user_name']) && $_SESSION['user_name'] != "admin"){ // Lasciare possibilità ad admin di prenotare ? (magari per tenersi libero qualche giorno)
                    $pagina->modificaHTML("{dataPrenotazione}", $data);
                    $pagina->modificaHTML("{dataFormattata}", $dataFormattata);
                    return true;
                
                }else{
                    $pagina->printErrorPage("<div class=\"sectionDash\"> <p> Per utilizzare questo servizio devi avere un <span lang=\"en\">account</span></p> 
                        <a href=\"login.php\">Accedi</a><a href=\"signup.php\">Registrati</a></div>");
                }
    
            } else { //Se non esiste la data
                $pagina->printErrorPage("<p>Data specificata non disponibile (".$dataFormattata."), <a href=\"calendario.php\">torna alla pagina di prenotazione</a> o alla <a href=\"index.php\">pagina <span lang=\"en\">home</span></a></p>");
            }
        } else {
            $pagina->printErrorPage("<p>Non risulta selezionata nessuna data , <a href=\"calendario.php\">ritornare alla pagina di prenotazione</a> e riprovare</p>");
        }
        return false;
    }
}
