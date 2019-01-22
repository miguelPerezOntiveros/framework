<?php
	require_once 'config.inc.php';
	require 'session.inc.php'; 
	
	if($_SESSION['userName'] == 'System Administrator'){
		header('Location: /admin/login.php');
		exit();
	}

	echo '<pre>'.json_encode($config, JSON_PRETTY_PRINT).'<pre>';
?>