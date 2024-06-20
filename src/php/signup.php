<?php 
require_once("newPage.php");
require_once "functions.php"; 
use functions\functions;

$functions = new functions();
$error = "";

$paginaObj = new newPage("../html/signup.html", 
                            "Registrazione", 
                            "Pagina di registrazione", 
                            "Pagina di registrazione");


if($_SERVER['REQUEST_METHOD'] == "POST"){

    $user_name = $functions->pulisciInput($_POST['signupUsername']);
    $password = $functions->pulisciInput($_POST['signupPassword']);

	if(strlen($user_name) < 4)
		$error .= "<span lang=\"en\">Username</span> troppo corto. ";
	else if(strlen($user_name) > 15)
		$error .= "<span lang=\"en\">Username</span> troppo lungo. ";

	if(strlen($password) < 4)
		$error .= "<span lang=\"en\">Password</span> troppo corta. ";
	else if(strlen($password) > 15)
		$error .= "<span lang=\"en\">Password</span> troppo lunga. ";
	
//NON FUNZIONA CON L'APOSTROFO
	// Verifica se la password contiene solo caratteri alfanumerici, trattini bassi (_) o trattini (-)
	/*if (!preg_match('/^[a-zA-Z0-9\-_@#$%^&*!\' ]+$/', $password))
		$error .= "Usare per la <span lang='en'>password</span> solo i caratteri indicati (caratteri speciali consentiti: -_@#$%^&*!')";

	// Verifica se lo username contiene solo caratteri alfanumerici, trattini bassi (_) o trattini (-)
	if (!preg_match('/^[a-zA-Z0-9\-_@#$%^&*!\' ]+$/', $user_name))
		$error .= "Usare per lo <span lang='en'>username</span> solo i caratteri indicati (caratteri speciali consentiti: -_@#$%^&*!')";		
	*/

	if($error == "" && !empty($user_name) && !empty($password) && !is_numeric($user_name)){
		$functions->openDBConnection();
		$stmt = $functions->getConnection()->prepare("SELECT * FROM utenti WHERE user_name=?");
		$stmt->bind_param("s", $user_name);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if($result->num_rows == 0){
			$stmt = $functions->getConnection()->prepare("INSERT INTO utenti (user_name, password) VALUES (?, ?)");
			$stmt->bind_param("ss", $user_name, $password);
			$stmt->execute();
			$stmt->close();
			$functions->closeConnection();

			header("Location: login.php");
			exit;
			
		}else
			$error = "<span lang=\"en\">Username</span> già esistente, sceglierne un altro";
	}else{
		if(empty($user_name))
			$error = "Campo <span lang='en'>username</span> vuoto, inserire dati";
		else if(is_numeric($user_name))
			$error .= "Il campo <span lang='en'>username</span> non può contentere solo numeri";

		if(empty($password))
			$error .= "Campo <span lang='en'>password</span> vuoto, inserire dati";
	}

	if($error != "")
		$error = "Problemi: " . $error;
}

$paginaObj->modificaHTML("{Error}", $error);
$paginaObj->printPage();
