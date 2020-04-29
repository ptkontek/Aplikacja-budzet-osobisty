<?php
	//sesja - globalny pojemnik na dane
	session_start();

	// jeśli nie ustawiono loginu i hasła - udaj się do index.php
	if ((!isset($_POST['login'])) || (!isset($_POST['password']))){
		
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	try{
		
		//otwarcie połączenia z bazą danych
		$connection = new mysqli($host, $db_user, $db_password, $db_name);
		//@- w przypadku błędu php nie będzie pokazywać żadnych informacji
	
		if ($connection->connect_errno!=0)	{ //inna niż 0 = true
		
			throw new Exception(mysqli_connect_errno());
		}
		
		else {
			$login = $_POST['login'];
			$password = $_POST['password'];
			
			//encje html, sanityzacja kodu, ENT_QUOTES - zamienia też cudzysłowia i apostrofy na encje
			$login = htmlentities($login, ENT_QUOTES, "UTF-8");
			
			//wysłanie zapytania do bazy
			if ($result = @$connection->query(
			sprintf("SELECT * FROM users WHERE username='%s'",  //pilnuje typów danych, %s-tu wstawiamy napis string
			mysqli_real_escape_string($connection,$login)))){ //zabezpiecza przed wstrzykiwaniem sql
			
				$howManyUsers = $result->num_rows;
				if ($howManyUsers>0){
					
					// wlozenie danych do tablicy asocjacyjnej
					$row = $result->fetch_assoc();
					
					// jeśli funkcja zwróci true
					if (password_verify($password,$row['password'])){
					
						//flaga - zmienna typu bool - ze jestesmy zalogowani
						$_SESSION['logged'] = true;
						
						$_SESSION['id'] = $row['id'];
						$_SESSION['email'] = $row['email'];
						$_SESSION['username'] = $row['username'];
						
						//jesli udało się zalogować - usun z sesji zmienną błąd
						unset($_SESSION['error']);
						//wyczyszczenie rezultatow zapytania np. ->close(); free();
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
			}else{
				throw new Exception($connection->error);
			}
			$connection->close(); //zamknięcie połączenia
		}
	}
	catch(Exception $e){
		echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
		//echo '<br />Informacja developerska: '.$e;
	}
	
?>