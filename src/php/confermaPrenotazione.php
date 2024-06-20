<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/confermaPrenotazione.html", "Conferma prenotazione", "Conferma prenotazione", "Pagina di conferma prenotazione chip");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    //QUERY SE GIA' PRENOTATO
    if($_SESSION['user_name'] != "admin") {
        $stmt = $functions->getConnection()->prepare("SELECT * FROM prenotazioni WHERE user_name=?");
        $stmt->bind_param("s", $_SESSION['user_name']);
        $stmt->execute();
        $risultato = $stmt->get_result();

        if(mysqli_num_rows($risultato) == 0){ //Se utente NON ha già prenotazione
                if($functions->validaPrenotazione($pagina) == true){
                    $data = $_GET['data'];
                    $user = $_SESSION['user_name'];

                    // Query per inserimento prenotazione
                    $stmt = $functions->getConnection()->prepare("UPDATE prenotazioni SET user_name = ? WHERE data = ?");
                    $stmt->bind_param("ss", $user, $data);
                    
                    if (!$stmt->execute()) //Errore nella query
                        $pagina->printErrorPage("Errore durante la procedura di prenotazione, riprovare più tardi");
                }
            }else{
                $pagina->printErrorPage("Hai già fissato un appuntamento, <a href=\"contatta.php\">contattaci</a> se pensi ci sia un errore");
            }
    }else
        $pagina->printErrorPage("Non sei loggato, esegui il <a href=\"login.php\">login</a> per accedere");

    

    $functions->closeConnection();
    $pagina->printPage();
