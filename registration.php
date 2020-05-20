<?php
	session_start();
	
	//walidacja formularza (dopiero po jego wysłaniu, po submit)
	//jesli nastąpił submit to w tablicy $_POST będą istnieć zmienne
	if (isset($_POST['email'])){ //wybieramy jedna ze zmiennych
		
		//udana walidacja
		$OK = true;
		
		//poprawność login
		$login = $_POST['login']; //pobranie wartosci z formularza
		
		//sprawdzenie długości loginu
		if (strlen($login)<5 || (strlen($login)>20)){
			$OK = false;
			$_SESSION['errorLogin']="Login musi zawierać od 5 do 20 znaków!";
		}
		
		//czy wszystkie znaki są alfanumeryczne
		if (ctype_alnum($login)==false){
			$OK=false;
			$_SESSION['errorLogin']="Login może składać się tylko z liter i cyfr (bez polskich znaków)";		
		}
		
		// poprawność email
		$email = $_POST['email']; //pobranie z formularza do pomocniczej zmiennej
		//przefiltruj zmienną w sposób określony przez rodzaj filtru(drugi parametr funkcji)
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL); //usuwa niedozwolone znaki 
		
		//zwaliduj poprawność email
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB != $email)){
			$OK=false;
			$_SESSION['errorEmail']="Podaj poprawny adres e-mail";
		}
		
		//poprawność hasła
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if (strlen($password1)<8 || (strlen($password1)>20)){
			$OK = false;
			$_SESSION['errorPassword']="Hasło musi zawierać od 8 do 20 znaków!";
		}
		
		if ($password1 != $password2){
			$OK = false;
			$_SESSION['errorPassword']="Podane hasła nie są identyczne!";
		}
		
		$passwordHash = password_hash($password1, PASSWORD_DEFAULT); //stała oznaczającaL użytj najsilniejszego algorytmu hashującego, jaki jst dostępny
		
		//bot or not, secret key
		$secretKey = "6LedZOsUAAAAABLE672PqgG12Vh_ZDCezZt6iapN";
		
		//pobierz zawartosc pliku do zmienej, czy weryfikacja się udała, połączenie się z zewnętrznym serwerem google'a
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
		
		//odp z serwera google'a jest zakodowana w formacie JSON
		$answer = json_decode($check); //zdekodowanie zmiennej check
		
		if ($answer->success==false){
			$OK = false;
			$_SESSION['errorBot']="Potwierdź, że nie jesteś botem";
		}
		
		//formularz zapamiętujący dane
		$_SESSION['frLogin'] = $login; 
		$_SESSION['frEmail'] = $email;
		$_SESSION['frPassword1'] = $password1;
		$_SESSION['frPassword2'] = $password2;
		
		
		//czy login już istnieje
		require_once "connect.php"; //połączenie z bazą danych żeby wyszukać czy taki sam login już istnieje
		mysqli_report(MYSQLI_REPORT_STRICT); //zamiast warning chcemy: exceptions
		
		try{
				$connection = new mysqli($host, $db_user, $db_password, $db_name);
				
				if ($connection->connect_errno!=0)	{
					throw new Exception(mysqli_connect_errno()); //rzuć nowym wyjątkiem
				}else{ //jeśli udało się połączenie 
					//czy istnieje już email
					$result = $connection->query("SELECT id FROM users WHERE email='$email'");
					if (!$result) throw new Exception($connection->error); //jeśli wystąpi błąd
					
					$howManyEmails = $result->num_rows;
					if ($howManyEmails >0){
							$OK = false;
							$_SESSION['errorEmail']="Istnieje już konto przypisane do tego adresu e-mail!";
					}
					
					//czy istnieje już nick
					$result = $connection->query("SELECT id FROM users WHERE username='$login'");
					if (!$result) throw new Exceptrion($result->error);
					
					$howManyLogins = $result->num_rows;
					if ($howManyLogins>0){
							$OK = false;
							$_SESSION['errorLogin']="Istnieje już użytkownik o takim loginie! Wybierz inny.";
					}
					
					if ($OK==true){
						//wszystkie testy ok, dodanie do bazy
						if ( $connection->query("INSERT INTO users VALUES (NULL, '$login' ,'$passwordHash','$email')")){
							$_SESSION['registrationOK']=true;
							header('Location: hello.php'); 
						}else{
							throw new Exception($connection->error);
						}
					}
					// konieczne zamknięcie połączenia
					$connection->close();
				}
		}			
		catch(Exception $e){ //klasa o nazwie exception
			echo '<span style = "color:#b30000; font-size:17px;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!" </span>';
			//echo '<br /> Informacja developerska: '.$e;			
		}

	}
	

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Rejestracja - budżet osobisty</title>
	<meta name="description" content="Rejestracja - Aplikacja do prowadzenbia budżetu osobistego." />
	<meta name="keywords" content="budżet osobisty, finanse, przychody, wydatki, kontrola finansów, rejestracja, oszczędzanie" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="css/fontello.css" rel="stylesheet" type="text/css" />
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	


