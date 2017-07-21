<?php
	isset($_GET['table']) || exit(json_encode((object) ["error" => "No such table."]));
	
	require 'config.inc.php';

	if($config['tables'][$_GET['table']]['permissions_read'] != '-'){
		require 'session.inc.php';

		// Checking table permissions
		if(!preg_match($config['tables'][$_GET['table']]['permissions_read'], $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table."]));
	}

	// Checking column permissions
	$tablesToJoin = [$_GET['table']];
	$joinRules = ['1'];
	$allowedColumns = [$_GET['table'].'.id'];
	if(isset($_GET['show'])) { // only asking for 'show' column
		if($config['tables'][$_GET['table']]['columns'][$config['tables'][$_GET['table']]['show']]['permissions_read'] == '-' || preg_match( $config['tables'][$_GET['table']]['columns'][$config['tables'][$_GET['table']]['show']]['permissions_read'], $_SESSION['type']))
			$allowedColumns[] = $_GET['table'].'.'.$config['tables'][$_GET['table']]['show'];
	} else{
		$toTraverse = $config['tables'][$_GET['table']]['columns'];
		reset($toTraverse);
		while ($column = current($toTraverse)) {
			if(	(!isset($_GET['only']) || in_array(key($toTraverse), explode(",", $_GET['only']))) &&
				($toTraverse[key($toTraverse)]['permissions_read'] == '-' || preg_match( $toTraverse[key($toTraverse)]['permissions_read'], $_SESSION['type']))
				)	
				if(isset($config['tables'][$config['tables'][$_GET['table']]['columns'][key($toTraverse)]['type']])){ // column is a ref
					$otherTable = $config['tables'][$_GET['table']]['columns'][key($toTraverse)]['type'];
					$otherColumn = $config['tables'][$config['tables'][$_GET['table']]['columns'][key($toTraverse)]['type']]['show'];
					$tablesToJoin[] = $otherTable;
					$allowedColumns[] = 'CONCAT('.$_GET['table'].'.'.key($toTraverse).', "-", '.$otherTable.'.'.$otherColumn.') as '.key($toTraverse);
					$joinRules[] = $_GET['table'].'.'.key($toTraverse).' = '.$otherTable.'.id';
				}
				else if($config['tables'][$_GET['table']]['columns'][key($toTraverse)]['type'] == 'boolean')
					$allowedColumns[] = 'IF('.$_GET['table'].'.'.key($toTraverse).', "1-Yes", "0-No") as '.key($toTraverse);
				else
					$allowedColumns[] = $_GET['table'].'.'.key($toTraverse);
			next($toTraverse);
		}
	}
	if(isset($_GET['id']))
		$joinRules[] = $_GET['table'].'.id = '.$_GET['id'];

	if(isset($_GET['where']))
		$joinRules[] = $_GET['table'].'.'.$_GET['where'].' = "'.$_GET['equals'].'"';

	if(count($allowedColumns) <= 1)
		exit(json_encode((object) ["error" => "No such table."]));

	//Executing Query
	require 'db_connection.inc.php';
	$res = array();
	$fields = array();
	$sql = 'SELECT '.implode(', ', $allowedColumns).' FROM '.implode(', ', $tablesToJoin).' WHERE '.implode(' and ', $joinRules).';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql)){
		while ($field = $result->fetch_field())
			$fields[] = (object)[$field->name, $config['tables'][$_GET['table']]['columns'][$field->name]['type']]; 
		while($row = $result->fetch_array(MYSQLI_NUM))
			$res[] = $row;
	}

	// TODO: return sql errors as json
	echo json_encode((object) ['data' => $res, 'columns' => $fields]);
	$conn->close();
?>