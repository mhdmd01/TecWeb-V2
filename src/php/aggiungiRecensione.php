<?php
    require_once"newPage.php";
    require_once "functions.php"; 

    $pagina = new newPage("../html/aggiungiRecensione.html", "Nuova recensione", "Nuova recensione", "Aggiunta nuova recensione");
    use functions\functions;
    $functions = new functions();
    $errorMsg = "";

    $sogno = urldecode($_GET['sogno']);

    
    if(isset($_SESSION['user_name'])){
        if(isset($_POST['recensione']) && strlen($_POST['recensione']) > 0){ //Parte eseguita solo se viene inviato il form

            $recensione = $functions->pulisciInput($_POST['recensione']);
            $user_name = $_SESSION['user_name'];
            
            $functions->openDBConnection();
            $stmt = $functions->getConnection()->prepare("INSERT INTO recensioni (user_name, testo, sogno) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_name, $recensione, $sogno); 
            $ris = $stmt->execute();
            $stmt->close();
            $functions->closeConnection();
            
            if($ris)
                $errorMsg = "Recensione caricata con successo";
            else
                $errorMsg = "Errore nel caricamento della recensione";
        }     
        $pagina->modificaHTML("{titoloSogno}", $sogno);
        $pagina->modificaHTML("{Errore}", $errorMsg);
    }else{

        $pagina->printErrorPage("Pagina riservata ad utenti loggati, esegui il <a href=\"login.php\">login</a> per accedere");
    }    

    $pagina->modificaHTML("{Errore}", $errorMsg);                    
    $pagina->printPage();    