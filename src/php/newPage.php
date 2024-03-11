<?php

//use functions\functions;

class newPage{
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

    public function modificaHTML($segnaposto, $dati){
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


        }else if($currentPage == "../html/about.html"){
            $navBar = str_replace("{aboutLink}", "<li>About</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "About", $navBar);

        }else if($currentPage == "../html/contatta.html"){
            $navBar = str_replace("{contattaLink}", "<li>Contattaci</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Contattaci", $navBar);

        }else if($currentPage == "../html/signup.html"){
            $navBar = str_replace("{signupLink}", "<li>Registrati</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Registrati", $navBar);

            $navBar = str_replace("{loginLink}", "<li><a href=\"../php/login.php\">Accedi</a></li>", $navBar);


        }else if($currentPage == "../html/login.html"){
            $navBar = str_replace("{loginLink}", "<li>Accedi</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Accedi", $navBar);

            $navBar = str_replace("{signupLink}", "<li><a href=\"../php/signup.php\">Registrati</a></li>", $navBar);
            
        }else if($currentPage == "../html/sognoSingolo.html"){
            $navBar = str_replace("{sogniLink}", "<li>Sogni</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "<a href=\"sogni.php\">Sogni</a> >> {titolo}", $navBar);
        }else if($currentPage == "paginaErrore"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"sogni.php\">Sogni</a> >> sogno non trovato", $navBar);
        }

        // Controlla se la variabile di sessione user_id è impostata
        session_start();
        if(isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
            $navBar = str_replace("{loginLink}", "Ciao ".$_SESSION['user_name'], $navBar);
            $navBar = str_replace("{signupLink}", "", $navBar);

            $navBar = str_replace("{logoutLink}", "<li><a href=\"../php/logout.php\">Logout</a></li>", $navBar);
        }else{
            $navBar = str_replace("{logoutLink}", "", $navBar);
            $navBar = str_replace("{loginLink}", "<li><a href=\"../php/login.php\">Accedi</a></li>", $navBar);
            $navBar = str_replace("{signupLink}", "<li><a href=\"../php/signup.php\">Registrati</a></li>", $navBar);

            session_destroy();
        }


        $navBar = str_replace("{homeLink}", "<li><a href=\"index.php\">Home</a></li>", $navBar);
        $navBar = str_replace("{serviziLink}", "<li><a href=\"servizi.php\">Servizi</a></li>", $navBar);
        $navBar = str_replace("{sogniLink}", "<li><a href=\"sogni.php\">Sogni</a></li>", $navBar);
        $navBar = str_replace("{aboutLink}", "<li><a href=\"about.php\"><span lang=\"en\">About</span></a></li>", $navBar);
        $navBar = str_replace("{contattaLink}", "<li><a href=\"contatta.php\">Contattaci</a></li>", $navBar);

        $this->modificaHTML("{navBar}", $navBar);
    }

    public function printErrorPage($errorMsg){
        $this->strutturaHTML = file_get_contents("../html/generalTemplate.html"); //strutturaHTML ora contiene l'HTML di generalTemplate (il template generale)

        $this->strutturaHTML = str_replace("{titoloPagina}", "Errore" , $this->strutturaHTML);                 //Sostituisce segnaposto titolo
        //Aggiungere keyword comuni per tutte le pagine
        $this->strutturaHTML = str_replace("{metaKeywords}", "Saudade, error" , $this->strutturaHTML);         //Sostituisce segnaposto keywords 
        $this->strutturaHTML = str_replace("{metaDescription}", "Pagina di errore Saudade" , $this->strutturaHTML);               //Sostituisce segnaposto description
	    $this->strutturaHTML = str_replace("{testoFooter}", $this->testoFooter, $this->strutturaHTML);              //Sostituzione segnaposto testoFooter
        $this->printNavBar("paginaErrore");
        
        //Sostituzione contenuto principale con il segnaposto main
        $this->strutturaHTML = str_replace("{contenutoMain}", $errorMsg, $this->strutturaHTML);
    }

    public function printPage(){
        echo $this->strutturaHTML;
    }

}
