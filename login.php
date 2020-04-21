<?php
	//sesja - globalny pojemnik na dane
	session_start();

	// jeśli nie ustawiono loginu i hasła - udaj się do index.php
	if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	
	//otwarcie połączenia z bazą danych
	$connection = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($connection->connect_errno!=0)	{
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$login = $_POST['login'];
		$password = $_POST['password'];
		
		//encje html, sanityzacja kodu
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		
		//wysłanie zapytania do bazy
		if ($result = @$connection->query(
		sprintf("SELECT * FROM users WHERE username='%s''", 
		mysqli_real_escape_string($connection,$login)))){
			
			$howManyUsers = $result->num_rows;
			if ($howManyUsers>0){
				// wlozenie danych do tablicy asocjacyjnej
				$row = $result->fetch_assoc();
				
				if (password_verify($password, $row['password'])){
				
					//flaga - zmienna typu bool - ze jestesmy zalogowani
					$_SESSION['logged'] = true;
					
					$_SESSION['id'] = $row['id'];
					$_SESSION['username'] = $row['username'];
					$_SESSION['email'] = $row['email'];
					
					//jesli udało się zalogować - usun z sesji zmienną błąd
					unset($_SESSION['error']);
					//wyczyszczenie rezultatow zapytania np. ->close();
					$result->free_result();
					header('Location: home.php');
				}else{
					//dobry login ale złe hasło
					$_SESSION['error'] = '<span style = "color:#b30000; font-size:17px;">Nieprawidłowy login lub hasło!</span>';
					header('Location: index.php');
				}
				
				
			}else{
				//zły login, obojętnie jakie hasło
				$_SESSION['error'] = '<span style = "color:#b30000; font-size:17px;">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
			}
		}
		$connection->close();
	}
	
?>