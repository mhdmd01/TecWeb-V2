<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $pagina = new newPage("../html/dashboardUser.html", "Area personale user", "Area personale user", "Area personale user Saudade");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] != "admin") { 
            //Verifico se esiste utente (improbabile)
            $stmt = $functions->getConnection()->prepare("SELECT * FROM utenti WHERE user_name=?");
            $stmt->bind_param("s", $_SESSION['user_name']);
            $stmt->execute();
            $risultato = $stmt->get_result();
    
            if(mysqli_num_rows($risultato) > 0){
                $stmt = $functions->getConnection()->prepare("SELECT * FROM acquisti WHERE user_name=?");
                $stmt->bind_param("s", $_SESSION['user_name']);
                $stmt->execute();
                $ris = $stmt->get_result();
    
                if(mysqli_num_rows($ris) > 0){
                    $acquisti = "";
                    while ($row = $ris->fetch_assoc()) {
                        $acquisti .= "<br>";    //Da eliminare
                        $acquisti .= $row['articolo']. " ";
                        $acquisti .= $row['data'];
                    }
                    $pagina->modificaHTML("{elencosogni}", $acquisti);
                }else{
                    $pagina->modificaHTML("{elencosogni}", "Nessun acquisto ancora effettuato");
                }
            }else{
                $pagina->printErrorPage("Utente non trovato"); //Quasi impossibile accada
            }
        }
    }else{
        $pagina->printErrorPage("Pagina riservata ad utenti loggati, esegui il <a href=\"login.php\">login</a> per accedere");
    }    

    $functions->closeConnection();
    $pagina->printPage();