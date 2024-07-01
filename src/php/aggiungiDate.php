<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();

    $pagina = new newPage("../html/aggiungiDate.html", "Aggiungi date", "Aggiungi date", "Aggiungi date");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") { 
            //Date aggiunte
            $result = $functions->executeQuery("SELECT data FROM prenotazioni ORDER BY data DESC LIMIT 1");

            if ($result !== null) {
                $row = mysqli_fetch_assoc($result);
                
                if ($row) {
                    $giorni = "";
                    $data = $row['data'];

                    //Faccio 5 volte INSERT INTO con controllo
                    for ($i = 3; $i <= 7; $i++) {
                        //Creazione query
                        $query = "INSERT INTO prenotazioni (data, user_name) VALUES ";
                         $data_inserimento = strtotime($data . " +" . $i . " days");
                        $giorni .= " - ".$formatted_date = date('Y-m-d', $data_inserimento);
                        $query .= "('" . $formatted_date . "', NULL); ";

                        if(date('l', strtotime($data)) != "Saturday" && date('l', strtotime($data)) != "Sunday"){
                            //Esecuzione query
                            $functions->openDBConnection();
                            $risultato = mysqli_query($functions->getConnection(), $query) or die("Errore di connessione" . mysqli_error($functions->getConnection()));
                        }
                    }


                    if($risultato == true){
                        $pagina->modificaHTML("{msg}", "Nuove date caricate correttamente: ". substr($giorni, 2)); //Substr serve solo per togliere il primo "-"
                    }else{
                        $pagina->modificaHTML("{errore}", "Errore nell caricamento dei nuovi giorni");
                    }

                } else {
                    $pagina->modificaHTML("{errore}", "Nessuna data trovata");
                }
            } else {
                $pagina->modificaHTML("{errore}", "Errore durante l'esecuzione della query");
            }

            //Date passate da rimuovere ?
        }
    }else{
        $pagina->printErrorPage("<p>Pagina riservata all'admin</p>");
    }    

    $pagina->modificaHTML("{errore}", "");
    $pagina->printPage();

    