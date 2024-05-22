<?php
require_once "newPage.php";
require_once "functions.php"; 
use functions\functions;
$functions = new functions();

$pagina = new newPage("../html/aggiungiSogno.html", "modifica sogno", "modifica sogno", "modifica di un sogno");

if(isset($_SESSION['user_name']) && $_SESSION['user_name'] === "admin"){
    $errorMsg = "";
    $messaggioSuccesso = "";
    $options= "";

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
                //$pagina->modificaHTML("{pathImg}",  "\"../assets/sogni/".$row['nomeFile']."\"");
            }

            $link = "modificaSogno.php?sogno=" . urlencode($sogno);

            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $titoloSogno = $sogno; // Default to current title
                $categoria = null;
                $prezzo = null;
                $descrizione = null;
                $fileName = null; // Add this if file handling is necessary

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

                    $functions->openDBConnection();
                    $stmt = $functions->getConnection()->prepare("UPDATE sogni SET titolo=?, descrizione=?, prezzo=?, nomeFile=?, categoria=? WHERE titolo=?");
                    $stmt->bind_param("ssdsss", $titoloSogno, $descrizione, $prezzo, $fileName, $categoria, $sogno);
                    $ris = $stmt->execute();
                    $stmt->close();
                    $functions->closeConnection();

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
                                if($ris) {
                                    $messaggioSuccesso = "Modifica effettuata con successo";
                                    $link = "modificaSogno.php?sogno=" . urlencode($titoloSogno);
                                } else {
                                    $errorMsg = "Errore nella modifica";
                                }
                            } else {
                                $errorMsg = "Si è verificato un errore durante il caricamento.";
                            }
                        } else {
                            $errorMsg = "Sono consentiti solo file di tipo JPG, JPEG e PNG o non hai caricato nessuna immagine";
                        }
                    } else {
                        if ($ris) {
                            $messaggioSuccesso = "Modifica effettuata con successo";
                            $link = "modificaSogno.php?sogno=" . urlencode($titoloSogno);
                        } else {
                            $errorMsg = "Errore nella modifica";
                        }
                    }
                }
            }
        } else {
            $pagina->printErrorPage("<p>Non esiste un sogno con questo titolo <a href=\"sogni.php\">torna a sogni<a> </p>");
        }
    } else {
        $errorMsg = "Errore passaggio parametri, riprovare"; 
    }
    $pagina->modificaHTML("{link}", $link);

    $pagina->modificaHTML("{titolo}", $tit);
    $pagina->modificaHTML("{descrizione}", $desc);
    $pagina->modificaHTML("{prezzo}", $price);

    $pagina->modificaHTML("{messaggioSuccesso}", $messaggioSuccesso);
    $pagina->modificaHTML("{Error}", $errorMsg);

} else {
    $pagina->printErrorPage("Pagina riservata all'<a href=\"login.php\">admin<a>");
}
$pagina->printPage();

