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
            $dataInput = new DateTime("$anno-$mese-01"); //Uso DateTime, la classe per date ed orari di php

            // Verifica se la data è passata
            if ($dataInput < new DateTime()) {
                $dataInizioQuery = (new DateTime())->format("Y-m-d");
            } else {
                $dataInizioQuery = $dataInput->format("Y-m-d");
            }

            $dataFineQuery = $anno."-".$mese."-"."31";

            $pagina->modificaHTML("{mesePrenotazione}", $arrayMesi[$mese]);

            $functions->openDBConnection();
            $stmt = $functions->getConnection()->prepare("SELECT COUNT(DISTINCT data) AS num_date FROM prenotazioni WHERE user_name IS NULL AND data BETWEEN ? AND ?;");
            $stmt->bind_param("ss", $dataInizioQuery, $dataFineQuery);
            $stmt->execute();
            $risultato = $stmt->get_result();

            if ($risultato->num_rows > 0) {
                $riga = $risultato->fetch_assoc();
                $numeroAppuntamenti = $riga['num_date']; // Ottieni il numero di date disponibili
                $pagina->modificaHTML("{numeroAppuntamenti}", $numeroAppuntamenti);
            } else {
                $pagina->modificaHTML("{numeroAppuntamenti}", "errore");
            }

            $stmt = $functions->getConnection()->prepare("SELECT DISTINCT(data) FROM prenotazioni WHERE user_name IS NULL AND data BETWEEN ? AND ?;");
            $stmt->bind_param("ss", $dataInizioQuery, $dataFineQuery);
            $stmt->execute();
            $risultato = $stmt->get_result();

            if($risultato == null)
                $pagina->printErrorPage("Data non trovata");                            
            else{
                if(mysqli_num_rows($risultato) > 0){
                    $placeHolderData = "";

                    foreach( $risultato as $row){
                        $giorno = substr($row['data'], 8, 2);
                        // commentare riga seguente per ripristinare gli zeri nelle date 1..9
                        $giorno = ltrim($giorno, '0');
                        $placeHolderData .= "<li><a href=\"confermaPrenotazione.php?data=".$row['data']."\">".$giorno ."</a></li>";
                    }
                    $pagina->modificaHTML("{data}", $placeHolderData);
                }else{
                    $pagina->modificaHTML("{data}", "Ancora nessuna data disponibile nel periodo selezionato");
                }
            }
        }else
            $pagina->modificaHTML("{data}", "Data selezionata non valida, scegliere una data esistente");
    }else{
        $pagina->modificaHTML("{data}", "Data selezionata non valida, scegliere una data esistente");
    }

    //Nel caso qualcosa vada male comunque i placeholder vengono gestiti qui
    $pagina->modificaHTML("{mesePrenotazione}", "");
    $pagina->modificaHTML("{numeroAppuntamenti}", "0");
    $pagina->modificaHTML("{data}", "");

    $pagina->printPage();   
