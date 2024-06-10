<?php
    require_once("newPage.php");
    require_once("functions.php");

    $pagina = new newPage("../html/sogni.html", "Sogni", "Sogni", "Elenco sogni Saudade");

    use functions\functions;
    $functions = new functions();

    //FILTRO
    $filtro = isset($_GET['categoria']) ? ($_GET['categoria']) : '';

    //BARRA DI RICERCA
    $item = isset($_GET['search']) ? $functions->pulisciInput($_GET['search']) : '';
    $sql = "SELECT * FROM sogni WHERE titolo LIKE '%$item%' OR descrizione LIKE '%$item%'";
    if (!empty($filtro)) {
        $sql = "SELECT * FROM sogni WHERE categoria LIKE '%$filtro%'";
    }
    $sogni = $functions->executeQuery($sql);

    //Filtro per categorie
    $options= "";
    $categorie = $functions->executeQuery("SELECT * FROM categorie;");
    if($categorie == null)
        $pagina->modificaHTML("{options}", "Non ci sono categorie");
    else{
        $name= '';
        //$options .="<option value='$name'> $name</option>";
        foreach($categorie as $row){
            if($filtro != $row['nome']){
                $name= $row['nome'];
                $options .= "<option value='$name'> $name</option>";
            }else{
                $name= $row['nome'];
                $options .= "<option value='$name' disabled selected> $name</option>";
            }

        }
        $pagina->modificaHTML("{chosen}", $filtro);
        $pagina->modificaHTML("{options}", $options);
    }

    //Vecchia select per all senza cerca e filtro
    //$sogni = $functions->executeQuery("SELECT * FROM sogni;");

    if($sogni == null)
        //$pagina->printErrorPage("Non ci sono sogni disponibili al momento o per la ricerca fatta, riprovare più tardi o tornare alla pagina <a href=\"index.php\">home</a>");
        $annuncio="<div> Non ci sono sogni disponibili al momento o per la ricerca fatta, riprovare più tardi o tornare alla pagina <a href=\"index.php\">home</a> </div>";
    else{
        $annuncio = "";

        foreach( $sogni as $row){
            $annuncio .= file_get_contents("../html/annuncioSogno.html");

            $annuncio = str_replace("{linkSogno}", "sognoSingolo.php?sogno=".urlencode($row['titolo']), $annuncio);
            $annuncio = str_replace("{titolo}", $row['titolo'], $annuncio);
            $annuncio = str_replace("{prezzo}", $row['prezzo']." &#8364;", $annuncio);
            $annuncio = str_replace("{pathImg}", "\"../assets/sogni/".$row['nomeFile']."\"", $annuncio);
            
        }
        //$pagina->modificaHTML("{ricerca}", $item);
        //$pagina->modificaHTML("{elencoSogni}", $annuncio);
    }
    $pagina->modificaHTML("{ricerca}", $item);
    $pagina->modificaHTML("{elencoSogni}", $annuncio);
    
    $pagina->printPage();