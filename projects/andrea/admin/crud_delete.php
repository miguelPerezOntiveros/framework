<?php
	isset($_GET['table']) && isset($_POST['id']) || exit('No such table');
	
	require 'config.inc.php';
	require 'session.inc.php';

	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_delete'], $_SESSION['type']))
		exit('No such table');
	
	// Executing Query
	require 'db_connection.inc.php';
	$sql = 'DELETE FROM '.$_GET['table'].' WHERE id = '.$_POST['id'].';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry deleted successfully"]);
	else
		echo json_encode((object) ["error" => $conn->error]);
		
	$conn->close();
?>