<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/prenotazione.html", "Prenotazione", "Prenotazione chip", "Pagina prenotazione chip");

    use functions\functions;
    $functions = new functions();

    if (isset($_GET['data']) && !is_null($_GET['data'])) {
        echo "<h1>Da sistemare</h1>";
        echo $data = $_GET['data'];
        // Esegue la query utilizzando uno statement preparato
        $stmt = $functions->getConnection()->prepare("SELECT * FROM prenotazioni WHERE data=?");
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $risultato = $stmt->get_result();

        // Verifica se sono presenti risultati
        if ($risultato->num_rows > 0) {
            $pagina->modificaHTML("{dataPrenotazione}", $data);
        } else {
            $pagina->printErrorPage("Nessuna prenotazione trovata per la data specificata");
        }
    } else {
        echo "Errore: parametro data mancante.";
    }

    $pagina->printPage();