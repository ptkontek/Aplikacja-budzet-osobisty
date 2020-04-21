<?php

	session_start();
	
	if (!isset($_SESSION['logged'])){
		
		header('Location: index.php');
		exit();
	}
?>
	
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title> Strona główna - budżet osobisty</title>
	<meta name="description" content="Strona główna - Aplikacja do prowadzenbia budżetu osobistego." />
	<meta name="keywords" content="budżet osobisty, finanse, przychody, wydatki, kontrola finansów, przychody, oszczędzanie" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="css/fontello.css" rel="stylesheet" type="text/css" />

</head>

<body>

	<header>

		<h1 class="h3">Budżet osobisty pod kontrolą <i class="icon-dollar"></i></h1>

		<nav class="navbar navbar-expand-md p-1">
		
			<button class="navbar-toggler ml-3" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Nawigation switch">
			
				<span class="navbar-toggler-icon"></span>
				<span class="navbar-toggler-icon"></span>
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="mainmenu">
		
				<ul class="navbar-nav mx-auto">
				
					<li class="nav-item active"><a class="nav-link"> Strona główna</a></li>
					<li class="nav-item"><a class="nav-link" href="przychody.html">Dodaj przychód</a></li>
					<li class="nav-item"><a class="nav-link" href="wydatki.html">Dodaj wydatek</a></li>
					<li class="nav-item"><a class="nav-link" href="bilans.html">Przeglądaj bilans</a></li>
					<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true">Ustawienia</a>
					
						<div class="dropdown-menu" aria-labelledby="submenu">
							<a class="dropdown-item" href="zmianDane.html">Zmień dane</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="zmienKategorie.html">Zmień kategorie</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="usunWpis.html">Usuń ostatnie wpisy</a>
						</div>
						
					</li>
					<li class="nav-item"><a class="nav-link" href="logout.php">Wyloguj się</a></li>
				</ul>
			</div>
		</nav>
	</header>	

	<main>
		<article>

			<div class="container">
				<div class="email">
					<?php
						echo $_SESSION['email'];
					?>
				</div>
				<div class="row text-center">
					<div class="col-12 opis content background">
						<h2 class="h4 mt-2"> Budżet osobisty </h2>

						<p> Witaj <strong>
						<?php
							echo $_SESSION['username'];
						?>
						</strong> w aplikacji, która pozwoli Ci w łatwy i przyjemny sposób <strong> zapanować nad Twoim budżetem osobistym</strong>. Dzięki kontroli swoich finansów dowiesz się na co przeznaczasz najwięcej pieniędzy, na czym możesz zaoszczędzić oraz ile odłożyć na przyjemności i rajskie wakacje! <i class="icon-sun"></i> </p> 
						<i class="icon-wallet"></i> <br><br>
							
						<h2 class="h4 mt-1"> Obsługa aplikacji </h2>
						<p> Wydatki oraz przychody będą zapisywane w poszczególnych <strong>kategoriach</strong>, dzięki czemu na koniec miesiąca dokładnie się dowiesz, ile pieniędzy wydajesz na mieszkanie, jedzenie, przyjemności i głupoty. Możesz również edytować i usuwać kategorie, aby dostosować aplikację do swoich potrzeb. <strong>Przekonaj się już dziś jakie to proste!</strong></p>
					</div>
				</div>
			</div>
		</article>	
	</main>
	<footer class="footer">
			Budżet osobity 2020 &copy; Wszelkie prawa zastrzeżone
	</footer>	
		
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>

</body>
</html>