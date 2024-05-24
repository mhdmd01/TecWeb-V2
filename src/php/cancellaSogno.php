<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/cancellaSogno.html", "Conferma cancellazione", "Conferma cancellazione", "Pagina di conferma cancellazione sogno");

    use functions\functions;
    $functions = new functions();

    if(isset($_SESSION['user_name']) && $_SESSION['user_name'] === "admin"){
        $errorMsg = "";
        $messaggioSuccesso = "";
        $sogno="";
        
    
    
        $functions->openDBConnection(); // Cerco il sogno per titolo passato con GET
        
        if (isset($_GET['sogno'])) {
            $sogno = urldecode($_GET['sogno']);
            $stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=?");
            $stmt->bind_param("s", $sogno);
            $stmt->execute();
            $risultato = $stmt->get_result();
            $functions->closeConnection();

            $messaggio= "Stai per cancellare questo articolo";
            $bottone="";
    
            if($risultato && $risultato->num_rows > 0) { 

                $bottone = "<a href=\"sognoSingolo.php?sogno=".urlencode($sogno). "\" role=\"button\">Annulla</a>";
                $bottone .= "<a href=\"cancellaSogno.php?sogno=".urlencode($sogno). "&conferma=1\" role=\"button\"> Conferma</a>";
        

                 if (isset($_GET['conferma'])){
                    $functions->openDBConnection();
                    $sogno = urldecode($_GET['sogno']);
                    $stmt = $functions->getConnection()->prepare("DELETE FROM sogni WHERE titolo=?");
                    $stmt->bind_param("s", $sogno);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $functions->closeConnection();

                    $messaggio= "Hai cancellato correttamente";

                    if($res == 0){
                        $bottone="<a href=\"sogni.php\" >Torna ai sogni </a>";
                    }else{
                        $messaggioSuccesso = "Errore durante la cancellazione";
                        $bottone="<a href=\"sogni.php\" >Torna ai sogni</a>";
                    }

                }    
                
                
                $pagina->modificaHTML("{bottone}", $bottone);
    
                $pagina->modificaHTML("{titolo}", $sogno);
                
                $pagina->modificaHTML("{messaggio}", $messaggio);
    
                $pagina->modificaHTML("{messaggioSuccesso}", $messaggioSuccesso);

            } else {
                $pagina->printErrorPage("Non esiste un sogno con questo titolo <a href=\"sogni.php\">torna a sogni<a>");
            }
        } else {
            $pagina->printErrorPage("Errore nel passaggio dei parametri <a href=\"sogni.php\">torna a sogni<a>");
        }
        
    
    } else {
        $pagina->printErrorPage("Pagina riservata all'<a href=\"login.php\">admin<a>");
    }

    $pagina->printPage();