<?php
    require_once("newPage.php");

    $pagina = new newPage("../html/dashboardAdmin.html", "Area personale admin", "Area personale admin", "Area personale admin Saudade");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") { 
            $pagina->modificaHTML("{aggiungiSogno}",  "<div><a href='aggiungiSogno.php'>Aggiungi sogno</a></div>"); 
            $pagina->modificaHTML("{aggiungiDate}",  "<div><a href='aggiungiDate.php'>Aggiungi una settimana</a></div>");
            $pagina->modificaHTML("{eliminaDate}",  "<div><a href='eliminaDate.php'>Elimina una settimana</a></div>");

        }else{
            $pagina->printErrorPage("Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a>");

        }
    }else{
        $pagina->printErrorPage("Devi <a href=\"login.php\">accedere al tuo account</a> per visionare questa pagina");
    }    

    $pagina->printPage();