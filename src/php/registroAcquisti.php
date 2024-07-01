<?php
    require_once("newPage.php");
    require_once("functions.php");

    use functions\functions;
    $functions = new functions();
    $functions->openDBConnection();

    $pagina = new newPage("../html/registroAcquisti.html", "Registro acquisti", "Registro acquisti", "Registro acquisti");

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

            $linkUsrAscValue = "usr-asc";
            $linkUsrDecValue = "usr-desc";
            $linkArtAscValue = "art-asc";
            $linkArtDecValue = "art-desc";
            $linkDataAscValue = "date-asc";
            $linkDataDecValue = "date-desc";

            $linkUsrAscDisabled = "";
            $linkUsrDecDisabled = "";
            $linkArtAscDisabled = "";
            $linkArtDecDisabled = "";
            $linkDataAscDisabled = "";
            $linkDataDecDisabled = "";

            if (isset($_GET['filtro'])){
                //Username ordine alfabetico crescente
                if($_GET['filtro'] == "usr-asc"){
                    $filtro = "user_name ASC";
                    $ordine = "username: ordine alfabetico crescente";
                    $linkUsrAscDisabled = "disabled selected";
                }

                //Username ordine alfabetico decrescente
                if($_GET['filtro'] == "usr-desc"){
                    $filtro = "user_name DESC";
                    $ordine = "username: ordine alfabetico decrescente";
                    $linkUsrDecDisabled = "disabled selected";
                }

                //Articolo ordine alfabetico crescente
                if($_GET['filtro'] == "art-asc"){
                    $filtro = "articolo ASC";
                    $ordine = "articolo: ordine alfabetico crescente";
                    $linkArtAscDisabled = "disabled selected";
                }

                //Articolo ordine alfabetico decrescente
                if($_GET['filtro'] == "art-desc"){
                    $filtro = "user_name DESC";
                    $ordine = "articolo: ordine alfabetico decrescente";
                    $linkArtDecDisabled = "disabled selected";
                }

                //Data crescente è già di default
                if($_GET['filtro'] == "date-asc"){
                    $linkDataAscDisabled = "disabled selected";
                }

                //Data decrescente
                if($_GET['filtro'] == "date-desc"){
                    $filtro = "data DESC";
                    $ordine = "data: ordine crescente";
                    $linkDataDecDisabled = "disabled selected";
                }
            }

            $pagina->modificaHTML("{ordine}", $ordine);

            $pagina->modificaHTML("{linkUsrAsc}", $linkUsrAsc);
            $pagina->modificaHTML("{linkUsrDec}", $linkUsrDec);
            $pagina->modificaHTML("{linkArtAsc}", $linkArtAsc);
            $pagina->modificaHTML("{linkArtDec}", $linkArtDec);
            $pagina->modificaHTML("{linkDataAsc}", $linkDataAsc);
            $pagina->modificaHTML("{linkDataDec}", $linkDataDec);

            $pagina->modificaHTML("{linkUsrAscValue}", $linkUsrAscValue);
            $pagina->modificaHTML("{linkUsrDecValue}", $linkUsrDecValue);
            $pagina->modificaHTML("{linkArtAscValue}", $linkArtAscValue);
            $pagina->modificaHTML("{linkArtDecValue}", $linkArtDecValue);
            $pagina->modificaHTML("{linkDataAscValue}", $linkDataAscValue);
            $pagina->modificaHTML("{linkDataDecValue}", $linkDataDecValue);

            $pagina->modificaHTML("{linkUsrAscDisabled}", $linkUsrAscDisabled);
            $pagina->modificaHTML("{linkUsrDecDisabled}", $linkUsrDecDisabled);
            $pagina->modificaHTML("{linkArtAscDisabled}", $linkArtAscDisabled);
            $pagina->modificaHTML("{linkArtDecDisabled}", $linkArtDecDisabled);
            $pagina->modificaHTML("{linkDataAscDisabled}", $linkDataAscDisabled);
            $pagina->modificaHTML("{linkDataDecDisabled}", $linkDataDecDisabled);

            $stmt = $functions->getConnection()->prepare("SELECT * FROM acquisti ORDER BY $filtro ;");
            $stmt->execute();
            $risultato = $stmt->get_result();

            if($risultato == NULL || mysqli_num_rows($risultato) == 0){
                $pagina->modificaHTML("{acquisto}", "Nessun risultato");
            }else{
                if(mysqli_num_rows($risultato) > 0){
                    $output = "";
                    foreach( $risultato as $row){
                        $output .= "<div class = \"sectionRecAc\"><p>Articolo: " . $row["articolo"] . "</p><p>User: " . $row["user_name"] . "</p><p>Data: " . $row["data"] . "</p></div>";
                    }
                    $pagina->modificaHTML("{acquisto}", $output);
                }
            }

        }else{
            $pagina->printErrorPage("<div class=\"sectionDash\">Pagina riservata. Torna alla <a href=\"index.php\">home</a> o alla tua <a href=\"dashboardUser.php\">area personale</a></div>");

        }
    }else{
        $pagina->printErrorPage("<div class=\"sectionDash\">Devi <a href=\"login.php\">accedere al tuo <span lang=\"en\">account</span></a> per visionare questa pagina</div>");
    }    

    $pagina->printPage();
