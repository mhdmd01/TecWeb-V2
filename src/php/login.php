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

        $user_name = $functions->pulisciInput($_POST['loginUsername']);
        $password = $functions->pulisciInput($_POST['loginPassword']);

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){
			$functions->openDBConnection();
			$stmt = $functions->getConnection()->prepare("SELECT * FROM utenti WHERE user_name=?");
			$stmt->bind_param("s", $user_name);
			$stmt->execute();
			$result = $stmt->get_result(); // Ottieni il risultato della query
			$stmt->close();
			$functions->closeConnection();

			if($result){
				if(mysqli_num_rows($result) > 0){

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password){
						if (session_status() === PHP_SESSION_NONE) // Se la sessione non è stata ancora avviata, avviala
                			session_start();

						$_SESSION['user_name'] = $user_data['user_name'];

						if($user_data['user_name'] == "admin")
							header("Location: dashboardAdmin.php");
						else
							header("Location: dashboardUser.php");

						exit;
					}
				}else{
					$error = "Si è verificato un'errore, ripetere la procedura di login";
				}
			}
			$error = "Nome utente o password errati!";
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