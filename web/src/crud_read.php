<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require 'set_config_and_params.inc.php';
	
	//Validating table permissions
	if($config[$_GET['table']]['_permissions']['read'] != '-'){
		require_once 'session.inc.php';

		if(!isset($config[$_GET['table']]) || !preg_match('/'.$config[$_GET['table']]['_permissions']['read'].'/', $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table 1."]));
	}

	//Validating column permissions
	$tablesToJoin = [$_GET['table']];
	$allowedColumns = [$_GET['table'].'.id'];
	$joinRules = ['1'];
	$bindings = [];
	if(isset($_GET['show'])) { // only asking for 'show' column
		if($config[$_GET['table']][$config[$_GET['table']]['_show']]['permissions_read'] == '-' || preg_match('/'.$config[$_GET['table']][$config[$_GET['table']]['_show']]['permissions_read'].'/', $_SESSION['type']))
			$allowedColumns[] = $_GET['table'].'.'.$config[$_GET['table']]['_show'];
	} else{
		foreach ($config[$_GET['table']] as $column_key => $column) {
			if($column_key[0] == '_')
				continue;
			if(
					(!isset($_GET['only']) || in_array($column_key, explode(",", $_GET['only'])))
					&&
					($column['permissions_read'] == '-' || preg_match('/'.$column['permissions_read'].'/', $_SESSION['type']))
				)	
				if(isset($config[$column['type']]) && $column['select'] != 'multi'){ //column is a non-multi ref
					$otherTable = $column['type'];
					$otherTableAlias = $column_key.'Table';
					$otherColumn = $config[$otherTable]['_show'];
					$tablesToJoin[] = $otherTable.' '.$otherTableAlias;
					$allowedColumns[] = 'CONCAT('.$_GET['table'].'.'.$column_key.', "-", '.$otherTableAlias.'.'.$otherColumn.') as '.$column_key;
					$joinRules[] = $_GET['table'].'.'.$column_key.' = '.$otherTableAlias.'.id';
				}
				else if($column['type'] == 'boolean')
					$allowedColumns[] = 'IF('.$_GET['table'].'.'.$column_key.', "1-Yes", "0-No") as '.$column_key;
				else
					$allowedColumns[] = $_GET['table'].'.'.$column_key;
		}
	}
	if(count($allowedColumns) <= 1)
		exit(json_encode((object) ["error" => "No such table 2."]));

	if(isset($_GET['id'])){
		$joinRules[] = $_GET['table'].'.id = ?';
		$bindings[] = $_GET['id'];
	}

	if(isset($_GET['where']) && array_search($_GET['where'], $allowedColumns) !== false){
		$joinRules[] = $_GET['where'].' = ?';
		$bindings[] = $_GET['equals'];
	}

	if(isset($_GET['columns']))
		$joinRules[] = '0';
	
	//Executing PDO
	$data = array();
	$columns = array();
	$sql = 'SELECT '.implode(', ', $allowedColumns).' FROM '.implode(', ', $tablesToJoin).' WHERE '.implode(' and ', $joinRules).';';	
	error_log('SQL - '.$config['_name'].' - ' .$sql);
	$stmt = $pdo->prepare($sql);
	error_log('bindings: '.implode(', ', $bindings));
	$stmt->execute($bindings);
	foreach(range(0, $stmt->columnCount() - 1) as $i)
		$columns[] = (object)[$stmt->getColumnMeta($i)['name'], $config[$_GET['table']][$stmt->getColumnMeta($i)['name']]['type'], $config[$_GET['table']][$stmt->getColumnMeta($i)['name']]['show'], $config[$_GET['table']][$stmt->getColumnMeta($i)['name']]['select'], $config[$_GET['table']][$stmt->getColumnMeta($i)['name']]['hide_in']]; 
	while($row = $stmt->fetch(PDO::FETCH_NUM))
		$data[] = $row;

	//Possible extension of the service
	$postfix = 'r';
	require 'ext.inc.php';

	echo json_encode((object) ['data' => $data, 'columns' => $columns]);
?>