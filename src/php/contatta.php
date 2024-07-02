<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/contatta.html", "Contatti", "Contatti", "Pagina contatti del sito ufficiale di Saudade");
    use functions\functions;
    $functions = new functions();
    $successMsg="";
    $errorMsg = "";
    $email= "";
    $motivo= "";
    $messaggio= "";
    

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['email']) && $functions->pulisciinput(strlen($_POST['email'])) != 0)
            $email = $functions->pulisciinput(strlen($_POST['email']));

        if(isset($_POST['motivo']) && strlen($_POST['motivo']) != 0)
            $motivo = $_POST['motivo'];

        if(isset($_POST['messaggio']) && $functions->pulisciinput(strlen($_POST['messaggio'])) != 0)
            $messaggio = $functions->pulisciinput(strlen($_POST['messaggio']));

            $sql = "SELECT * FROM assistenza WHERE email LIKE '%$email%' AND descrizione LIKE '%$messaggio%'";
            $risultato = $functions->executeQuery($sql);

            if($risultato == null){ 
                $functions->openDBConnection();
                $stmt = $functions->getConnection()->prepare("INSERT INTO assistenza (email, motivo, descrizione) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $motivo, $messaggio);
                $ris = $stmt->execute();
                $stmt->close();
                $functions->closeConnection();

                if($ris)
                    $successMsg = "Messaggio mandato con successo";
                else
                    $errorMsg = "Errore nel caricamento del messaggio";
            }else{
                $errorMsg = "Errore, hai già mandato questo messaggio";
            }
    } 
                                              
    $pagina->modificaHTML("{Error}", $errorMsg);
    $pagina->modificaHTML("{messaggioSuccesso}", $successMsg);

    $pagina->printPage();