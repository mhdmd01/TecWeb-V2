<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/error404.html", "Pagina non trovata", "Pagina non trovata", "Pagina non trovata");
    $pagina->printPage();