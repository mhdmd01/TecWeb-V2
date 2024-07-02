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
                $pagina->modificaHTML("{prenotazioni}", "<p>Nessun risultato</p>");
            }else{
                if(mysqli_num_rows($risultato) > 0){
                    $output = "";
                    foreach( $risultato as $row){
                        $output .= "<div class = \"sectionRegPren\"><p><span class=\"titoletto\" lang=\"en\">User</span>: " . $row["user_name"] . "</p><p><span class=\"titoletto\">Data</span>: " . $row["data"] . "</p></div>";
                    }
                    
                    $pagina->modificaHTML("{prenotazioni}", $output);
                }
            }

        }else{
            $pagina->printErrorPage("<div class=\"sectionDash\">Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a></div>");

        }
    }else{
        $pagina->printErrorPage("<div class=\"sectionDash\">Devi <a href=\"login.php\">accedere al tuo <span lang=\"en\">account</span></a> per visionare questa pagina</div>");
    }    

    $pagina->printPage();
