<?php 
require_once("newPage.php");
require_once("functions.php");

use functions\functions;

$pagina = new newPage("../html/sognoNonTrovato.html", "Sogno non disponibile", "Sogno non disponibile", "Pagina di errore per il sogno non disponibile");


$functions = new functions();
$functions->openDBConnection();

if(isset($_SESSION['user_name'])){
    if (isset($_GET['sogno'])) {
        $sogno = urldecode($_GET['sogno']);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        } // ???

        // Verifica se l'utente ha già effettuato l'acquisto
        $stmt = $functions->getConnection()->prepare("SELECT * FROM acquisti WHERE user_name=? AND articolo=?");
        $stmt->bind_param("ss", $_SESSION['user_name'], $sogno);
        $stmt->execute();
        $risultato = $stmt->get_result();

        if (mysqli_num_rows($risultato) == 0) {
            // Registra l'acquisto se non è stato ancora effettuato
            $stmt = $functions->getConnection()->prepare("INSERT INTO acquisti (user_name, articolo) VALUES (?, ?)");
            $stmt->bind_param("ss", $_SESSION['user_name'], $sogno);
            $stmt->execute();

            // Prepara i dati per la pagina di conferma
            $titolo = $sogno;
            $username = $_SESSION['user_name'];   

            // Carica il contenuto della pagina HTML di conferma acquisto
            $pagina = new newPage("../html/confermaAcquisto.html", "Conferma acquisto $titolo", $titolo, "Pagina di conferma acquisto per $titolo");
            $pagina->modificaHTML("{username}", $username);
            $pagina->modificaHTML("{titolo}", $titolo);
            
            // Chiudi la connessione al database
            $functions->closeConnection();
        } else {
            $pagina = new newPage("../html/acquistoGiaEffettuato.html", "Sogno già acquistato", "Sogno già acquistato", "Pagina di errore per il sogno non disponibile");
        }
    } else {
        $pagina = new newPage("../html/sognoNonTrovato.html", "Sogno non disponibile", "Sogno non disponibile", "Pagina di errore per il sogno non disponibile");
    }

}else{
    $pagina->printErrorPage("Pagina riservata all'<a href=\"login.php\">accedi prima<a>");
}



// Stampare la pagina HTML con i contenuti dinamici inseriti
$pagina->printPage();