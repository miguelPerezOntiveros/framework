<?php
	require 'session.inc.php'; 
	
	if($_SESSION['userName'] == 'System Administrator'){
		header('Location: /admin/login.php');
		exit();
	}

	require_once 'config.inc.php';
	echo json_encode($config);

?>