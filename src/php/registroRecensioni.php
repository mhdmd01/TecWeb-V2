<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $pagina = new newPage("../html/registroRecensioni.html", "Registro recensioni", "Registro recensioni", "Registro recensioni");

    if(isset($_SESSION['user_name'])){
        if($_SESSION['user_name'] === "admin") {
            //Filtri
            $filtro = "data ASC"; //Di default
            $ordine = "data: ordine crescente";

            $linkUsrAsc = "Username ordine alfabetico crescente";
            $linkUsrDec = "Username ordine alfabetico decrescente";
            $linkArtAsc = "Articolo ordine alfabetico crescente";
            $linkArtDec = "Articolo ordine alfabetico decrescente";
            $linkDataAsc = "Data ordine crescente";
            $linkDataDec = "Data ordine decrescente";
            $linkStarAsc =  "Numero stelle ordine crescente";
            $linkStarDec =  "Numero stelle ordine decrescente";

            $linkUsrAscValue = "usr-asc";
            $linkUsrDecValue = "usr-desc";
            $linkArtAscValue = "art-asc";
            $linkArtDecValue = "art-desc";
            $linkDataAscValue = "date-asc";
            $linkDataDecValue = "date-desc";
            $linkStarAscValue = "star-asc";
            $linkStarDecValue = "star-desc";

            $linkUsrAscDisabled = "";
            $linkUsrDecDisabled = "";
            $linkArtAscDisabled = "";
            $linkArtDecDisabled = "";
            $linkDataAscDisabled = "";
            $linkDataDecDisabled = "";
            $linkStarAscDisabled = "";
            $linkStarDecDisabled = "";



            if (isset($_GET['filtro'])){
                //Username ordine alfabetico crescente
                if($_GET['filtro'] == "usr-asc"){
                    $filtro = "user_name ASC";
                    $ordine = "username: ordine alfabetico crescente";
                    $linkUsrAscDisabled = "selected disabled";
                }

                //Username ordine alfabetico decrescente
                if($_GET['filtro'] == "usr-desc"){
                    $filtro = "user_name DESC";
                    $ordine = "username: ordine alfabetico decrescente";
                    $linkUsrDecDisabled = "selected disabled";
                }

                //Articolo ordine alfabetico crescente
                if($_GET['filtro'] == "art-asc"){
                    $filtro = "articolo ASC";
                    $ordine = "articolo: ordine alfabetico crescente";
                    $linkArtAscDisabled = "selected disabled";
                }

                //Articolo ordine alfabetico decrescente
                if($_GET['filtro'] == "art-desc"){
                    $filtro = "user_name DESC";
                    $ordine = "articolo: ordine alfabetico decrescente";
                    $linkArtDecDisabled = "selected disabled";
                }

                //Data crescente è già di default
                if($_GET['filtro'] == "date-asc"){
                    $linkDataAscDisabled = "selected disabled";
                }

                //Data decrescente
                if($_GET['filtro'] == "date-desc"){
                    $filtro = "data DESC";
                    $ordine = "data: ordine crescente";
                    $linkDataDecDisabled = "selected disabled";
                }

                //Stelle crescente
                if($_GET['filtro'] == "star-asc"){
                    $filtro = "stelle ASC";
                    $ordine = "stelle: ordine crescente";
                    $linkStarAscDisabled = "selected disabled";
                }

                //Stelle descresente
                if($_GET['filtro'] == "star-desc"){
                    $filtro = "stelle DESC";
                    $ordine = "stelle: ordine decrescente";
                    $linkStarDecDisabled = "selected disabled";
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

            $pagina->modificaHtml("{linkUsrAscValue}", $linkUsrAscValue);
            $pagina->modificaHtml("{linkUsrDecValue}", $linkUsrDecValue);
            $pagina->modificaHtml("{linkArtAscValue}", $linkArtAscValue);
            $pagina->modificaHtml("{linkArtDecValue}", $linkArtDecValue);
            $pagina->modificaHtml("{linkDataAscValue}", $linkDataAscValue);
            $pagina->modificaHtml("{linkDataDecValue}", $linkDataDecValue);
            $pagina->modificaHtml("{linkStarAscValue}", $linkStarAscValue);
            $pagina->modificaHtml("{linkStarDecValue}", $linkStarDecValue);

            $pagina->modificaHtml("{linkUsrAscDisabled}", $linkUsrAscDisabled);
            $pagina->modificaHtml("{linkUsrDecDisabled}", $linkUsrDecDisabled);
            $pagina->modificaHtml("{linkArtAscDisabled}", $linkArtAscDisabled);
            $pagina->modificaHtml("{linkArtDecDisabled}", $linkArtDecDisabled);
            $pagina->modificaHtml("{linkDataAscDisabled}", $linkDataAscDisabled);
            $pagina->modificaHtml("{linkDataDecDisabled}", $linkDataDecDisabled);
            $pagina->modificaHtml("{linkStarAscDisabled}", $linkStarAscDisabled);
            $pagina->modificaHtml("{linkStarDecDisabled}", $linkStarDecDisabled);

            $stmt = $functions->getConnection()->prepare("SELECT * FROM recensioni ORDER BY $filtro ;");
            $stmt->execute();
            $risultato = $stmt->get_result();

            if($risultato == NULL){
                $pagina->modificaHTML("{commenti}", "<p>Nessun risultato</p>");
            }else{
                if(mysqli_num_rows($risultato) > 0){
                    $output = "";
                    foreach( $risultato as $row){
                        $output .= "<div class = \"sectionRecAc\"><p>Articolo: " . $row["articolo"] . "</p><p>User: " . $row["user_name"] . "</p><p>Data: " . $row["data"] . "</p><p>Stelle: ".$row["stelle"]."</p><p>Testo:<span class=\"full-description\"> ".$row["testo"]."</span></p></div>";
                    }
                    $pagina->modificaHTML("{commenti}", $output);
                }
            }

        }else{
            $pagina->printErrorPage("<div class=\"sectionDash\">Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a></div>");

        }
    }else{
        $pagina->printErrorPage("<div class=\"sectionDash\">Devi <a href=\"login.php\">accedere al tuo <span lang=\"en\">account</span></a> per visionare questa pagina</div>");
    }    

    $pagina->printPage();
