<?php
    require_once"newPage.php";
    require_once "functions.php"; 
    use functions\functions;
    $functions = new functions();

    $pagina = new newPage("../html/aggiungiSogno.html", "Nuovo sogno", "Nuovo sogno", "Aggiunta nuovo sogno");
    
    if(isset($_SESSION['user_name']) && $_SESSION['user_name'] === "admin"){        
        $errorMsg = "";
        $options= "";

        $categorie = $functions->executeQuery("SELECT * FROM categorie;");
        if($categorie == null)
            $pagina->modificaHTML("{options}", "Non ci sono categorie");
        else{

            foreach($categorie as $row){
                $name= $row['nome'];
                $options .= "<option value='$name'>$name</option>";
            }

            $pagina->modificaHTML("{options}", $options);
        }


        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(isset($_POST['titoloSogno']) && strlen($_POST['titoloSogno']) != 0)
                $titoloSogno = $functions->pulisciInput($_POST['titoloSogno']);

            if(isset($_POST['categoria']) && strlen($_POST['categoria']) != 0)
                $categoria = $_POST['categoria'];

            if(isset($_POST['prezzo']) && $_POST['prezzo'] >= 0)
                $prezzo = $_POST['prezzo'];

            if(isset($_POST['descrizione']) && strlen($_POST['descrizione']) != 0)
                $descrizione = $functions->pulisciInput($_POST['descrizione']);

            //Controllo duplicato
            $functions->openDBConnection();
            $stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=?");
            $stmt->bind_param("s", $titoloSogno);
            $stmt->execute();
            $risultato = $stmt->get_result();

            if($risultato->num_rows == 0){ //Se non esiste già
                //Controlli
                if (isset($_FILES['immagineSogno'])) {

                    $uploadDir = "../assets/sogni/";                   
                    // Verifica se la cartella di destinazione esiste, altrimenti crea la cartella
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    //$fileName = $titoloSogno . '.' . pathinfo($_FILES["immagineSogno"]["name"], PATHINFO_EXTENSION);
                    $fileName = basename($_FILES["immagineSogno"]["name"]);
                    $targetFilePath = $uploadDir . $fileName;
                    
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                    
                    // Verifica se il file è un'immagine
                    $allowTypes = array("jpg", "jpeg", "png");
                    if (in_array($fileType, $allowTypes)) {
                        // Sposta il file nella cartella di destinazione
                        if (move_uploaded_file($_FILES["immagineSogno"]["tmp_name"], $targetFilePath)) {
                            $functions->openDBConnection();
                            $stmt = $functions->getConnection()->prepare("INSERT INTO sogni (titolo, descrizione, prezzo, nomeFile, categoria) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssdss", $titoloSogno, $descrizione, $prezzo, $fileName, $categoria);
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
                        $errorMsg = "Sono consentiti solo file di tipo JPG, JPEG e PNG o non hai caricato nessuna immagine";
                    }
                }else{
                    $errorMsg = "Non hai caricato nessuna immagine o qualcosa è andato storto";
                }
            }else{
                $errorMsg = "Cambiare titolo, già esistente";
            }

        }
        $pagina->modificaHTML("{Error}", "<p>".$errorMsg."</p>");
    }else{
        $pagina->printErrorPage("Pagina riservata all'<a href=\"login.php\">admin</a>");
    }

    $pagina->printPage();
    