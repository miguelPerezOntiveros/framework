<?php
	session_name('andrea');
	session_start();
	if(!isset($_SESSION['userName']) && basename($_SERVER['PHP_SELF']) != 'logIn.php'){
		header('Location: logIn.php');
		exit();
	}
?>