<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $pagina = new newPage("../html/registroRecensioni.html", "Registro recensioni", "Registro recensioni", "Registro recensioni");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") {
            isset($_GET['pagina']) ? $numPagina = urldecode($_GET['pagina']) : $numPagina = 0; //Operatore ternario, se è stato passato il num pagina usa quello, sennò 0
            //DA IMPLEMENTARE
            //$stmt = $functions->getConnection()->prepare("SELECT * FROM acquisto ORDER BY data_ins LIMIT ".$numPagina*20." OFFSET ".$numPagina*21.";");
            //$stmt->bind_param("s", $numPagina);

            //Filtri
            $filtro = "data ASC"; //Di default
            $ordine = "data: ordine crescente";

            $linkUsrAsc = "<a href=\"registroRecensioni.php?filtro=usr-asc\">Username ordine alfabetico crescente</a>";
            $linkUsrDec = "<a href=\"registroRecensioni.php?filtro=usr-desc\">Username ordine alfabetico decrescente</a>";
            $linkArtAsc = "<a href=\"registroRecensioni.php?filtro=art-asc\">Articolo ordine alfabetico crescente</a>";
            $linkArtDec = "<a href=\"registroRecensioni.php?filtro=art-desc\">Articolo ordine alfabetico decrescente</a>";
            $linkDataAsc = "<a href=\"registroRecensioni.php?filtro=date-asc\">Data ordine decrescente</a>";
            $linkDataDec = "<a href=\"registroRecensioni.php?filtro=date-desc\">Data ordine decrescente</a>";
            $linkStarAsc =  "<a href=\"registroRecensioni.php?filtro=star-asc\">Numero stelle ordine crescente</a>";
            $linkStarDec =  "<a href=\"registroRecensioni.php?filtro=star-desc\">Numero stelle ordine decrescente</a>";

            if (isset($_GET['filtro'])){
                //Username ordine alfabetico crescente
                if($_GET['filtro'] == "usr-asc"){
                    $filtro = "user_name ASC";
                    $ordine = "username: ordine alfabetico crescente";
                    $linkUsrAsc = "";
                }

                //Username ordine alfabetico decrescente
                if($_GET['filtro'] == "usr-desc"){
                    $filtro = "user_name DESC";
                    $ordine = "username: ordine alfabetico decrescente";
                    $linkUsrDec = "";
                }

                //Articolo ordine alfabetico crescente
                if($_GET['filtro'] == "art-asc"){
                    $filtro = "articolo ASC";
                    $ordine = "articolo: ordine alfabetico crescente";
                    $linkArtAsc = "";
                }

                //Articolo ordine alfabetico decrescente
                if($_GET['filtro'] == "art-desc"){
                    $filtro = "user_name DESC";
                    $ordine = "articolo: ordine alfabetico decrescente";
                    $linkArtDec = "";
                }

                //Data crescente è già di default
                if($_GET['filtro'] == "date-asc"){
                    $linkDataAsc = "";
                }

                //Data decrescente
                if($_GET['filtro'] == "date-desc"){
                    $filtro = "data DESC";
                    $ordine = "data: ordine crescente";
                    $linkDataDec = "";
                }

                //Stelle crescente
                if($_GET['filtro'] == "star-asc"){
                    $filtro = "stelle ASC";
                    $ordine = "stelle: ordine crescente";
                    $linkStarAsc = "";
                }

                //Stelle descresente
                if($_GET['filtro'] == "star-desc"){
                    $filtro = "stelle DESC";
                    $ordine = "stelle: ordine decrescente";
                    $linkStarDesc = "";
                }
            }

            $pagina->modificaHTML("{ordine}", $ordine);

            $pagina->modificaHTML("{linkUsrAsc}", $linkUsrAsc);
            $pagina->modificaHTML("{linkUsrDec}", $linkUsrDec);
            $pagina->modificaHTML("{linkArtAsc}", $linkArtAsc);
            $pagina->modificaHTML("{linkArtDec}", $linkArtDec);
            $pagina->modificaHTML("{linkDataAsc}", $linkDataAsc);
            $pagina->modificaHTML("{linkDataDec}", $linkDataDec);
            $pagina->modificaHTML("{linkStarAsc}", $linkStarAsc);
            $pagina->modificaHTML("{linkStarDec}", $linkStarDec);

            $stmt = $functions->getConnection()->prepare("SELECT * FROM recensioni ORDER BY $filtro ;");
            $stmt->execute();
            $risultato = $stmt->get_result();

            if($risultato == NULL){
                $pagina->modificaHTML("{acquisto}", "Nessun risultato");
            }else{
                if(mysqli_num_rows($risultato) > 0){
                    $output = "";
                    foreach( $risultato as $row){
                        $output .= "<div class = \"section\"><p>Articolo: " . $row["articolo"] . "</p><p>User: " . $row["user_name"] . "</p><p>Data: " . $row["data"] . "<p>Stelle: ".$row["stelle"]."</p>" . "<p>Testo: ".$row["testo"]."</p>" . "</div>";
                    }
                    $pagina->modificaHTML("{commenti}", $output);
                }
            }

        }else{
            $pagina->printErrorPage("Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a>");

        }
    }else{
        $pagina->printErrorPage("Devi <a href=\"login.php\">accedere al tuo account</a> per visionare questa pagina");
    }    

    $pagina->printPage();