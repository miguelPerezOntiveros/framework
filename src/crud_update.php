<?php
	//TODO: Work in progress
	isset($_GET['table']) && isset($_POST['id']) || exit('No such table');
	
	require 'config.inc.php';
	require 'session.inc.php';

	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_update'], $_SESSION['type']))
		exit('No such table');
	
	// Checking column permissions
	$allowedColumns = [];
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	while ($column = current($toTraverse)) {
		if(preg_match( $toTraverse[key($toTraverse)]['permissions'], $_SESSION['type'])){
			$columnValues[] = key($toTraverse).' = \''.$_POST[key($toTraverse)].'\'';
		}
		next($toTraverse);
	}

	if(!count($allowedColumns))
		exit('No such table');

	//Executing Query
	require 'db_connection.inc.php';
	$sql = 'UPDATE '.$_GET['table'].' SET '.implode(', ',$allowedColumns).' WHERE id=\''.$_POST['id'].'\';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry updated successfully"]);
	else
		echo json_encode((object) ["error" => $conn->error]);

	$conn->close();
?>