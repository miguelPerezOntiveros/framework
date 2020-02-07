<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require 'set_config_and_params.inc.php';

	//Validating table permissions
	if($config[$_GET['table']]['_permissions']['create'] != '-'){
		require_once 'session.inc.php';

		if(!isset($config[$_GET['table']]) || !preg_match('/'.$config[$_GET['table']]['_permissions']['create'].'/', $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table."]));
	}
	
	// Checking column permissions
	$row = [];
	foreach ($config[$_GET['table']] as $column_key => $column) {
		if($column_key[0] == '_')
			continue;
		if($column['permissions_create'] == '-' ||  preg_match('/'.$column['permissions_create'].'/', $_SESSION['type'])){
			$value =
				(isset($_POST[$column_key])?
					(is_array($_POST[$column_key])?
						json_encode($_POST[$column_key])
						:
						$_POST[$column_key])
					: 
					$column_key.' value not present on creation.'
			);
			// upload possible files start
			if($_FILES[$column_key]['error'] === 0 && $column['type'] == 'file'){
				for($now = ''; file_exists($target_file = $now.basename(str_replace(" ", "_", $_FILES[$column_key]['name']))); $now = (!$now? time(): $now+1))
					;
				// var_dump($_FILES[$column_key]);
				// echo 'target file:  '.$target_file.'<br>';
				// echo 'ext: '.pathinfo($target_file, PATHINFO_EXTENSION);
				$ext = pathinfo($target_file, PATHINFO_EXTENSION);
				$ext = strtolower($ext);
				$validExts = json_decode($column['ext']) ?: array('jpg', 'jpeg', 'gif', 'png');
				if(array_search($ext, $validExts) === False ){
					error_log('Valid exts: '.implode(', ', $validExts));
					exit(json_encode((object) ["error" => "Supported extensions: ".implode(', ', $validExts)]));
				}
				
				if ($_FILES[$column_key]["size"] > 10*1024*1024)
					exit(json_encode((object) ["error" => "File too large"]));

				error_log('About to move uploaded file: '. json_encode( $_FILES[$column_key]));
				if (!move_uploaded_file($_FILES[$column_key]["tmp_name"], '../projects/'.$_GET['project'].'/admin/uploads/'.$_GET['table'].'/'.$target_file)){
					error_log('Error during transfer: '. json_encode( $_FILES[$column_key]));
					exit(json_encode((object) ["error" => "Error during transfer, check the log"]));
				}
				$value = $target_file;	
			}
			// upload possible files end
			$row[$column_key] = $value;
		}
	}

	if(!count($row))
		exit(json_encode((object) ["error" => "No such table."]));

	require 'db_connection.inc.php';

	//Possible extension of the service
	$postfix = 'c';
	require 'ext.inc.php';

	//Executing Query
	$sql = 'INSERT INTO '.$_GET['table'].' ('.implode(', ',array_keys($row)).') VALUES (?';
	for($i = 1; $i<count($row); $i++)
		$sql .= ', ?';
	$sql .= ');';	
	error_log('INFO - sql:' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array_values($row));

	if($stmt->rowCount())
		echo json_encode((object) ["success" => 'Entries added successfully: '.$stmt->rowCount()]);
	else
		echo json_encode((object) ["error" => 'No entries updates']);
?>