<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/prenotazione.html", "Prenotazione", "Prenotazione chip", "Pagina prenotazione chip");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();


    if (isset($_GET['data']) && !is_null($_GET['data']) && $_GET['data'] != "") {
        $data = $_GET['data'];
        // Query per verificare se la data esiste
        $stmt = $functions->getConnection()->prepare("SELECT * FROM prenotazioni WHERE data=?");
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $risultato = $stmt->get_result();
        $riga = $risultato->fetch_assoc(); // Ottenere la prima riga del risultato

        $data = date('d/m/Y', strtotime($data)); // Formattazione data


        if ($risultato->num_rows > 0 && $riga['user_name'] == NULL) { //Se la data esiste ed è disponibile

            // Avvia una sessione, se non è già attiva
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }            

            
            if(isset($_SESSION['user_name']) && !empty($_SESSION['user_name']) /*&& $_SESSION['user_name'] != "admin"*/){ // Lasciare possibilità ad admin di prenotare ? (magari per tenersi libero qualche giorno)
                $pagina->modificaHTML("{dataPrenotazione}", $data);
            
            }else{
                $pagina->printErrorPage("<a href=\"login.php\">Accedere</a> al proprio account o <a href=\"signup.php\">registrarsi</a> prima di proseguire con la prenotazione");
            }

        } else { //Se non esiste la data
            $pagina->printErrorPage("Data specificata non disponibile (".$data."), <a href=\"formChip.php\">torna alla pagina di prenotazione</a> o alla <a href=\"index.php\">pagina home</a>");
        }
    } else {
        $pagina->printErrorPage("Non risulta selezionata nessuna data , <a href=\"formChip.php\">ritornare alla pagina di prenotazione</a> e riprovare");
    }

    $functions->closeConnection();
    $pagina->printPage();