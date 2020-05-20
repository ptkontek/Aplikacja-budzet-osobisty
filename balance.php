<?php

	session_start();
	
	if(!isset($_SESSION['logged'])){
		header ('Location: index.php');
		exit();
	}
	
	if(isset($_SESSION['startDate']))
		unset($_SESSION['startDate']);
    
	if(isset($_SESSION['endDate']))
		unset($_SESSION['endDate']);
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title> Bilans finansów - budżet osobisty</title>
	<meta name="description" content="Bilans finansów - Aplikacja do prowadzenbia budżetu osobistego." />
	<meta name="keywords" content="budżet osobisty, finanse, przychody, wydatki, kontrola finansów, przychody, oszczędzanie" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="css/fontello.css" rel="stylesheet" type="text/css" />
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

</head>

<body>
	<div class="content">
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
				
					<li class="nav-item"><a class="nav-link" href="home.php"> Strona główna</a></li>
					<li class="nav-item"><a class="nav-link" href="incomes.php">Dodaj przychód</a></li>
					<li class="nav-item"><a class="nav-link" href="expenses.php">Dodaj wydatek</a></li>
					<li class="nav-item active"><a class="nav-link" href="balance.php">Przeglądaj bilans</a></li>
					<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true">Ustawienia</a>
					
						<div class="dropdown-menu" aria-labelledby="submenu">
							<a class="dropdown-item" href="changeData.php">Zmień dane</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="changeCategory.php">Zmień kategorie</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="deleteEntry.php">Usuń ostatnie wpisy</a>
						</div>
					</li>
					<li class="nav-item"><a class="nav-link" href="logout.php">Wyloguj się</a></li>
				</ul>
			</div>
		</nav>
	</header>

	<main>	
		<div class="container">
			<div class="row mt-5">
				<div class="col-10 col-sm-7 offset-sm-3 offset-1">
					<form action="date.php" method="post">
						<div class="form-group">
							<label for "timeInterval" > Wybierz okres czasu: </label></br>
							
								<select class="dropdownMenuButton" id="dropdownMenuButton" name="timeInterval">
									<option  class="dropdown-item" value="currentMonth">Bieżący miesiąc</option>
									<option  class="dropdown-item" value="previousMonth">Poprzedni miesiąc</option>
									<option  class="dropdown-item" value="periodOfTime">Wybrany okres</option>
								</select>
								<button type="submit" class="save">Zapisz</button>
						</div>
					</form>
				</div>	
			</div>	
			<article>
				<div class="row">
					<div class="col-12 col-lg-10 offset-lg-1  background b mt-5">
						<h4 class="h4 font-weight-bold mt-2 mb-5"> Bilans finansów: </h4>
						<i class="icon-wallet"></i> <br><br>
					</div>
				</div>
			</article>			
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