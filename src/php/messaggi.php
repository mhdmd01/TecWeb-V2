<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $pagina = new newPage("../html/messaggi.html", "Messaggi assistenza", "Messaggi assistenza", "Messaggi assistenza");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") {

            if(isset($_GET['filtro'])){
                $filtro = $_GET['filtro'];
                $stmt = $functions->getConnection()->prepare("SELECT * FROM assistenza WHERE motivo=? ORDER BY data_ins;");
                $stmt->bind_param("s", $filtro);
            }else{
                $stmt = $functions->getConnection()->prepare("SELECT * FROM assistenza ORDER BY data_ins;");
            }
            
            $stmt->execute();
            $risultato = $stmt->get_result();
            $stmt->close();
			$functions->closeConnection();

            if($risultato == NULL){
                $pagina->modificaHTML("{messaggi}", "Nessun messaggio ancora");
            }else{
                if(mysqli_num_rows($risultato) > 0){
                    $output = "";
                    foreach( $risultato as $row){
                        $output .= "<div class = \"sectionMessg\"><p><span class=\"titoletto\">Data</span> " . $row["data_ins"] . 
                        "</p><p><span class=\"titoletto\" lang=\"en\">Email:</span> " . $row["email"] . 
                        "</p><p><span class=\"titoletto\">Motivo:</span> " . $row["motivo"] .
                        "</p><p><span class=\"titoletto\">Descrizione:</span> " . $row["descrizione"] ."</p></div>";
                    }
                    
                    $pagina->modificaHTML("{messaggi}", $output);
                }
            }

        }else{
            $pagina->printErrorPage("<div class=\"sectionDash\">Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a></div>");

        }
    }else{
        $pagina->printErrorPage("<div class=\"sectionDash\">Devi <a href=\"login.php\">accedere al tuo <span lang=\"en\">account</span></a> per visionare questa pagina</div>");
    }    

    $pagina->printPage();
