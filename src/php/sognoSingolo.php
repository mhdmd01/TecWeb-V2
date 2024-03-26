<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();

    if (isset($_GET['sogno'])) {
        $sogno = urldecode($_GET['sogno']);
        // Esegue la query utilizzando uno statement preparato
        $functions->openDBConnection();
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
                
                $bottone = "<a href=\"acquisto.php?titolo={$row['titolo']}\" role=\"button\">Compra</a>";
                $pagina->modificaHTML("{bottoneCompra}",  $bottone);
            }
        }else{
            $pagina= new newPage("../html/sognoNonTrovato.html",
                                    "Sogno non disponibile",
                                    "", "Pagina di errore per il sogno non disponibile");
            
            //$pagina->modificaHTML("{errorMsg}", "Il sogno che stai cercando non esiste o è stato rimosso, torna all'<a href=\"sogni.php\">elenco</a>");
        }
    }
    

    $pagina->printPage();