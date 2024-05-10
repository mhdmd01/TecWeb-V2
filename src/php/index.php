<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/index.html", "Home", "Home", "Pagina home del sito ufficiale di Saudade");

    use functions\functions;
    $functions = new functions();

    $sogni = $functions->executeQuery("SELECT * FROM sogni ORDER BY data_ins DESC LIMIT 3;");

    $recenz = $functions->executeQuery("SELECT * FROM recensioni ORDER BY data_ins LIMIT 5;");

    $sognitop = $functions->executeQuery("SELECT *, AVG(r.stelle) AS media_valutazione FROM recensioni r 
    INNER JOIN sogni s ON r.sogno = s.titolo GROUP BY s.titolo ORDER BY media_valutazione DESC LIMIT 3;");

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
            $annuncio = str_replace("{pathImg}", "\"../assets/sogni/".$row['nomeFile']."\"", $annuncio);
        }

        $pagina->modificaHTML("{sogni}", $annuncio);
    }

    if($sognitop == null){
        $rec = "Ancora nessun sogno disponibile";
        $pagina->modificaHTML("{topsogni}", $rec);
    }        
    else{
        $annuncio = "";

        foreach( $sognitop as $row){
            $annuncio .= file_get_contents("../html/annuncioSogno.html");

            $annuncio = str_replace("{linkSogno}", "sognoSingolo.php?sogno=".urlencode($row['titolo']), $annuncio);
            $annuncio = str_replace("{titolo}", $row['titolo'], $annuncio);
            $annuncio = str_replace("{descrizione}", $row['descrizione'], $annuncio);
            $annuncio = str_replace("{prezzo}", $row['prezzo'], $annuncio);
            $annuncio = str_replace("{pathImg}", "\"../assets/sogni/".$row['nomeFile']."\"", $annuncio);
        }

        $pagina->modificaHTML("{topsogni}", $annuncio);
    }

    $pagina->printPage();