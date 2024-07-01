<?php
require_once "newPage.php";
require_once "functions.php"; 
use functions\functions;
$functions = new functions();

$pagina = new newPage("../html/aggiungiSogno.html", "modifica sogno", "modifica sogno", "modifica di un sogno");
$poster = "<p>Modifica sogno</p>";

if(isset($_SESSION['user_name']) && $_SESSION['user_name'] === "admin"){
    $errorMsg = "";
    $messaggioSuccesso = "";
    $options= "";
    $fileName = null;

    // Carico le categorie
    $categorie = $functions->executeQuery("SELECT * FROM categorie;");
    if($categorie == null) {
        $pagina->modificaHTML("{options}", "Non ci sono categorie");
    } else {
        foreach($categorie as $row){
            $name= $row['nome'];
            $options .= "<option value='$name'>$name</option>";
        }
        $pagina->modificaHTML("{options}", $options);
    }

    $functions->openDBConnection(); // Cerco il sogno per titolo passato con GET
    if (isset($_GET['sogno'])) {
        $sogno = urldecode($_GET['sogno']);
        $stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=?");
        $stmt->bind_param("s", $sogno);
        $stmt->execute();
        $risultato = $stmt->get_result();
        $functions->closeConnection();

        if($risultato && $risultato->num_rows > 0) {
            foreach($risultato as $row){
                $tit = $row['titolo'];
                $desc = $row['descrizione'];
                $price = $row['prezzo'];
                //salvo nome file
                $fileName = $row['nomeFile'];
                //$pagina->modificaHTML("{pathImg}",  "\"../assets/sogni/".$row['nomeFile']."\"");
            }

            $link = "modificaSogno.php?sogno=" . urlencode($sogno);

            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $titoloSogno = $sogno; 
                $categoria = null;
                $prezzo = null;
                $descrizione = null;
                //$fileName = null; 

                if(isset($_POST['titoloSogno']) && strlen($_POST['titoloSogno']) != 0)
                    $titoloSogno = $functions->pulisciInput($_POST['titoloSogno']);

                if(isset($_POST['categoria']) && strlen($_POST['categoria']) != 0)
                    $categoria = $_POST['categoria'];

                if(isset($_POST['prezzo']) && $_POST['prezzo'] >= 0)
                    $prezzo = $_POST['prezzo'];

                if(isset($_POST['descrizione']) && strlen($_POST['descrizione']) != 0)
                    $descrizione = $functions->pulisciInput($_POST['descrizione']);

                // Controllo esistenza del titolo nuovo da modificare, escludendo il sogno corrente
                $functions->openDBConnection();
                $stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=? AND titolo!=?");
                $stmt->bind_param("ss", $titoloSogno, $sogno);
                $stmt->execute();
                $check = $stmt->get_result();
                $functions->closeConnection();

                if($check->num_rows > 0) {
                    $errorMsg = "Titolo già esistente per un altro sogno.";
                } else {
                    $tit = $titoloSogno;
                    $desc = $descrizione;
                    $price = $prezzo;
          
                    // Controlli
                    if (isset($_FILES['immagineSogno']) && $_FILES['immagineSogno']['error'] == UPLOAD_ERR_OK) {
                        $uploadDir = "../assets/sogni/";                   
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $fileName = basename($_FILES["immagineSogno"]["name"]);
                        $targetFilePath = $uploadDir . $fileName;
                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                        $allowTypes = array("jpg", "jpeg", "png");
                        if (in_array($fileType, $allowTypes)) {
                            if (move_uploaded_file($_FILES["immagineSogno"]["tmp_name"], $targetFilePath)) {            
                                $messaggioSuccesso = "Immagine caricata con successo";
                            } else {
                                $errorMsg = "Si è verificato un errore durante il caricamento dell'immagine.";
                            }
                        } else {
                            $errorMsg = "Sono consentiti solo file di tipo JPG, JPEG e PNG o non hai caricato nessuna immagine";
                        }
                    } else {
                        $errorMsg = "Non hai modificato l'immagine";
                    }

                    $functions->openDBConnection();
                    $stmt = $functions->getConnection()->prepare("UPDATE sogni SET titolo=?, descrizione=?, prezzo=?, nomeFile=?, categoria=? WHERE titolo=?");
                    $stmt->bind_param("ssdsss", $titoloSogno, $descrizione, $prezzo, $fileName, $categoria, $sogno);
                    $ris = $stmt->execute();
                    $stmt->close();
                    $functions->closeConnection();

                    if ($ris) {
                        $messaggioSuccesso = "Modifica effettuata con successo";
                        $link = "modificaSogno.php?sogno=" . urlencode($titoloSogno);
                    } else {
                        $errorMsg = "Errore nella modifica";
                    }
                }
            }
            $pagina->modificaHTML("{poster}", $poster);
            $bottone = "Conferma modifica";
            $pagina->modificaHTML("{bottone}", $bottone);

			$pagina->modificaHTML("{link}", $link);

			$pagina->modificaHTML("{titolo}", $tit);
			$pagina->modificaHTML("{descrizione}", $desc);
			$pagina->modificaHTML("{prezzo}", $price);

			$pagina->modificaHTML("{messaggioSuccesso}", $messaggioSuccesso);
			$pagina->modificaHTML("{Error}", $errorMsg);
        } else {
            $pagina->printErrorPage("Non esiste un sogno con questo titolo <a href=\"sogni.php\">torna a sogni<a>");
        }
    } else {
        $pagina->printErrorPage("Errore nel passaggio dei parametri <a href=\"sogni.php\">torna a sogni<a>");
    }
    

} else {
    $pagina->printErrorPage("<div class=\"sectionDash\">Pagina riservata all'<a href=\"login.php\"><span lang=\"en\">admin</span></a></div>");
}
$pagina->printPage();

