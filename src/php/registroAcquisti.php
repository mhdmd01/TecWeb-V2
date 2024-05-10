<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $pagina = new newPage("../html/registroAcquisti.html", "Registro acquisti", "Registro acquisti", "Registro acquisti");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") {
            isset($_GET['pagina']) ? $numPagina = urldecode($_GET['pagina']) : $numPagina = 0; //Operatore ternario, se è statp passato il num pagina usa quello, sennò 0
            //$stmt = $functions->getConnection()->prepare("SELECT * FROM acquisto ORDER BY data_ins LIMIT ".$numPagina*20." OFFSET ".$numPagina*21.";");
            //$stmt->bind_param("s", $numPagina);
            $stmt = $functions->getConnection()->prepare("SELECT * FROM acquisti ORDER BY data ;");
            $stmt->execute();
            $risultato = $stmt->get_result();

            if($risultato == NULL){
                $pagina->modificaHTML("{acquisto}", "Nessun risultato");
            }else{
                if(mysqli_num_rows($risultato) > 0){
                    $output = "";
                    foreach( $risultato as $row){
                        $output .= "<div class = \"section\"><p>Articolo: " . $row["articolo"] . "</p><p>User: " . $row["user_name"] . "</p><p>Data: " . $row["data"] . "</div>";
                    }
                    $pagina->modificaHTML("{acquisto}", $output);
                }
            }

        }else{
            $pagina->printErrorPage("Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a>");

        }
    }else{
        $pagina->printErrorPage("Devi <a href=\"login.php\">accedere al tuo account</a> per visionare questa pagina");
    }    

    $pagina->printPage();