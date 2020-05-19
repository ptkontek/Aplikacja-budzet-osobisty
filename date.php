<?php

	session_start();
	
	if(!isset($_SESSION['logged'])){
		header ('Location: index.php');
		exit();
	}
	
	if(isset($_POST['timeInterval'])){
		$OK= true;
		$timeInterval = $_POST['timeInterval'];
		$_SESSION['timeInterval'] = $timeInterval;
	}
	
	if(isset($_POST['startDate'])) {
		
		$startDate = $_POST['startDate'];
		$startDate = htmlentities($startDate,ENT_QUOTES, "UTF-8");
		$endDate = $_POST['endDate'];
		$endDate = htmlentities($endDate,ENT_QUOTES, "UTF-8");
		$OK = true;
		
		if($startDate == NULL){
			$OK = false;
			$_SESSION['errorStartDate'] = "Wybierz datę startową!";
		}
				
		if($endDate == NULL){
			$OK = false;
			$_SESSION['errorEndDate'] = "Wybierz datę końcową!";
		}				
			
		$currentDate = date('Y-m-d');
		
		if($startDate > $currentDate){
			$OK = false;
			$_SESSION['errorStartDate'] = "Wybierz dobrą datę początkową! Nie może być późniejsza od aktualnej daty.";
		}
					
		if($endDate > $currentDate){
			$OK = false;
			$_SESSION['errorEndDate'] =  "Wybierz dobrą datę końcową! Nie może być późniejsza od aktualnej daty.";
		}
			
		if($endDate!=NULL && $startDate!=NULL){
			if($endDate < $startDate){
				$OK = false;
				$_SESSION['errorStartDate'] = "Data początkowa musi być wcześniejsza niż data końca okresu!";
			}
		}
		$_SESSION['startDate'] = $startDate;
		$_SESSION['endDate'] = $endDate;	
		
		$_SESSION['startDateInterval'] = $startDate;
		$_SESSION['endDateInterval'] = $endDate;	
	
		if (($OK==true) && (isset($_SESSION['startDateInterval'])) && (isset($_SESSION['endDateInterval']))){
			header ('Location: calculateBalance.php');
		}
	}
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
					<li class="nav-item active"><a class="nav-link"  href="balance.php">Przeglądaj bilans</a></li>
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
					<form method="post">
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
			<?php
			if(isset($_SESSION['timeInterval']) && $_SESSION['timeInterval'] == "periodOfTime"){
				
				echo '<form method = "POST">';
					echo '<div class="row">';
						echo '<div class ="col-10 col-sm-7 col-md-5 offset-1 offset-sm-3  offset-md-3 form-group">';
							echo '<label for="startDate" class="row2">Początek okresu:</label></br>';
						
							echo '<input type="date" name="startDate" value="';
							if (isset($_SESSION['startDate'])){
								echo $_SESSION['startDate'];
								unset($_SESSION['startDate']);
							}
							echo '"class="form-control">';
										
								if (isset($_SESSION['errorStartDate'])){
									echo '<div class="error">'.$_SESSION['errorStartDate'].'</div>';
									unset($_SESSION['errorStartDate']);
								}
						echo '</div>';

						echo '<div class ="col-10 col-sm-7 col-md-5 offset-1 offset-sm-3 form-group">';
							echo '<label for="endDate" class="row2">Koniec okresu:</label></br>';
							
								echo '<input type="date" name="endDate" value="';
								if (isset($_SESSION['endDate'])){
									echo $_SESSION['endDate'];
									unset($_SESSION['endDate']);
								}
								echo '" class="form-control">';
								if (isset($_SESSION['errorEndDate'])){
									echo '<div class="error">'.$_SESSION['errorEndDate'].'</div>';
									unset($_SESSION['errorEndDate']);
								}
						echo '</div>';
						
						echo '<div class="col-4 col-md-3 offset-sm-3 offset-3">';
						echo '<input type="submit" value="BILANS"></div>';
					echo'</div>';
				echo '</form>';

			} else if ($_SESSION['timeInterval'] == "currentMonth" || $_SESSION['timeInterval'] == "previousMonth"){ 
				header ('Location: calculateBalance.php');
			}
			?>
			<article>
				<div class="row">
					<div class="col-12 col-lg-10 offset-lg-1  background mt-5">
						<h4 class="h4 font-weight-bold mt-2 mb-5"> Bilans finansów: </h4>
						<i class="icon-wallet"></i> <br><br>
			
					</div>
				</div>
			</article>
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