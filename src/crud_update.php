<?php
	//TODO: this service is a work in progress

	isset($_GET['table']) && isset($_GET['id']) || exit('No such table');
	
	require 'config.inc.php';
	require 'session.inc.php';

	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_update'], $_SESSION['type']))
		exit('No such table');
	
	//TODO: traverse sent fields (through POST)
	// Checking column permissions
	$updates = [];
	$values = [];
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	while ($column = current($toTraverse)) {
		if(preg_match( $toTraverse[key($toTraverse)]['permissions'], $_SESSION['type']))
			$updates[] = key($toTraverse);
		next($toTraverse);
	}

	if(!count($allowedColumns))
		exit('No such table');

	//Executing Query
	require 'db_connection.inc.php';
	$res = array();
	$sql = 'UPDATE '.$_GET['table'].' SET '.$updates.' WHERE id='.$_GET['id'].';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		while($row = $result->fetch_assoc())
			$res[] = $row;
	// return sql errors as json
	echo json_encode($res);
	$conn->close();
?>