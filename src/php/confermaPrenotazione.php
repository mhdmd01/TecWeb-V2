<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/confermaPrenotazione.html", "Conferma prenotazione", "Conferma prenotazione", "Pagina di conferma prenotazione chip");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    if($functions->validaPrenotazione($pagina) == true){
        $data = $_GET['data'];
        $user = $_SESSION['user_name'];

        // Query per inserimento prenotazione
        $stmt = $functions->getConnection()->prepare("UPDATE prenotazioni SET user_name = ? WHERE data = ?");
        $stmt->bind_param("ss", $user, $data);
        
        if (!$stmt->execute()) //Errore nella query
            $pagina->printErrorPage("Errore durante la procedura di prenotazione, riprovare più tardi");
    }

    $functions->closeConnection();
    $pagina->printPage();