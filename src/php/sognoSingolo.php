<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    if (isset($_GET['sogno'])) {
        $sogno = urldecode($_GET['sogno']);
        // Esegue la query utilizzando uno statement preparato
        $stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=?");
        $stmt->bind_param("s", $sogno);
        $stmt->execute();
        $risultato = $stmt->get_result();
    } else {
        echo "Errore passaggio parametri, riprovare"; // Da migliorare
    }
    

    
    $pagina = new newPage("../html/sognoSingolo.html", 
                            $sogno, 
                            strval($sogno)." saudade", 
                            "Pagina riguardante ".strval($sogno));

    if($risultato == null)
        $pagina->printErrorPage("Sogno non trovato, tornare alla pagina <a href=\"sogni.php\">Sogni</a> e provare a riselezionare il sogno desiderato");                            
    else{
        if(mysqli_num_rows($risultato) > 0){
            foreach( $risultato as $row){
                $pagina->modificaHTML("{errorMsg}", "");
                $pagina->modificaHTML("{titolo}", $row['titolo']);
                $pagina->modificaHTML("{descrizione}", $row['descrizione']);
                $pagina->modificaHTML("{prezzo}", $row['prezzo']);
                $pagina->modificaHTML("{pathImg}",  "\"../assets/sogni/".$row['titolo'].".".$row['estensioneFile']."\"");

                if(isset($_SESSION['user_name'])){ //Se sono loggato
                    if($_SESSION['user_name'] === "admin") //Se sono admin
                        $bottone = "<a href=\"modificaSogno.php?sogno={$row['titolo']}\" role=\"button\">Modifica</a>";
                    else{ //Se sono un utente
                        $stmt = $functions->getConnection()->prepare("SELECT * FROM acquisti WHERE user_name=? AND articolo=?");
                        $stmt->bind_param("ss", $_SESSION['user_name'], $sogno);
                        $stmt->execute();
                        $risultato = $stmt->get_result();

                        if($risultato->num_rows == 0)
                            $bottone = "<a href=\"acquistaSogno.php?sogno={$row['titolo']}\" role=\"button\">Compra</a>";
                        else{
                            $bottone = "<a role=\"button\">Aricolo già acquistato.</a>";
                            $bottone .= "<a href=\"aggiungiRecensione.php?sogno={$row['titolo']}\" role=\"button\"> Lascia una recensione</a>";
                        }
                            
                    }
                }else{   //Se non sono loggato
                    $bottone = "<a href=\"login.php\" role=\"button\">Esegui il login per acquistare</a>";
                }
                
                $pagina->modificaHTML("{bottoneCompra}",  $bottone);
               
            }
        }else{
            $pagina= new newPage("../html/sognoNonTrovato.html",
                                    "Sogno non disponibile",
                                    "", "Pagina di errore per il sogno non disponibile");
        }
    }
    
    $functions->closeConnection();
    $pagina->printPage();