<?php
	//TODO: this service is a work in progress
	isset($_GET['table']) && isset($_GET['id']) || exit('No such table');
	
	require 'config.inc.php';
	require 'session.inc.php';

	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_delete'], $_SESSION['type']))
		exit('No such table');
	
	//Executing Query
	require 'db_connection.inc.php';
	$res = array();
	$sql = 'DELETE FROM '.$_GET['table'].' WHERE id='.$_GET['id'].';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		while($row = $result->fetch_assoc())
			$res[] = $row;
	// return sql errors as json
	echo json_encode($res);
	$conn->close();
?>