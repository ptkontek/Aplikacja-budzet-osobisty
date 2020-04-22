<?php
	session_start();
	//jesli zalogowany to udaj się od razu do strony głównej
	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true)){
		header('Location: home.php'); //przegladarka robi cały kod, a dopiero potem przechodzi do pliku
		exit(); //konczenie przetwarzania kodu i od razu przechodzimy do strony głównej
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Logowanie - budżet osobisty</title>
	<meta name="description" content="Logowanie - Aplikacja do prowadzenbia budżetu osobistego." />
	<meta name="keywords" content="budżet osobisty, finanse, przychody, wydatki, kontrola finansów, logowanie, oszczędzanie" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="css/fontello.css" rel="stylesheet" type="text/css" />

</head>

<body>

	<header>
		<h1 class="h3">Budżet osobisty pod kontrolą <i class="icon-dollar"></i></h1>
	</header>	

	<main>
		<div class="container">
			<div class="row text-center ">
				<div class="col-12 background">
					<p> Zapanuj nad swoimi finansami już dziś! Skorzystaj z aplikacji do zarządzania budżetem osobistym w prosty i wygodny sposób. </p>
					<div class="ikon">
						<i class="icon-wallet"></i>
					</div>
					<p> <strong> Zaloguj się </strong> lub <strong> zarejestruj</strong>, jeśli nie masz jeszcze konta. </p>
				</div>
			</div>
		
			<section>			
				<div class="row text-center ">
					<div class ="col-6 col-md-4 offset-md-2 col-lg-3 offset-lg-3 background p-0">
						
						<div class="user" style="background-color: #e6e6e6; cursor:default;">
							<a href=# class="link" >
								<h2 class="h4" style="color: #006666; cursor:default;" > Logowanie</h2>
							</a>
						</div>
					</div>
					<div class ="col-6 col-md-4 col-lg-3 background p-0">
								
						<div class="user two">
							<a href="registration.php" class="link">
								<h2 class="h4">Rejestracja</h2>
							</a>
						</div>					
					</div>
				</div>
				
				<div class ="row">
					<div class ="col-md-8 offset-md-2 col-lg-6 offset-lg-3 background mt-4">
					
						<form action="login.php" method="post">
						
							<div class ="row">
								<div class="col-3 column1 form-group ml-2"> <label for="inputLogin">Login:</label></div>
								
								<div class="col-8"> <input type="text" class="form-control" id="inputLogin" name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'" > </div>
							
								<div class="col-3 column1 form-group ml-2"><label for="inputPassword">Hasło:</label></div>
								
								<div class="col-8"> <input type="password" class="form-control" id="inputPassword" name="password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" > </div>
							

								<div class="col-6 offset-3 mt-4 p-2">
								<button type="submit" class="btn btn-lg" > Zaloguj się</button></div>

							</div>			
						</form>
						
						<?php
							if (isset($_SESSION['error'])) //jesli istnieje zmienna error, tylko wtedy pokaz:
								echo $_SESSION['error'];
						?>

					</div>
				</div>
			</section>
		</div>
	</main>
	
	
	<footer class="footer">
		Budżet osobity 2020 &copy; Wszelkie prawa zastrzeżone
	</footer>

	
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/scroll.js" ></script>
	<script src="js/bootstrap.min.js"></script>

</body>
</html>