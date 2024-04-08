<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/prenotazione.html", "Prenotazione", "Prenotazione chip", "Pagina prenotazione chip");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $functions->validaPrenotazione($pagina);

    $functions->closeConnection();
    $pagina->printPage();