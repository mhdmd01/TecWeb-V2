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
	//$user_name = $dbFunctions->pulisciInput($_POST['user_name']);
	//$password = $dbFunctions->pulisciInput($_POST['password']);

    $user_name = ($_POST['user_name']);
    $password = ($_POST['password']);

	if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){
		$result = $functions->executeQuery("SELECT * FROM utenti WHERE user_name='$user_name'");

		if($result){
			if(mysqli_num_rows($result) == 0){
				$functions->executeQuery("INSERT INTO utenti (user_name, password) VALUES ('$user_name', '$password')");

				header("Location: login.php");
				exit;
			
			}else{
				$error = "Username già esistente, sceglierne un altro";
			}
		}
        
	}else{
		$error = "Inserire dati validi";
	}
}

$paginaObj->modificaHTML("{Error}", $error);
$paginaObj->printPage();