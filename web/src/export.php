<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require 'set_config_and_params.inc.php';
	require_once 'session.inc.php';
	require $_SERVER["DOCUMENT_ROOT"].'/start_settings.inc.php';

	if($_SESSION['type'] != 'System Administrator'){
		echo json_encode((object) ["error" => 'This feature is only for System Administrators']);
		exit();
	}
	
	for($now = ''; file_exists($target_folder = $config['_name']).$now; $now = (!$now? time(): $now+1))
		;
	$dir = '../projects/'.$config['_name'].'/admin/exports/';
				
	$command = 'cd '.$dir.' && mkdir '.$target_folder.' && mysqldump -h '.$db_host.' -u '.$db_user.' --password='.$db_pass.' --databases '.$config['_name'].' > '.$target_folder.'/db.sql && zip '.$target_folder.'.zip '.$target_folder.'/db.sql && rm -rf '.$target_folder;
	error_log('Command: '.$command);
	exec($command);
	
	//Executing PDO
	// $data = array();
	// $columns = array();
	// $sql = 'SELECT '.implode(', ', $allowedColumns).' FROM '.implode(', ', $tablesToJoin).' WHERE '.implode(' and ', $joinRules).';';	
	// error_log('SQL - '.$config['_name'].' - ' .$sql);
	// $stmt = $pdo->prepare($sql);
	// error_log('bindings: '.implode(', ', $bindings));
	// $stmt->execute($bindings);
	// foreach(range(0, $stmt->columnCount() - 1) as $i)
	// 	$columns[] = (object)[$stmt->getColumnMeta($i)['name'], $config[$_GET['table']][$stmt->getColumnMeta($i)['name']]['type'], $config[$_GET['table']][$stmt->getColumnMeta($i)['name']]['show'], $config[$_GET['table']][$stmt->getColumnMeta($i)['name']]['select']]; 
	// while($row = $stmt->fetch(PDO::FETCH_NUM))
	// 	$data[] = $row;

	
	echo json_encode((object) ['path' => $target_folder.'.zip']);
?>