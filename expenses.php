<?php

	session_start();
	
	if (!isset($_SESSION['logged'])){
		
		header('Location: index.php');
		exit();
	}
	
	if(isset($_POST['amount'])){
		
		//Udana walidacja
		$OK = true;
		
		//poprawność kwoty
		$amount = $_POST['amount'];
		$amount = htmlentities($amount,ENT_QUOTES, "UTF-8");
		
		
		if($amount == NULL){
			
			$OK= false;
			$_SESSION['errorAmount'] = "Wpisz kwotę!";
		}else{
			$amount = str_replace(",",".",$amount);
			
			if(is_numeric($amount)){
				$amount = round($amount,2);
			}else{
				$OK = false;
				$_SESSION['errorAmount']="Podana kwota musi być w formacie liczbowym!";
			}
		}
		
		//poprawność daty
		$date = $_POST['date'];
		$date = htmlentities($date,ENT_QUOTES, "UTF-8");
		
		if($date == NULL){
			$OK= false;
			$_SESSION['errorDate'] = "Wybierz datę!";
		}
		$currentDate = date('Y-m-d');
		
		if($date > $currentDate){
			$OK = false;
			$_SESSION['errorDate'] = "Wybierz datę aktualną lub wcześniejszą!";	
		}

		if(isset($_POST['expenseCategory'])) {
			
			$category = $_POST['expenseCategory'];
			$_SESSION['frCategory'] = $category;
		}
		else{
			$OK = false;
			$_SESSION['errorCategory'] = "Wybierz kategorię wydatku!";
		}
		
		if(isset($_POST['paymentMethod'])) {
			
			$paymentMethod = $_POST['paymentMethod'];
			$_SESSION['frPaymentMethod'] = $paymentMethod;

		}else{
			$OK = false;
			$_SESSION['errorPayment'] = "Wybierz sposób płatności!";
		}
		
		//poprawność komentarza
		$comment = $_POST['comment'];
		$comment = htmlentities($comment,ENT_QUOTES, "UTF-8");
		
		if((strlen($comment) > 100))
		{
			$OK = false;
			$_SESSION['errorComment'] = "Komentarz może mieć maksymalnie 100 znaków.";
		}
		
		$_SESSION['frAmount'] = $amount;
		$_SESSION['frDate'] = $date;
		$_SESSION['frComment'] = $comment;
		
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try{
			
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			$connection->set_charset("utf8");
			
			if ($connection->connect_errno!=0){
				
				throw new Exception(mysqli_connect_errno());
			}else{
				
				$userId = $_SESSION['id'];

				if ($OK==true){
					
					if ($connection->query("INSERT INTO expenses VALUES (NULL, '$userId',(SELECT id FROM expenses_category_assigned_to_users WHERE user_id ='$userId' AND name ='$category'),(SELECT id FROM payment_methods_assigned_to_users WHERE user_id ='$userId' AND name='$paymentMethod'),'$amount','$date','$comment')"))	{
						$_SESSION['addExpenseOK'] = true;
					    header('Location: expenseAdded.php');
					}else{
						
						throw new Exception($connection->error);
					}
				}
				$connection->close();
			}
		}
		catch(Exception $e)
		{
			echo '<span style = "color:#b30000; font-size:17px;">Błąd serwera! Przepraszamy za niedogodności i prosimy o skorzystanie z aplikacji w innym terminie!" </span>';
			//echo '<br />Informacja developerska: '.$e;
		}
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Dodaj wydatek- budżet osobisty</title>
	<meta name="description" content="Dodaj wydatek - Aplikacja do prowadzenbia budżetu osobistego." />
	<meta name="keywords" content="budżet osobisty, finanse, przychody, wydatki, kontrola finansów, przychody, oszczędzanie" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="css/fontello.css" rel="stylesheet" type="text/css" />


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
					<li class="nav-item"><a class="nav-link"  href="incomes.php">Dodaj przychód</a></li>
					<li class="nav-item active"><a class="nav-link">Dodaj wydatek</a></li>
					<li class="nav-item"><a class="nav-link" href="balance.php">Przeglądaj bilans</a></li>
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
		<section>
			<div class="container">
			
				<form method = "post">
					<div class ="row justify-content-center">
						<div class="col-12 background form">
							<h4 class="h4 font-weight-bold mt-2 mb-4">Dodaj wydatek</h4>
						
							<div class ="row">
								<div class ="col-2 col-md-3 mt-2 ml-5 form-group"> <label for="inputAmount" class="row2" > Kwota: </label></div>
								<div class="col-5 col-sm-4 ml-auto"><input type="text" value="<?php
								if (isset($_SESSION['frAmount'])){
									echo $_SESSION['frAmount'];
									unset($_SESSION['frAmount']);
								}
								?>"class="form-control" id="inputAmount" name="amount">
								
								<?php
									if (isset($_SESSION['errorAmount'])){
										echo '<div class="error">'.$_SESSION['errorAmount'].'</div>';
										unset($_SESSION['errorAmount']);	
									}
								?></div>
								
								<div class="col-3 col-sm-4"></div>

								<div class ="col-2 col-md-3 mt-2 ml-5 form-group"><label for="inputDate" class="row2"> Data: </label></div>
								<div class="col-5 col-sm-4 ml-auto"><input type="date" value="<?php
								if (isset($_SESSION['frDate'])){
									echo $_SESSION['frDate'];
									unset($_SESSION['frDate']);
								}
								?>"class="form-control" id="inputDate" name="date">
								<?php
									if (isset($_SESSION['errorDate'])){
										echo '<div class="error">'.$_SESSION['errorDate'].'</div>';
										unset($_SESSION['errorDate']);	
									}
								?></div>
								<div class="col-3 col-sm-4"></div>
							</div>

							<div class ="row mt-3">					
								<div class ="col-8 offset-4 col-sm-7 offset-sm-5 col-md-5 offset-1 expenses">
									<fieldset>
										<legend> Sposób płatności </legend>
										<?php
											require_once "connect.php";
											mysqli_report(MYSQLI_REPORT_STRICT);
												
											try{
												$connection = new mysqli($host, $db_user, $db_password, $db_name);
												
												if ($connection->connect_errno!=0){
													throw new Exception(mysqli_connect_errno());
												}else{
													$userId = $_SESSION['id'];
											
													$result=$connection->query("SELECT name FROM payment_methods_assigned_to_users WHERE user_id ='$userId'");
													if(!$result) throw new Exception($connection->error);
														
													$howManyNames=$result->num_rows;
													if($howManyNames>0){
														
														while ($row = $result->fetch_assoc())	{
															echo '<div class="row ">';
															echo '<label>'.'<input type="radio" name="paymentMethod" value="'.$row['name'];

															if(isset($_SESSION['frPaymentMethod'])){
																if($row['name'] == $_SESSION['frPaymentMethod']) {
																	echo '"checked ="checked"';
																}
															}
															echo '">'.' '.$row['name'].'</label>';
															echo '</div>';
														}
														$result->free_result();
													}
												}
												$connection->close();
											}
											catch(Exception $e){
												echo '<span style = "color:#b30000; font-size:17px;">Błąd serwera! Przepraszamy za niedogodności i prosimy o skorzystanie z aplikacji w innym terminie!" </span>';
													//echo '<br />Informacja developerska: '.$e;
											}
										?>	
										<?php
											if (isset($_SESSION['errorPayment'])){
												echo '<div class="error">'.$_SESSION['errorPayment'].'</div>';
												unset($_SESSION['errorPayment']);
											}
										?>			
									</fieldset>						
								</div>
								
								<div class ="col-8 offset-4 col-sm-7 offset-sm-5 col-md-5 expenses">
									<fieldset>
										<legend> Kategoria wydatku </legend>							
										<?php
											require_once "connect.php";
											mysqli_report(MYSQLI_REPORT_STRICT);
												
											try{
												$connection = new mysqli($host, $db_user, $db_password, $db_name);

												if ($connection->connect_errno!=0){
													throw new Exception(mysqli_connect_errno());
												}else{
													$userId = $_SESSION['id'];
												
													$result=$connection->query("SELECT name FROM expenses_category_assigned_to_users WHERE user_id ='$userId'");
													
													if(!$result) throw new Exception($connection->error);
														
													$howManyNames=$result->num_rows;

													if($howManyNames>0){
														while ($row = $result->fetch_assoc())
														{
															echo '<div class="row ">';
															echo '<label>'.'<input type="radio" name="expenseCategory" value="'.$row['name'];
															
															if(isset($_SESSION['frCategory'])){
																if($row['name'] == $_SESSION['frCategory']) {
																	echo '"checked ="checked"';
																}
															}
															
															echo '">'.' '.$row['name'].'</label>';
															echo '</div>';
														}
														$result->free_result();
													}
												}
												$connection->close();
											}
											catch(Exception $e)
											{
												echo '<span style = "color:#b30000; font-size:17px;">Błąd serwera! Przepraszamy za niedogodności i prosimy o skorzystanie z aplikacji w innym terminie!" </span>';
													//echo '<br />Informacja developerska: '.$e;
											}
										?>
										<?php
											if (isset($_SESSION['errorCategory'])){
												echo '<div class="error">'.$_SESSION['errorCategory'].'</div>';
												unset($_SESSION['errorCategory']);
											}
										?>			
									</fieldset>
									
								</div>
								<div class ="col-10 offset-1 form-group expenses">
									<div class="comment"><label for="comment"> Komentarz:  [opcjonalnie] </label></div>
									<textarea class="form-control" name="comment" id="comment" rows="4"><?php
									if (isset($_SESSION['frComment'])){
										echo $_SESSION['frComment'];
										unset($_SESSION['frComment']);
									}
									?></textarea>
									<?php
									if (isset($_SESSION['errorComment'])){
										echo '<div class="error">'.$_SESSION['errorComment'].'</div>';
										unset($_SESSION['errorComment']);	
									}
									?>
								</div>	

								<div class="col-4 col-md-3 offset-md-3 offset-2">
									<input type="submit" value="Dodaj"></div> 
								<div class="col-4 col-md-3">	
									<input type="submit" value="Anuluj"></div>
							</div>	
						</div>
					</div>			
				</form>
			</div>
		</section>	
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