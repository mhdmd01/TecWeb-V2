<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/formChip.html", "Ottieni chip", "Ottieni il tuo chip", "Pagina prenotazione chip");

    use functions\functions;
    $functions = new functions();

    //Colori delle celle
    $coloreDisponibile = "green";
    $coloreNonDisponibile = "red";

    $mesi = array("Giugno", "Luglio", "Agosto", "Settembre");



        foreach($mesi as $mese){
            if($mese == "Giugno") $meseNum = "6"; 
            if($mese == "Luglio") $meseNum = "7"; 
            if($mese == "Agosto") $meseNum = "8"; 
            if($mese == "Settembre") $meseNum = "9"; 

            $prenotazioni = $functions->executeQuery("SELECT * FROM prenotazioni WHERE data BETWEEN '2024-".$meseNum."-01' AND '2024-".$meseNum."-31' ORDER BY data ASC");

            if(!is_null($prenotazioni)){
                $tabella = "";
                foreach($prenotazioni as $row){
                    if($row['user_name'] == NULL) //DA AGGIUNGERE LINK CON PARAMETRO  (GIORGNO) AL FORM DI PRENOTAZIONE
                        $tabella .= "<td style=\"background-color: ".$coloreDisponibile.";\"><a href=\"prenotazione.php?data=".$row['data']."\">".date('d', strtotime($row['data']))."</a></td>";
                    else
                        $tabella .= "<td style=\"background-color: ".$coloreNonDisponibile.";\"><a href=\"prenotazione.php?data=".$row['data']."\">".date('d', strtotime($row['data']))."</a></td>";
                }
                $pagina->modificaHTML("{tabella".$mese."}", $tabella);
            }else{
                $pagina->modificaHTML("{tabella".$mese."}", "Date disponibili a breve");
            }
        }

    $pagina->printPage();   