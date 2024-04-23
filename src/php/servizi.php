<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/servizi.html", "Servizi", "Servizi", "Pagina servizi del sito ufficiale di Saudade");
    $pagina->modificaHTML("{meseOdierno}", date('n'));
    $pagina->printPage();