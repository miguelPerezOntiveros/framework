<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	isset($_GET['table']) || exit(json_encode((object) ["error" => "No such table."]));
	
	if($_GET['project'] == 'mike_maker')
		require '../config.inc.php';
	else
		require '../projects/'.$_GET['project'].'/admin/config.inc.php';

	if($config[$_GET['table']]['_permissions']['read'] != '-'){
		require 'session.inc.php';

		// Checking table permissions
		if(!preg_match($config[$_GET['table']]['_permissions']['read'], $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table."]));
	}

	// Checking column permissions
	$tablesToJoin = [$_GET['table']];
	$joinRules = ['1'];
	$allowedColumns = [$_GET['table'].'.id'];
	if(isset($_GET['show'])) { // only asking for 'show' column
		if($config[$_GET['table']][$config[$_GET['table']]['_show']]['permissions_read'] == '-' || preg_match( $config[$_GET['table']][$config[$_GET['table']]['_show']]['permissions_read'], $_SESSION['type']))
			$allowedColumns[] = $_GET['table'].'.'.$config[$_GET['table']]['_show'];
	} else{
		foreach ($config[$_GET['table']] as $column_key => $column) {
			if($column_key[0] == '_')
				continue;
			if(	(!isset($_GET['only']) || in_array($column_key, explode(",", $_GET['only']))) &&
				($column['permissions_read'] == '-' || preg_match( $column['permissions_read'], $_SESSION['type']))
				)	
				if(isset($config[$column['type']])){ // column is a ref
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
	if(isset($_GET['id']))
		$joinRules[] = $_GET['table'].'.id = '.$_GET['id'];

	if(isset($_GET['where']))
		$joinRules[] = $_GET['table'].'.'.$_GET['where'].' = "'.$_GET['equals'].'"';

	if(count($allowedColumns) <= 1)
		exit(json_encode((object) ["error" => "No such table."]));

	//Executing Query
	require 'db_connection.inc.php';
	$data = array();
	$columns = array();
	$sql = 'SELECT '.implode(', ', $allowedColumns).' FROM '.implode(', ', $tablesToJoin).' WHERE '.implode(' and ', $joinRules).';';	
	error_log('SQL - '.$config['_projectName'].' - ' .$sql);
	if($result = $conn->query($sql)){
		while ($column = $result->fetch_field())
			$columns[] = (object)[$column->name, $config[$_GET['table']][$column->name]['type']]; 
		while($row = $result->fetch_array(MYSQLI_NUM))
			$data[] = $row;
	}

	$ext = '../projects/'.$_GET['project'].'/admin/ext/'.$_GET['table'].'.r.php';
	if(file_exists($ext))
		require($ext);

	// TODO: return sql errors as json
	echo json_encode((object) ['data' => $data, 'columns' => $columns]);
	$conn->close();
?>