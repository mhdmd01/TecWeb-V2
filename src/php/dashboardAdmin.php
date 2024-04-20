<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/dashboardAdmin.html", "Area personale admin", "Area personale admin", "Area personale admin Saudade");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") { 
            $pagina->modificaHTML("{aggiungiSogno}",  "<div><a href='aggiungiSogno.php'>Aggiungi sogno</a></div>"); 
            $pagina->modificaHTML("{aggiungiDate}",  "<div><a href='aggiungiDate.php'>Aggiungi una settimana</a></div>");
        }
    }else{
        $pagina->printErrorPage("Pagina riservata all'admin");
    }    

    $pagina->printPage();

    
    