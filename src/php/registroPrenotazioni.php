<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $pagina = new newPage("../html/registroPrenotazioni.html", "Registro prenotazioni", "Registro prenotazioni", "Registro prenotazioni");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") {
            $stmt = $functions->getConnection()->prepare("SELECT * FROM prenotazioni WHERE user_name IS NOT NULL ORDER BY data;");
            $stmt->execute();
            $risultato = $stmt->get_result();

            if($risultato == NULL){
                $pagina->modificaHTML("{prenotazioni}", "Nessun risultato");
            }else{
                if(mysqli_num_rows($risultato) > 0){
                    $output = "";
                    foreach( $risultato as $row){
                        $output .= "<div class = \"sectionRegPren\"><p>User: " . $row["user_name"] . "</p><p>Data: " . $row["data"] . "</p></div>";
                    }
                    
                    $pagina->modificaHTML("{prenotazioni}", $output);
                }
            }

        }else{
            $pagina->printErrorPage("Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a>");

        }
    }else{
        $pagina->printErrorPage("Devi <a href=\"login.php\">accedere al tuo account</a> per visionare questa pagina");
    }    

    $pagina->printPage();
