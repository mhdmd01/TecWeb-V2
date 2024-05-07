<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/formChip.html", "Ottieni chip", "Ottieni il tuo chip", "Pagina prenotazione chip");

    use functions\functions;
    $functions = new functions();

    $arrayMesi = array("", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre");

    //Popolo menù a tendina
    $dateTabella = $functions->executeQuery("SELECT DISTINCT YEAR(data) AS anno, MONTH(data) AS mese FROM prenotazioni;");

    if($dateTabella == null){
        $pagina->printErrorPage("Errore di connessione al server, riprovare più tardi");
    }else{
        $dateMenu = "";
        foreach($dateTabella as $row){
            //echo $row['anno'] ." - ". $row['mese'] . "<br>";
            $dateMenu .= "<option value=\"".$row['mese']."-".$row['anno']."\"> ".$arrayMesi[$row['mese']]." ".$row['anno']." </option>";
        }
        $pagina->modificaHTML("{dateMenu}", $dateMenu);
    }

    //Ottengo valori data
    if(isset($_GET['data'])) {
        $valore = $_GET['data'];
        $valori = explode('-', $valore); // Splittare il valore del mese e dell'anno
        $mese = $valori[0];
        $anno = $valori[1];

        if($mese>=1 && $mese <=12 && $anno>=date('Y')){ //Controllo sui valori della data
            $dataInizioQuery = $anno."-".$mese."-01"; 
            $dataFineQuery = $anno."-".$mese."-31";

            $functions->openDBConnection();
            $stmt = $functions->getConnection()->prepare("SELECT * FROM prenotazioni WHERE data BETWEEN ? AND ?;");
            $stmt->bind_param("ss", $dataInizioQuery, $dataFineQuery);
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
        }
    }else{
        $pagina->modificaHTML("{data}", "Data selezionata non valida, scegliere una data esistente");
    }

    $pagina->printPage();   