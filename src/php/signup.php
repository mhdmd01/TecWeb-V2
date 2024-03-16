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

    $user_name = $functions->pulisciInput($_POST['user_name']);
    $password = $functions->pulisciInput($_POST['password']);

	if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){
		$result = $functions->executeQuery("SELECT * FROM utenti WHERE user_name='$user_name'");

		if(is_null($result)){
			$functions->openDBConnection();
			$stmt = $functions->getConnection()->prepare("INSERT INTO utenti (user_name, password) VALUES (?, ?)");
			$stmt->bind_param("ss", $user_name, $password);
			$stmt->execute();
			$stmt->close();
			$functions->closeConnection();

			header("Location: login.php");
			exit;
			
		}else
			$error = "Username già esistente, sceglierne un altro";
	}else{
		if(empty($user_name))
			$error = "<p>Campo 'username' vuoto, inserire dati</p>";
		else if(is_numeric($user_name))
			$error .= "<p>Il campo 'username' non può contentere solo numeri</p>";

		if(empty($password))
			$error .= "<p>Campo 'password' vuoto, inserire dati</p>";
	}

	if($error != "")
		$error = "Problemi: " . $error;
}

$paginaObj->modificaHTML("{Error}", $error);
$paginaObj->printPage();