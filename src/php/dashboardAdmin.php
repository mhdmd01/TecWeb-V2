<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/dashboardAdmin.html", "Area personale admin", "Area personale admin", "Area personale admin Saudade");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] != "admin")
            $pagina->printErrorPage("<div class=\"sectionDash\">Pagina riservata. Torna alla <a href=\"index.php\"><span lang=\"en\">home</span></a> o alla tua <a href=\"dashboardUser.php\">area personale</a></div>");
    
    }else{
        $pagina->printErrorPage("<div class=\"sectionDash\"><p>Devi <a href=\"login.php\">accedere al tuo <span lang=\"en\">account</span></a> per visionare questa pagina</p></div>");
    }

    $pagina->printPage();