</head>

<body>
	<div class="content">
	<header>
		<h1 class="h3">Budżet osobisty pod kontrolą <i class="icon-dollar"></i></h1>
	</header>	

	<main>
		<div class="container">
			<div class="row text-center">
				<div class="col-12 background">
					<p> Zapanuj nad swoimi finansami już dziś! Skorzystaj z aplikacji do zarządzania budżetem osobistym w prosty i wygodny sposób. </p>
					<div class="ikon">
						<i class="icon-wallet"></i>
					</div>
					<p> <strong> Zaloguj się </strong> lub <strong> zarejestruj</strong>, jeśli nie masz jeszcze konta. </p>
				</div>
			</div>
		
			<section>
				<div class="row text-center">
					<div class ="col-6 col-md-4 offset-md-2 col-lg-3 offset-lg-3 background p-0">
					
						<div class="user" style="background-color: #e6e6e6; cursor:default;">
							<a href=# class="link" >
								<h2 class="h4" style="color: #006666; cursor:default;" > Rejestracja</h2>
							</a>
						</div>
					</div>
					<div class ="col-6 col-md-4 col-lg-3 background p-0">
						<div class="user two">
							<a href=" index.php" class="link">
								<h2 class="h4">Logowanie</h2>
							</a>
						</div>					
					</div>
				</div>
							
				<div class ="row">
					<div class ="col-md-8 offset-md-2 col-lg-6 offset-lg-3 background b mt-4">
						<form method="post">
							<div class="row">
								<div class="col-4 column1 form-group ml-2"> <label for="inputEmail">Login:</label></div>
								<div class="col-7"> <input type="text"  value="<?php
									if (isset($_SESSION['frLogin'])){
										echo $_SESSION['frLogin'];
										unset($_SESSION['frLogin']); //usunięcie zmiennej sesyjnej
									}
									?>" class="form-control" name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'" ></div>
								
								<div class="col-10 offset-1">
								<?php
									if (isset($_SESSION['errorLogin'])){
										echo '<div class="error">'.$_SESSION['errorLogin'].'</div>';
										unset($_SESSION['errorLogin']);
										
									}
								?></div>

								<div class="col-4 column1 form-group ml-2"><label for="inputName">E-mail:</label></div>
								<div class="col-7"> <input type="email" value="<?php
									if (isset($_SESSION['frEmail'])){
										echo $_SESSION['frEmail'];
										unset($_SESSION['frEmail']);
									}
									?>" class="form-control" name="email" placeholder="e-mail" onfocus="this.placeholder=''" onblur="this.placeholder='e-mail'" > </div>
								
								<div class="col-10 offset-1">
								<?php
									if (isset($_SESSION['errorEmail'])){
										echo '<div class="error">'.$_SESSION['errorEmail'].'</div>';
										unset($_SESSION['errorEmail']);
										
									}
								?></div>
							
								<div class="col-4 column1 form-group ml-2"><label for="inputPassword">Hasło:</label></div>
								<div class="col-7"> <input type="password" value="<?php
									if (isset($_SESSION['frPassword1'])){
										echo $_SESSION['frPassword1'];
										unset($_SESSION['frPassword1']);
									}
									?>" class="form-control"  name="password1" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" > </div>
							
								<div class="col-10 offset-1">
								<?php
									if (isset($_SESSION['errorPassword'])){
										echo '<div class="error">'.$_SESSION['errorPassword'].'</div>';
										unset($_SESSION['errorPassword']);
										
									}
								?></div>
							
								<div class="col-4 column1 form-group ml-2"><label for="inputPassword">Powtórz hasło:</label></div>
								<div class="col-7"> <input type="password" value="<?php
									if (isset($_SESSION['frPassword2'])){
										echo $_SESSION['frPassword2'];
										unset($_SESSION['frPassword2']);
									}
									?>" class="form-control"  name="password2" placeholder="powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" > </div>
								
								<div class="col-8 offset-2 mt-4">
								<div class="g-recaptcha" data-sitekey="6LedZOsUAAAAAMRPTQcVAsEmGQO8DBrj18pi7k4P"></div>
								</div>
								
								<div class="col-10 offset-1">
								<?php
									if (isset($_SESSION['errorBot'])){
										echo '<div class="error">'.$_SESSION['errorBot'].'</div>';
										unset($_SESSION['errorBot']);
										
									}
								?></div>
						
						
								<div class="col-6 offset-3 mt-4 p-2">
								<button type="submit" class="btn btn-lg" > Zarejestruj się</button></div>
								
							</div>
						</form>
					</div>
				</div>
			</section>
		</div>
	</main>
	</div>
	<footer class="footer">
		Budżet osobity 2020 &copy; Wszelkie prawa zastrzeżone
	</footer>

	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/scroll.js" ></script>
	<script src="js/bootstrap.min.js"></script>

</body>
</html>