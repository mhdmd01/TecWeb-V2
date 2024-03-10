<?php
    require_once("newPage.php");
    require_once("functions.php");


    $pagina = new newPage("../html/sogni.html", "Sogni", "Sogni", "Elenco sogni Saudade");

    use functions\functions;
    $functions = new functions();
    $sogni = $functions->executeQuery("SELECT * FROM sogni;");

    $annuncio = "";

    foreach( $sogni as $row){
        $annuncio .= file_get_contents("../html/sognoLista.html");

        $annuncio = str_replace("{linkSogno}", "link".urlencode($row['titolo']), $annuncio);
        $annuncio = str_replace("{titolo}", $row['titolo'], $annuncio);
        $annuncio = str_replace("{descrizione}", $row['descrizione'], $annuncio);
        $annuncio = str_replace("{prezzo}", $row['prezzo'], $annuncio);
        $annuncio = str_replace("{pathImg}", $row['pathImg'], $annuncio);
    }

    if($annuncio == "")
        $annuncio = "Non ci sono sogni disponibili al momento, riprovare più tardi";

    $pagina->modificaHTML("{elencoSogni}", $annuncio);
    $pagina->printPage();