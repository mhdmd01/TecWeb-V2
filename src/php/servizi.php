<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/servizi.html", "Servizi", "Servizi", "Pagina servizi del sito ufficiale di Saudade");
    
    $pagina->modificaHTML("{meseCorrente}", date('n'));
    $pagina->modificaHTML("{annoCorrente}", date('Y'));


    $pagina->printPage();