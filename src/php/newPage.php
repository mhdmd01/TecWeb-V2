<?php

//use functions\functions;

class newPage{
    private $strutturaHTML = "";
    public $testoFooter = "<p>Saudade <span lang=\"en\">corporation</span> - 2024 </p>";

    //Parametri: link alla pagina template con contenuto, titolo della pagina, keywords, descrizione
    public function __construct($template, $titoloPagina, $keywords, $description){

        $paginaTemplate = file_get_contents($template);
        $this->strutturaHTML = file_get_contents("../html/generalTemplate.html"); //strutturaHTML ora contiene l'HTML di generalTemplate (il template generale)

        $this->strutturaHTML = str_replace("{titoloPagina}", $titoloPagina , $this->strutturaHTML);                 //Sostituisce segnaposto titolo
        //Aggiungere keyword comuni per tutte le pagine
        $this->strutturaHTML = str_replace("{metaKeywords}", "Saudade, ".$keywords , $this->strutturaHTML);         //Sostituisce segnaposto keywords 
        $this->strutturaHTML = str_replace("{metaDescription}", $description , $this->strutturaHTML);               //Sostituisce segnaposto description
        $this->printNavBar($template);

        if($template == "../html/contatta.html"){ //Per rimuovere link circolare in contatta
            $this->strutturaHTML = str_replace("{testoFooter}", $this->testoFooter, $this->strutturaHTML);
        }else{
            $this->strutturaHTML = str_replace("{testoFooter}", $this->testoFooter."<p> Hai bisogno di aiuto? <a href=\"../php/contatta.php\">Contattaci</a></p>", $this->strutturaHTML);
        }
        
        //Sostituzione contenuto principale con il segnaposto main
        $this->strutturaHTML = str_replace("{contenutoMain}", $paginaTemplate, $this->strutturaHTML);
    }

    public function modificaHTML($segnaposto, $dati){
        $this->strutturaHTML = str_replace($segnaposto, $dati, $this->strutturaHTML);
    }

    public function printNavBar($currentPage=null){
        // Avvia una sessione, se non è già attiva
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            // $_SESSION['user_name']="user";  // VA CANCELLATA A FINE TEST
        }
        $navBar = file_get_contents("../html/navBarTemplate.html");

