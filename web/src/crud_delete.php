<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require 'set_config_and_params.inc.php';
	
	//Validating table permissions
	if($config[$_GET['table']]['_permissions']['delete'] != '-'){
		require_once 'session.inc.php';

		if(!isset($config[$_GET['table']]) || !preg_match('/'.$config[$_GET['table']]['_permissions']['delete'].'/', $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table."]));
	}
	
	// Delete possibe files
	$fileColumns = [];
	foreach ($config[$_GET['table']] as $column_key => $column) {
		if($column_key[0] == '_')
			continue;
		if($column['type'] == 'file')
			$fileColumns[] = $column_key;
	}
	error_log('file columns: '.count($fileColumns));

	require 'db_connection.inc.php';
	$sql = 'SELECT * FROM '.$_GET['table'].' WHERE id = ?;';
	error_log('INFO - sql:' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$_POST['id']]);

	if(!$row = $stmt->fetch(PDO::FETCH_ASSOC))
		exit(json_encode((object) ["error" => "No files to delete anymore"]));
	else{
		foreach ($fileColumns as $file_key){
			$fileToUnlink = '../projects/'.$_GET['project'].'/admin/uploads/'.$_GET['table'].'/'.$row[$file_key];
			if(file_exists($fileToUnlink))
				unlink($fileToUnlink);
			else
				error_log('Had no file to unlink: '.$fileToUnlink);
		}
	}

	//Possible extension of the service
	$postfix = 'd';
	require 'ext.inc.php';	

	// Executing Query	
	$sql = 'DELETE FROM '.$_GET['table'].' WHERE id = ?;';
	error_log('INFO - sql:' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$_POST['id']]);

	if($stmt->rowCount())
		echo json_encode((object) ["success" => 'Entries deleted successfully: '.$stmt->rowCount()]);
	else
		echo json_encode((object) ["error" => 'No entries deleted.']);
?>