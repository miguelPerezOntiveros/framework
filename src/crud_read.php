<?php
	isset($_GET['table']) || exit('No such table');
	
	require 'config.inc.php';
	require 'session.inc.php';

	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_read'], $_SESSION['type']))
		exit('No such table');
	
	//TODO: Allow for user specified columns

	// Checking column permissions
	$tablesToJoin = [$_GET['table']];
	$joinRules = ['1'];
	$allowedColumns = [$_GET['table'].'.id'];
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	while ($column = current($toTraverse)) {
		if(preg_match( $toTraverse[key($toTraverse)]['permissions'], $_SESSION['type']))
			if(isset($config['tables'][$config['tables'][$_GET['table']]['columns'][key($toTraverse)]['type']])){
				$otherTable = $config['tables'][$_GET['table']]['columns'][key($toTraverse)]['type'];
				$otherColumn = $config['tables'][$config['tables'][$_GET['table']]['columns'][key($toTraverse)]['type']]['show'];
				$tablesToJoin[] = $otherTable;
				$allowedColumns[] = $otherTable.'.'.$otherColumn.' as '.key($toTraverse);
				$joinRules[] = $_GET['table'].'.'.key($toTraverse).' = '.$otherTable.'.id';
			}
			else
				$allowedColumns[] = $_GET['table'].'.'.key($toTraverse);
		next($toTraverse);
	}

	if(count($allowedColumns) <= 1)
		exit('No such table');

	//Executing Query
	require 'db_connection.inc.php';
	$res = array();
	$fields = array();
	$sql = 'SELECT '.implode(', ', $allowedColumns).' FROM '.implode(', ', $tablesToJoin).' WHERE '.implode(' and ', $joinRules).';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql)){
		while ($field = $result->fetch_field())
			$fields[] = $field->name;
		while($row = $result->fetch_array(MYSQLI_NUM))
			$res[] = $row;
	}

	// TODO: return sql errors as json
	echo json_encode((object) ['data' => $res, 'columns' => $fields]);
	$conn->close();
?>