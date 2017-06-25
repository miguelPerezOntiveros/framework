<?php
	isset($_GET['table']) || exit('No such table');
	
	require 'config.inc.php';
	require 'session.inc.php';

	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_create'], $_SESSION['type']))
		exit('No such table');
	
	// Checking column permissions
	$allowedColumns = [];
	$columnValues = [];
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	while ($column = current($toTraverse)) {
		if(preg_match( $toTraverse[key($toTraverse)]['permissions'], $_SESSION['type'])){
			$allowedColumns[] = key($toTraverse);
			$columnValues[] = $_POST[key($toTraverse)];
		}
		next($toTraverse);
	}

	if(!count($allowedColumns))
		exit('No such table');

	//Executing Query
	require 'db_connection.inc.php';
	$sql = 'INSERT INTO '.$_GET['table'].' ('.implode(', ',$allowedColumns).') VALUES (\''.implode('\', \'', $columnValues).'\');';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry added successfully"]);
	else
		echo json_encode((object) ["error" => $conn->error]);

	$conn->close();
?>