<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/sogni.html", "Sogni", "Sogni", "Elenco sogni Saudade");

    use functions\functions;
    $functions = new functions();
    $sogni = $functions->executeQuery("SELECT * FROM sogni;");

    if($sogni == null)
        $pagina->printErrorPage("Non ci sono sogni disponibili al momento, riprovare più tardi o tornare alla pagina <a href=\"index.php\">home</a>");
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

        $pagina->modificaHTML("{elencoSogni}", $annuncio);
    }

    
    $pagina->printPage();