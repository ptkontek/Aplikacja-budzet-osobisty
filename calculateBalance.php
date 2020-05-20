<?php
	session_start();
	
	if(!isset($_SESSION['logged'])){
		header ('Location: index.php');
		exit();
	}
	
	if(isset($_SESSION['timeInterval'])){
		
		$OK = true;
		$timeInterval=$_SESSION['timeInterval'];
		$currentDate = date('Y-m-d');
		
		if($timeInterval == "currentMonth"){
			
			$startDate = date('Y-m-d',strtotime("first day of this month"));
			$endDate = date('Y-m-d');
		}
		else if($timeInterval == "previousMonth"){
			
			$startDate = date('Y-m-d',strtotime("first day of previous month"));
			$endDate = date('Y-m-d',strtotime("last day of previous month"));
		}
		else if($timeInterval == "periodOfTime"){
			
			$startDate = $_SESSION['startDateInterval'];
			$endDate = $_SESSION['endDateInterval'];	
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

<body onload="pieChart()">
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
					<div class="col-12 col-lg-10 offset-lg-1 background b mt-5">

						<?php
							require_once "connect.php";
							mysqli_report(MYSQLI_REPORT_STRICT);
							
							try{
								$connection = new mysqli($host, $db_user, $db_password, $db_name);

								if ($connection->connect_errno!=0){
									throw new Exception(mysqli_connect_errno());
								}else{
									$userId = $_SESSION['id'];
									
									$result=$connection->query("SELECT ic.name, SUM(i.amount) FROM users INNER JOIN incomes i ON users.id = i.user_id INNER JOIN incomes_category_assigned_to_users ic ON i.income_category_assigned_to_user_id = ic.id  WHERE users.id = $userId AND i.date_of_income >= '$startDate' AND  i.date_of_income <= '$endDate' GROUP BY ic.id");
									if(!$result) throw new Exception($connection->error);
									
									echo '<h4 class="h4 font-weight-bold mt-2 mb-4"> Bilans finansów: '.'</br>'.$startDate.' - '.$endDate.'</h4>';
									$howManyCategory=$result->num_rows;
									if($howManyCategory>0){
									
									echo '<div class="row text-center">';		
									echo '<div class="col-sm-10 offset-sm-1 col-md-6 offset-md-0 tab mt-4">';  
										echo '<h5 class="h5 font-weight-bold mb-3"> Wykaz przychodów: </h5>';													
											echo '<table class="table table-sm">';         
												echo '<thead>'; 
													echo ' <tr>'; 
													echo '<th scope="col">Nazwa kategorii '; 
													echo '<th scope="col">Suma przychodów'; 
												echo '</thead>'; 
												echo '<tbody>'; 

												while ($row = $result->fetch_assoc()){
													
													echo '<tr>'; 
													echo '<th scope="row">'.$row['name']; 
													echo '<td>'.$row['SUM(i.amount)']; 
												} 
												$result->free_result();
												
												$result=$connection->query("SELECT SUM(i.amount) FROM users INNER JOIN incomes i ON users.id = i.user_id WHERE users.id = $userId AND i.date_of_income >= '$startDate' AND  i.date_of_income <= '$endDate'");
													if(!$result) throw new Exception($connection->error);
									
													$howManyResults=$result->num_rows;
													if($howManyResults>0){
																
														while ($row = $result->fetch_assoc()){
																
															echo '<tr style="background-color: grey;">';
															echo '<th scope="row">ŁĄCZNIE';
															echo '<th>'.$row['SUM(i.amount)'];
															$sumIncomes = $row['SUM(i.amount)'];
															
														}$result->free_result();		
													}		
												echo '</tbody>'; 
											echo '</table>'; 
										echo '</div>'; 		 
									}else{
										echo '<h5>Brak przychodów w okresie od '.$startDate.' do '.$endDate.'</h5>';
										$sumIncomes=0;
									}
									
									$result=$connection->query("SELECT ec.name, SUM(e.amount) FROM users INNER JOIN expenses e ON users.id = e.user_id INNER JOIN expenses_category_assigned_to_users ec ON e.expense_category_assigned_to_user_id = ec.id WHERE users.id = $userId AND e.date_of_expense >= '$startDate' AND  e.date_of_expense <= '$endDate' GROUP BY ec.id");
									if(!$result) throw new Exception($connection->error);
									
									$howManyCategory=$result->num_rows;
									if($howManyCategory>0){
												
									echo '<div class="col-sm-10 offset-sm-1 col-md-6 offset-md-0 tab mt-4">'; 
										echo '<h5 class="h5 font-weight-bold mb-3"> Wykaz wydatków </h5>';		
											echo '<div class="tab">';
												echo '<table class="table table-sm">';         
													echo '<thead>'; 
														echo ' <tr>'; 
														echo '<th scope="col">Nazwa kategorii '; 
														echo '<th scope="col">Suma wydatków'; 
													echo '</thead>'; 
													echo '<tbody>'; 
													$i=0;
													while ($row = $result->fetch_assoc()){
														
														echo '<tr>'; 
														echo '<th scope="row">'.$row['name']; 
														echo '<td>'.$row['SUM(e.amount)'];  
														$dataPoints[$i]["name"]= $row['name'];
														$dataPoints[$i]["y"]= $row['SUM(e.amount)']; 
														$i=$i+1;
													} 
													$result->free_result();
													
													$result=$connection->query("SELECT SUM(e.amount) FROM users INNER JOIN expenses e ON users.id = e.user_id WHERE users.id = $userId AND e.date_of_expense >= '$startDate' AND  e.date_of_expense <= '$endDate'");
													if(!$result) throw new Exception($connection->error);
									
													$howManyResults=$result->num_rows;
													if($howManyResults>0){
																
														while ($row = $result->fetch_assoc()){
																
															echo '<tr style="background-color: grey;">';
															echo '<th scope="row">ŁĄCZNIE';
															echo '<th>'.$row['SUM(e.amount)'];
															$sumExpenses = $row['SUM(e.amount)'];
															
														}$result->free_result();		
													}		
													echo '</tbody>'; 
												echo '</table>'; 
											echo '</div>'; 		
										echo '</div>'; 
									}else{
										echo '<h5>Brak wydatków w okresie od '.$startDate.' do '.$endDate.'</h5>';
										$sumExpenses=0;
									}	
									
									$difference = $sumIncomes - $sumExpenses;
									echo '<div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-7 offset-lg-3 outcome mt-4">';
									echo '<span style="float:left; padding:5px;">BILANS FINANSÓW WYNOSI</span>';
									echo '<div class="score">'.number_format($difference,2,'.', '').' zł'.'</div>';
									
									if($difference>0)
										echo '</br></br><strong>Gratulacje, świetnie zarządzasz finansami!</strong>';
									else if($difference<0)
										echo '</br></br><strong>Uważaj na długi! Popracuj nad oszczędzaniem!</strong>';
									echo '</div>';
		
								}$connection->close();
							}
							catch(Exception $e){
								echo '<span style = "color:#b30000; font-size:17px;">Błąd serwera! Przepraszamy za niedogodności i prosimy o skorzystanie z aplikacji w innym terminie!" </span>';
								//echo '<br />Informacja developerska: '.$e;
							}		
						?>							
						<div id="chartContainer">
							<script>
								function pieChart () 
								{
									var chart = new CanvasJS.Chart("chartContainer", {
												exportEnabled: true,
												animationEnabled: true,
												theme: "light2",
												title:{
													text: "Wykres przedstawiający Twoje wydatki",
													fontColor: "#ffc34d",
													fontSize: 20,
												},
												data: [{
													type: "pie",
													radius: 140,
													startAngle: 270,
													indexLabelFontSize: 15,
													yValueFormatString: "##0.00\" zł\"",
													toolTipContent: "{name}: <strong>{y}</strong>",
													indexLabel: "{name} (#percent%)",
													dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
												}]
											});
									chart.render();
							}
							</script>	
						</div>
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