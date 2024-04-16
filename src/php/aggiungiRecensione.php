<?php
    require_once"newPage.php";
    require_once "functions.php"; 

    $pagina = new newPage("../html/aggiungiRecensione.html", "Nuova recensione", "Nuova recensione", "Aggiunta nuova recensione");
    use functions\functions;
    $functions = new functions();
    $errorMsg = "";

    $sogno = urldecode($_GET['sogno']);

    if($_SERVER['REQUEST_METHOD'] == "POST"){
    
        if(isset($_SESSION['user_name'])){
            if(isset($_POST['recensione']) && strlen($_POST['recensione']) != 0){

                
                $recensione = $_POST['recensione'];
                $user_name = $_SESSION['user_name'];
                
                $functions->openDBConnection();
                $stmt = $functions->getConnection()->prepare("INSERT INTO recensioni (user_name, testo, sogno) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $user_name, $recensione, $sogno); 
                $ris = $stmt->execute();
                $stmt->close();
                $functions->closeConnection();
                
                if($ris)
                    $errorMsg = "Recensione caricato con successo";
                else
                    $errorMsg = "Errore nel caricamento della recensione";
            }     
            $pagina->modificaHTML("{titoloSogno}", $sogno);
        $pagina->modificaHTML("{Errore}", $errorMsg);
        $pagina->printPage();
        }else{

            $errorMsg = "<a href=\"login.php\" role=\"button\">Esegui il login per acquistare</a>";
        }    
    
    } 
    $pagina = new newPage("../html/login.html", 
								"Login", 
								"Login - accesso", 
								"Pagina di login");

            $errorMsg = "Accedi come utente prima";
            $pagina->modificaHTML("{Error}", $errorMsg);                    

            $pagina->printPage();    