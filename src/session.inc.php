<?php
	session_name($config['_projectName']);
	session_start();
	if(!isset($_SESSION['userName']) && basename($_SERVER['PHP_SELF']) != 'login.php'){
		if(file_exists('login.php'))
			header('Location: login.php');
		exit();
	}
?>