<?php
    require_once"newPage.php";
    require_once "functions.php"; 
    use functions\functions;
    $functions = new functions();

    $pagina = new newPage("../html/aggiungiSogno.html", "modifica sogno", "modifica sogno", "modifica di un sogno");
	
    
    if(isset($_SESSION['user_name']) && $_SESSION['user_name'] === "admin"){ //controllo utente        
        $errorMsg = "";
        $options= "";

		//carico le categorie
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

		$functions->openDBConnection(); //cerco il sogno per titolo
		if (isset($_GET['sogno'])) {
			$sogno = urldecode($_GET['sogno']);
			// Esegue la query utilizzando uno statement preparato
			$stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=?");
			$stmt->bind_param("s", $sogno);
			$stmt->execute();
			$risultato = $stmt->get_result();

			foreach($risultato as $row){
				$tit = $row['titolo'];
				$desc = $row['descrizione'];
				$price = $row['prezzo'];
				//$pagina->modificaHTML("{pathImg}",  "\"../assets/sogni/".$row['nomeFile']."\"");
			}

			$link="modificaSogno.php?sogno=".urlencode($row['titolo']);
			$pagina->modificaHTML("{link}", $link);

		} else {
			$errorMsg = "Errore passaggio parametri, riprovare"; 
		}
		$functions->closeConnection();

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(isset($_POST['titoloSogno']) && strlen($_POST['titoloSogno']) != 0)
                $titoloSogno = $functions->pulisciInput($_POST['titoloSogno']);

            if(isset($_POST['categoria']) && strlen($_POST['categoria']) != 0)
                $categoria = $_POST['categoria'];

            if(isset($_POST['prezzo']) && $_POST['prezzo'] >= 0)
                $prezzo = $_POST['prezzo'];

            if(isset($_POST['descrizione']) && strlen($_POST['descrizione']) != 0)
                $descrizione = $functions->pulisciInput($_POST['descrizione']);

			//Controllo esistenza del titolo nuovo da modificare
            $functions->openDBConnection();
            $stmt = $functions->getConnection()->prepare("SELECT * FROM sogni WHERE titolo=?");
            $stmt->bind_param("s", $titoloSogno);
            $stmt->execute();
            $check = $stmt->get_result();
			$functions->closeConnection();

			if($check->num_rows == 1){
				$errorMsg = "Titolo già esistente.";
				$titoloSogno = $sogno;
			}else{
				$tit = $titoloSogno;
				$desc = $descrizione;
				$price = $prezzo;

				if($risultato->num_rows == 1){ //Se esiste già lo modifico

					$functions->openDBConnection();
					$stmt = $functions->getConnection()->prepare("UPDATE sogni SET titolo=?, descrizione=?, prezzo=?, nomeFile=?, categoria=? WHERE titolo=?");
					$stmt->bind_param("ssdsss", $titoloSogno, $descrizione, $prezzo, $fileName, $categoria, $titoloSogno);
					$ris = $stmt->execute();
					$stmt->close();
					$functions->closeConnection();

					//Controlli
					if (isset($_FILES['immagineSogno'])) {

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
								if($ris)
									$errorMsg = "Modifica fatta con successo";
								else
									$errorMsg = "Errore nella modifica";
							} else {
								$errorMsg = "Si è verificato un errore durante il caricamento.";
							}
						} else {
							$errorMsg = "Sono consentiti solo file di tipo JPG, JPEG e PNG o non hai caricato nessuna immagine";
						}
					}else{
						$errorMsg = "Non hai cambiato immagine o qualcosa è andato storto";
					}
				}else{
					$pagina->printErrorPage("<p>Non esiste un sogno con questo titolo <a href=\"sogni.php\">torna a sogni<a> </p>");
				}
			}

        }
		$pagina->modificaHTML("{titolo}", $tit);
		$pagina->modificaHTML("{descrizione}", $desc);
		$pagina->modificaHTML("{prezzo}", $price);

        $pagina->modificaHTML("{Error}", "<p>".$errorMsg."</p>");
		
    }else{
        $pagina->printErrorPage("Pagina riservata all'<a href=\"login.php\">admin<a>");
    }
    $pagina->printPage();