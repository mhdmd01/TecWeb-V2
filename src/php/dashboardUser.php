<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/dashboardAdmin.html", "Area personale admin", "Area personale admin", "Area personale admin Saudade");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") { 
            $pagina->modificaHTML("{placeholder}",  "<div><a href='aggiungiSogno.php'>Aggiungi sogno</a></div>");
            $pagina->printPage();
        }
        $pagina = new newPage("../html/dashboardUser.html", "Area personale", "Area personale", "Area personale Saudade");
        $pagina->modificaHTML("{elencosogni}", "HELLO" );
        $pagina->printPage();
    }else{
        $pagina->modificaHTML("{placeholder}",  "<div>Pagina riservata, accedere come utente prima</div>");
        $pagina->printPage();
    }

    