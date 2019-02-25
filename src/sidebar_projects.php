<?php 
	if($_GET['project'] == 'mike_maker'){
		require $_SERVER["DOCUMENT_ROOT"].'/config.inc.php';
		//error_log('got config from root - sidebar');
	}
	else{
		require $_SERVER["DOCUMENT_ROOT"].'/projects/'.$_GET['project'].'/admin/config.inc.php';
		//error_log('got config from inside project - sidebar');
	}
	$no_header = true; 
	require 'session.inc.php';
	require_once 'db_connection.inc.php';
	$sql = "select SCHEMA_NAME from information_schema.SCHEMATA where SCHEMA_NAME NOT IN('mysql', 'information_schema', 'performance_schema', 'sys', 'maker_mike');";
	echo json_encode($pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN));
 ?>