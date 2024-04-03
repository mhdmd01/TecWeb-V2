<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/index.html", "Home", "Home", "Pagina home del sito ufficiale di Saudade");

    use functions\functions;
    $functions = new functions();
    $recenz = $functions->executeQuery("SELECT * FROM recensioni ORDER BY data_ins;");

    if($recenz == null){
        //$pagina->printErrorPage("Ancora nessuna recensione");
        $rec = "Ancora nessuna recensione";
        $pagina->modificaHTML("{recensioni}", $rec);
    }        
    else{
        $rec = "";

        foreach( $recenz as $row){
            $rec .= file_get_contents("../html/recensioneTemp.html");

            $rec = str_replace("{utente}", $row['user_name'], $rec);
            $rec = str_replace("{sogno}", $row['sogno'], $rec);
            $rec = str_replace("{testo}", $row['testo'], $rec);
        }

        $pagina->modificaHTML("{recensioni}", $rec);
    }

    $pagina->printPage();