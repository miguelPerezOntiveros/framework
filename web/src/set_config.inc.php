<?php 
	preg_match('/\/projects\/(.*?)\/admin\/discovery\.php/', $_SERVER['REQUEST_URI'], $matches);
	$bindings = $matches[1];

	//Executing PDO
	$config['_projectName'] = 'maker_mike';
	require_once 'db_connection.inc.php';
	$data = array();
	$columns = array();
	$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, $._projectName = ?;';	
	error_log('SQL - '.$config['_projectName'].' - ' .$sql);
	$stmt = $pdo->prepare($sql);
	error_log('bindings: '.implode(', ', $bindings));
	$stmt->execute($bindings);
	while($row = $stmt->fetch(PDO::FETCH_NUM))
		$config=json_decode($row);

	// should use the JSON datatype on MySQL. TODO
?>
