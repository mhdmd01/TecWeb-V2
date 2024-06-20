<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/dashboardAdmin.html", "Area personale admin", "Area personale admin", "Area personale admin Saudade");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] != "admin")
            $pagina->printErrorPage("Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a>");
    
    }else{
        $pagina->printErrorPage("Devi <a href=\"login.php\">accedere al tuo account</a> per visionare questa pagina");
    }

    $pagina->printPage();