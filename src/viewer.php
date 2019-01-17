<?php
	require_once 'config.inc.php';
	require 'session.inc.php'; 
	
	if($_SESSION['userName'] == 'System Administrator'){
		header('Location: /admin/login.php');
		exit();
	}

	echo json_encode($config);
?>