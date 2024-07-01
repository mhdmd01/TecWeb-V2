<?php
    require_once"newPage.php";
    require_once "functions.php"; 

    $pagina = new newPage("../html/aggiungiRecensione.html", "Nuova recensione", "Nuova recensione", "Aggiunta nuova recensione");
    use functions\functions;
    $functions = new functions();
    $errorMsg = "";
    $successMsg = "";

    //Controlli
    if(isset($_GET['sogno'])){
        $sogno = urldecode($_GET['sogno']);

        $functions->openDBConnection();
        // Esegue la query utilizzando uno statement preparato
        $stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=?");
        $stmt->bind_param("s", $sogno);
        $stmt->execute();
        $risultato = $stmt->get_result();
        $functions->closeConnection();


        if(mysqli_num_rows($risultato) == 0){
            $pagina->printErrorPage("<p>Sogno non trovato, torna alla <a href=\"sogni.php\">lista sogni</a> e riprova</p>");
            $pagina->printPage();
            die;
        }

    }else{
        $pagina->printErrorPage("<p>Sogno non trovato, torna alla <a href=\"sogni.php\">lista sogni</a> e riprova</p>");
        $pagina->printPage();
        die;
    }

    
    if(isset($_SESSION['user_name'])){
        if(isset($_POST['recensione']) && strlen($_POST['recensione']) > 0){ //Parte eseguita solo se viene inviato il form

            $recensione = $functions->pulisciInput($_POST['recensione']);
            $user_name = $_SESSION['user_name'];
            $stelle = isset($_POST['valutazione']) ? ($_POST['valutazione']) : '';
            
            $functions->openDBConnection();
            $stmt = $functions->getConnection()->prepare("INSERT INTO recensioni (user_name, testo, articolo, stelle) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $user_name, $recensione, $sogno, $stelle); 
            $ris = $stmt->execute();
            $stmt->close();
            $functions->closeConnection();
            
            if($ris)
                $successMsg = "Recensione caricata con successo";
            else
                $errorMsg = "Errore nel caricamento della recensione";
        }     
        $pagina->modificaHTML("{titoloSogno}", $sogno);
        $pagina->modificaHTML("{Errore}", $errorMsg);
    }else{

        $pagina->printErrorPage("<div class=\"sectionDash\">Pagina riservata ad utenti loggati, esegui il <a href=\"login.php\"><span lang=\"en\">login</span></a> per accedere</div>");
    }    
    
    $pagina->modificaHTML("{messaggioSuccesso}", $successMsg);                    
    $pagina->modificaHTML("{Errore}", $errorMsg);                    
    $pagina->printPage();    