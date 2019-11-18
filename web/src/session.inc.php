<?php
	session_name($config['_projectName']);
	session_start();
	error_log('session.inc.php - projectName: '.$config['_projectName'].' - '.$_SERVER['REQUEST_URI'].' userName:'.$_SESSION['userName']);
	if( !(isset($_SESSION['userName']) && isset($_SESSION['type'])) && basename($_SERVER['PHP_SELF']) != 'login.php'){
		if(!isset($no_header))
			header('Location: login.php?sidebar='.$_GET['sidebar']);
		exit();
	}
?>