        if($currentPage == "../html/index.html"){
            $navBar = str_replace("{homeLink}", "<li><span lang=\"en\">Home</span></li>", $navBar);
            $navBar = str_replace("{logo}", "<img id=\"logo\" alt=\"logo dell'azienda\" src=\"../assets/logo.svg\">
                <img id=\"logo_stampa\" alt=\"logo dell'azienda\" src=\"../assets/logo_small.svg\">", $navBar);
            $navBar = str_replace("{breadcrumb}", "<span lang=\"en\">Home</span>", $navBar);
            
            $this->strutturaHTML = str_replace("{langPlaceholder}", "lang=\"en\"", $this->strutturaHTML);

        }else if($currentPage == "../html/servizi.html"){
            $navBar = str_replace("{serviziLink}", "<li>Servizi</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Servizi", $navBar);


        }else if($currentPage == "../html/sogni.html"){
            $navBar = str_replace("{breadcrumb}", "Sogni", $navBar);


        }else if($currentPage == "../html/storia.html"){
            $navBar = str_replace("{storiaLink}", "<li>Storia</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Storia", $navBar);

        }else if($currentPage == "../html/contatta.html"){
            $navBar = str_replace("{contattaLink}", "<li>Contattaci</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Contattaci", $navBar);

        }else if($currentPage == "../html/signup.html"){
            $navBar = str_replace("{signupLink}", "<li class=\"spostaSign\">Registrati</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Registrati", $navBar);

            $navBar = str_replace("{loginLink}", "<li class=\"spostaSign\"><a href=\"../php/login.php\">Accedi</a></li>", $navBar);


        }else if($currentPage == "../html/login.html"){
            $navBar = str_replace("{loginLink}", "<li class=\"spostaSign\">Accedi</li>", $navBar);
            $navBar = str_replace("{breadcrumb}", "Accedi", $navBar);

            $navBar = str_replace("{signupLink}", "<li class=\"spostaSign\"><a href=\"../php/signup.php\">Registrati</a></li>", $navBar);
            
        }else if($currentPage == "../html/sognoSingolo.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"sogni.php\">Sogni</a> &gt&gt {titolo}", $navBar);

        }else if($currentPage == "paginaErrore"){
            $navBar = str_replace("{breadcrumb}", "Pagina di errore", $navBar);

        }else if($currentPage == "../html/error404.html"){
            $navBar = str_replace("{breadcrumb}", "Pagina non trovata", $navBar);

        }else if($currentPage == "../html/dashboardUser.html" || $currentPage == "../html/dashboardAdmin.html"){
            $navBar = str_replace("{breadcrumb}", "Area personale", $navBar);
            if(isset($_SESSION['user_name'])) {
                    $navBar = str_replace("{loginLink}", "<li>Ciao ".$_SESSION['user_name']."</li>", $navBar);
            }else{
                $navBar = str_replace("{loginLink}", "<li><a href=\"../php/login.php\">Accedi</a></li>", $navBar);
            }
        }else if($currentPage == "../html/aggiungiSogno.html"){
            $navBar = str_replace("{breadcrumb}", "Nuovo sogno", $navBar);

        }else if($currentPage == "../html/sognoNonTrovato.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"sogni.php\">Sogni</a> &gt&gt Sogno non disponibile", $navBar);

        }else if($currentPage == "../html/acquistoGiaEffettuato.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"sogni.php\">Sogni</a> &gt&gt Acquisto già effettuato", $navBar);

        }else if($currentPage == "../html/calendario.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"servizi.php\">Servizi</a> &gt&gt Prenotazione", $navBar);

        }else if($currentPage == "../html/prenotazione.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"servizi.php\">Servizi</a> &gt&gt Prenotazione {dataPrenotazione}", $navBar);

        }else if($currentPage == "../html/confermaPrenotazione.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"servizi.php\">Servizi</a> &gt&gt Prenotazione {dataPrenotazione}", $navBar);

        }else if($currentPage ==  "../html/aggiungiRecensione.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"dashboardUser.php\">Area personale</a> &gt&gt Recensione {titoloSogno}", $navBar);

        }else if($currentPage == "../html/aggiungiDate.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"dashboardAdmin.php\">Area personale admin</a> &gt&gt Aggiungi date", $navBar);

        }else if($currentPage == "../html/registroAcquisti.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"dashboardAdmin.php\"><span lang=\"en\">Dashboard admin</span></a> &gt&gt Registro acquisti", $navBar);

        }else if($currentPage == "../html/registroRecensioni.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"dashboardAdmin.php\"><span lang=\"en\">Dashboard admin</span></a> &gt&gt Registro recensioni", $navBar);

        }else if($currentPage == "../html/cancellaSogno.html"){
            $navBar = str_replace("{breadcrumb}", "<a href=\"sogni.php\">Sogni</a> &gt&gt {titolo} &gt&gt cancella", $navBar);

        }else if($currentPage == "../html/confermaAcquisto.html"){
            $navBar = str_replace("{breadcrumb}", "Acquisto effettuato", $navBar);

        }else if($currentPage == "../html/registroPrenotazioni.html"){
            $navBar = str_replace("{breadcrumb}", "Registro prenotazioni", $navBar);

        }

        //Se non è stato modificato il tag title in inglese allora lo rimuovo perchè non servirà
        $this->strutturaHTML = str_replace("{langPlaceholder}", "", $this->strutturaHTML);

        if($currentPage == "../html/sognoSingolo.html" || $currentPage == "../html/sogni.html" || $currentPage == "../html/acquistaSogno.html" || $currentPage == "../html/confermaAcquisto.html")
            $navBar = str_replace("{sogniLink}", "<li>Sogni</li>", $navBar);

        // Controlla se la variabile di sessione user_id è impostata
        if(isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
            if($_SESSION['user_name'] == "admin")
                $navBar = str_replace("{loginLink}", "<li><a href=\"dashboardAdmin.php\">Ciao ".$_SESSION['user_name']."</a></li>", $navBar);
            else
                $navBar = str_replace("{loginLink}", "<li><a href=\"dashboardUser.php\">Ciao ".$_SESSION['user_name']."</a></li>", $navBar);

            $navBar = str_replace("{signupLink}", "", $navBar);

            $navBar = str_replace("{logoutLink}", "<li><a href=\"../php/logout.php\"><span lang=\"en\">Logout</span></a></li>", $navBar);
        }else{
            $navBar = str_replace("{logoutLink}", "", $navBar);
            $navBar = str_replace("{loginLink}", "<li class=\"spostaSign\"><a href=\"../php/login.php\">Accedi</a></li>", $navBar);
            $navBar = str_replace("{signupLink}", "<li class=\"spostaSign\"><a href=\"../php/signup.php\">Registrati</a></li>", $navBar);

            session_destroy();
        }

        $navBar = str_replace("{logo}", "<a href=\"../php/index.php\"><img id=\"logo\" alt=\"logo dell'azienda\" src=\"../assets/logo.svg\" tabindex=\"1\"></a>
            <img id=\"logo_stampa\" alt=\"logo dell'azienda\" src=\"../assets/logo_small.svg\">", $navBar);
        $navBar = str_replace("{homeLink}", "<li><a href=\"index.php\"><span lang=\"en\">Home</span></a></li>", $navBar);
        $navBar = str_replace("{serviziLink}", "<li><a href=\"servizi.php\">Servizi</a></li>", $navBar);
        $navBar = str_replace("{sogniLink}", "<li><a href=\"sogni.php\">Sogni</a></li>", $navBar);
        $navBar = str_replace("{storiaLink}", "<li><a href=\"storia.php\">Storia</a></li>", $navBar);
        $navBar = str_replace("{contattaLink}", "<li><a href=\"contatta.php\">Contattaci</a></li>", $navBar);

        $this->modificaHTML("{navBar}", $navBar);
    }

    public function printErrorPage($errorMsg){
        $this->strutturaHTML = file_get_contents("../html/generalTemplate.html"); //strutturaHTML ora contiene l'HTML di generalTemplate (il template generale)

        $this->strutturaHTML = str_replace("{titoloPagina}", "Errore" , $this->strutturaHTML);                 //Sostituisce segnaposto titolo
        //Aggiungere altre keyword comuni per tutte le pagine ?
        $this->strutturaHTML = str_replace("{metaKeywords}", "Saudade, error" , $this->strutturaHTML);              //Sostituisce segnaposto keywords 
        $this->strutturaHTML = str_replace("{metaDescription}", "Pagina di errore Saudade" , $this->strutturaHTML); //Sostituisce segnaposto description
	    $this->strutturaHTML = str_replace("{testoFooter}", $this->testoFooter, $this->strutturaHTML);              //Sostituzione segnaposto testoFooter
        $this->printNavBar("paginaErrore");
        
        //Sostituzione contenuto principale con il segnaposto main
        $this->strutturaHTML = str_replace("{contenutoMain}", $errorMsg, $this->strutturaHTML);
    }

    public function printPage(){
        echo $this->strutturaHTML;
    }

}
