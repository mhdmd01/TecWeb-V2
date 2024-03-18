<?php
    require_once"newPage.php";
    require_once "functions.php"; 

    $pagina = new newPage("../html/aggiungiSogno.html", "Nuovo sogno", "Nuovo sogno", "Aggiunta nuovo sogno");
    use functions\functions;
    $functions = new functions();
    $errorMsg = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['titoloSogno']) && strlen($_POST['titoloSogno']) != 0)
            $titoloSogno = $_POST['titoloSogno'];

        if(isset($_POST['prezzo']) && $_POST['prezzo'] >= 0)
            $prezzo = $_POST['prezzo'];

        if(isset($_POST['descrizione']) && strlen($_POST['descrizione']) != 0)
            $descrizione = $_POST['descrizione'];

        //Controlli
        if (isset($_FILES['immagineSogno'])) {
            $uploadDir = "../assets/sogni/";
            $fileName = $titoloSogno . '.' . pathinfo($_FILES["immagineSogno"]["name"], PATHINFO_EXTENSION);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            
            // Verifica se il file è un'immagine
            $allowTypes = array("jpg", "jpeg", "png", "gif");
            if (in_array($fileType, $allowTypes)) {
                // Sposta il file nella cartella di destinazione
                if (move_uploaded_file($_FILES["immagineSogno"]["tmp_name"], $targetFilePath)) {
                    $functions->openDBConnection();
                    $stmt = $functions->getConnection()->prepare("INSERT INTO sogni (titolo, descrizione, prezzo, estensioneFile) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssds", $titoloSogno, $descrizione, $prezzo, $fileType);
                    $ris = $stmt->execute();
                    $stmt->close();
                    $functions->closeConnection();

                    if($ris)
                        $errorMsg = "File caricato con successo";
                    else
                        $errorMsg = "Errore caricamento file";
                } else {
                    $errorMsg = "Si è verificato un errore durante il caricamento dell'immagine.";
                }
            } else {
                $errorMsg = "Sono consentiti solo file di tipo JPG, JPEG, PNG e GIF.";
            }
        }
    }

    $pagina->modificaHTML("{Error}", $errorMsg);
    $pagina->printPage();