<?php 
	require_once("newPage.php");
	require_once "functions.php"; 
	use functions\functions;

	$functions = new functions();
	$error = "";
	
	$paginaObj = new newPage("../html/login.html", 
								"Login", 
								"Login - accesso", 
								"Pagina di login");

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		//something was posted
		//$user_name = $dbFunctions->pulisciInput($_POST['user_name']);
		//$password = $dbFunctions->pulisciInput($_POST['password']);

        $user_name = ($_POST['user_name']);
        $password = ($_POST['password']);

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){
			$result = $functions->executeQuery("SELECT * FROM utenti WHERE user_name = '$user_name'");

			if($result){
				if(mysqli_num_rows($result) > 0){

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password){
                        session_start();
						$_SESSION['user_name'] = $user_data['user_name'];

						//header("Location: dashboard.php");
                        header("Location: index.php");
						exit;
					}
				}else{
					$error = "Si è verificato un'errore, riprovare la procedura di login";
				}
			}
			$error = "Nome utente o password errati!";
		}else{
			$error = "Nome utente o password mancanti!";
		}
	}

$paginaObj->modificaHTML("{Error}", $error);
$paginaObj->printPage();