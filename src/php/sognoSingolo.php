<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();

    if (isset($_GET['sogno'])) {
        $sogno = urldecode($_GET['sogno']);
    }else{
        echo "Errore passaggio parametri, riprovare"; //Da migliorare
    }

    $risultato = $functions->executeQuery("SELECT * FROM sogni WHERE titolo=\"$sogno\";");

    
    $pagina = new newPage("../html/sognoSingolo.html", 
                            $sogno, 
                            strval($sogno)." saudade", 
                            "Pagina riguardante ".strval($sogno));

    if($risultato == null)
        $pagina->printErrorPage("Sogno non trovato, tornare alla pagina <a href=\"sogni.php\">Sogni</a> e provare a riselezionare il sogno desiderato");                            
    else{
        if(mysqli_num_rows($risultato) > 0){
            foreach( $risultato as $row){
                $pagina->modificaHTML("{titolo}", $row['titolo']);
                $pagina->modificaHTML("{descrizione}", $row['descrizione']);
                $pagina->modificaHTML("{prezzo}", $row['prezzo']);
                $pagina->modificaHTML("{pathImg}", $row['pathImg']);
            }
        }
    }
    

    $pagina->printPage();