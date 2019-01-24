<?php
	session_name($config['_projectName']);
	session_start();
	if( !(isset($_SESSION['userName']) && isset($_SESSION['type'])) && basename($_SERVER['PHP_SELF']) != 'login.php'){
		header('Location: login.php?sidebar='.$_GET['sidebar']);
		exit();
	}
?>