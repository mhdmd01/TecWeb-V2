<?php
    require_once("newPage.php");

    $pagina = new pagina("../html/index.html", "Home", "Home", "Pagina home del sito ufficiale di Saudade");
    $pagina->printPage();