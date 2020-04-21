<?php
	session_start();
	
	//zniszczenie całej sesji, zmiennych 
	session_unset();
	
	header('Location: index.php');
	
?>
