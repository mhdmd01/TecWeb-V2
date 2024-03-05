<?php

use functions\functions;

class pagina{
    private $strutturaHTML = "";
    public $testoFooter = "Saudade corporation - 2024";

    //Parametri: link alla pagina template con contenuto, titolo della pagina, keywords, descrizione
    public function __construct($template, $titoloPagina, $keywords, $description){

        $paginaTemplate = file_get_contents($template);
        $this->strutturaHTML = file_get_contents("../html/generalTemplate.html"); //strutturaHTML ora contiene l'HTML di generalTemplate (il template generale)

        $this->strutturaHTML = str_replace("{titoloPagina}", $titoloPagina , $this->strutturaHTML);                 //Sostituisce segnaposto titolo
        //Aggiungere keyword comuni per tutte le pagine
        $this->strutturaHTML = str_replace("{metaKeywords}", "Saudade, ".$keywords , $this->strutturaHTML);         //Sostituisce segnaposto keywords 
        $this->strutturaHTML = str_replace("{metaDescription}", $description , $this->strutturaHTML);               //Sostituisce segnaposto description
	    $this->strutturaHTML = str_replace("{testoFooter}", $this->testoFooter, $this->strutturaHTML);              //Sostituzione segnaposto testoFooter
        $this->printNavBar($template);
        
        //Sostituzione contenuto principale con il segnaposto main
        $this->strutturaHTML = str_replace("{contenutoMain}", $paginaTemplate, $this->strutturaHTML);
    }

    public function modificaStruttura($segnaposto, $dati){
        $this->strutturaHTML = str_replace($segnaposto, $dati, $this->strutturaHTML);
    }

    public function printNavBar($currentPage=null){
        $navBar = file_get_contents("../html/navBarTemplate.html");

        if($currentPage == "../html/index.html"){
            $navBar = str_replace("{homeLink}", "<li>Home</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Home", $navBar);

        }else if($currentPage == "../html/servizi.html"){
            $navBar = str_replace("{serviziLink}", "<li>Servizi</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Servizi", $navBar);


        }else if($currentPage == "../html/sogni.html"){
            $navBar = str_replace("{sogniLink}", "<li>Sogni</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Sogni", $navBar);


        }else if($currentPage == "../html/storia.html"){
            $navBar = str_replace("{storiaLink}", "<li>Storia</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Storia", $navBar);

        }else if($currentPage == "../html/contatta.html"){
            $navBar = str_replace("{contattaLink}", "<li>Contatta</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Contatta", $navBar);

        }

        $navBar = str_replace("{homeLink}", "<li><a href=\"index.php\">Home</a></li>", $navBar);
        $navBar = str_replace("{serviziLink}", "<li><a href=\"servizi.php\">Servizi</a></li>", $navBar);
        $navBar = str_replace("{sogniLink}", "<li><a href=\"sogni.php\">Sogni</a></li>", $navBar);
        $navBar = str_replace("{storiaLink}", "<li><a href=\"storia.php\"><span lang=\"en\">About</span></a></li>", $navBar);
        $navBar = str_replace("{contattaLink}", "<li><a href=\"contatta.php\">Contattaci</a></li>", $navBar);

        $this->modificaStruttura("{navBar}", $navBar);
    }

    public function printPage(){
        echo $this->strutturaHTML;
    }

}