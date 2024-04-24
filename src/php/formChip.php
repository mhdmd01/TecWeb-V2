<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/formChip.html", "Ottieni chip", "Ottieni il tuo chip", "Pagina prenotazione chip");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    if(isset($_GET['mese']) && $_GET['mese'] >= 1 && $_GET['mese'] <= 12){
        $stmt = $functions->getConnection()->prepare("SELECT * FROM prenotazioni WHERE data BETWEEN ? AND ?;");
        $settimana1 = "2024-".$_GET['mese']."-01";
        $settimana2 = "2024-".$_GET['mese']."-31";
        $stmt->bind_param("ss", $settimana1, $settimana2);
        $stmt->execute();
        $risultato = $stmt->get_result();
        
        if($risultato == null)
            $pagina->printErrorPage("Data non trovata");                            
        else{
            if(mysqli_num_rows($risultato) > 0){
                $placeHolderData = "";

                foreach( $risultato as $row){
                    $placeHolderData .= "<li><a href=\"confermaPrenotazione.php?data=".$row['data']."\">".$row['data']."</a></li>";
                }
                $pagina->modificaHTML("{data}", $placeHolderData);
            }else{
                $pagina->modificaHTML("{data}", "Ancora nessuna data disponibile nel periodo selezionato");
            }
        }
    }else{
        $pagina->printErrorPage("Data selezionata non valida, <a href=\"formChip.php\">riprovare</a>");
    }

    $pagina->printPage();   