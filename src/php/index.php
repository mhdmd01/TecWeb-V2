<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/index.html", "Home", "Home", "Pagina home del sito ufficiale di Saudade");

    use functions\functions;
    $functions = new functions();

    $sogni = $functions->executeQuery("SELECT * FROM sogni ORDER BY data_ins DESC LIMIT 3;");

    $recenz = $functions->executeQuery("SELECT * FROM recensioni ORDER BY data_ins;");

    if($recenz == null){
        $rec = "Ancora nessuna recensione";
        $pagina->modificaHTML("{recensioni}", $rec);
    }        
    else{
        $rec = "";

        foreach( $recenz as $row){
            $rec .= file_get_contents("../html/recensioneTemp.html");

            $rec = str_replace("{utente}", $row['user_name'], $rec);
            $rec = str_replace("{sogno}", $row['sogno'], $rec);
            $rec = str_replace("{testo}", $row['testo'], $rec);
        }

        $pagina->modificaHTML("{recensioni}", $rec);
    }

    if($sogni == null){
        $rec = "Ancora nessun sogno disponibile";
        $pagina->modificaHTML("{sogni}", $rec);
    }        
    else{
        $annuncio = "";

        foreach( $sogni as $row){
            $annuncio .= file_get_contents("../html/annuncioSogno.html");

            $annuncio = str_replace("{linkSogno}", "sognoSingolo.php?sogno=".urlencode($row['titolo']), $annuncio);
            $annuncio = str_replace("{titolo}", $row['titolo'], $annuncio);
            $annuncio = str_replace("{descrizione}", $row['descrizione'], $annuncio);
            $annuncio = str_replace("{prezzo}", $row['prezzo'], $annuncio);
            $annuncio = str_replace("{pathImg}", "\"../assets/sogni/".$row['titolo'].".".$row['estensioneFile']."\"", $annuncio);
        }

        $pagina->modificaHTML("{sogni}", $annuncio);
    }

    $pagina->printPage();