<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	isset($_GET['table']) || exit(json_encode((object) ["error" => "No such table."]));
	
	require 'config.inc.php';

	if($config[$_GET['table']]['_permissions']['create'] != '-'){
		require 'session.inc.php';

		// Checking table permissions
		if(!preg_match($config[$_GET['table']]['_permissions']['create'], $_SESSION['type']))
			exit(json_encode((object) ["error" => "login"]));
	}
	
	//TODO: Single respnose for all errors

	// Checking column permissions
	$allowedColumns = [];
	$values = [];
	foreach ($config[$_GET['table']] as $column_key => $column) {
		if($column_key[0] == '_')
			continue;
		if($column['permissions_create'] == '-' ||  preg_match( $column['permissions_create'], $_SESSION['type'])){
			$value = (isset($_POST[$column_key])? $_POST[$column_key]: 'Not present.');
			// upload possible files start
			if($column['type'] == '\*'){
				for($now = ''; file_exists($target_file = $now.basename($_FILES[$column_key]['name'])); $now = (!$now? time(): $now+1))
					;
				// var_dump($_FILES[$column_key]);
				// echo 'target file:  '.$target_file.'<br>';
				// echo 'ext: '.pathinfo($target_file, PATHINFO_EXTENSION);
				$ext = pathinfo($target_file, PATHINFO_EXTENSION);
				if(array_search(strtolower($ext), array('jpg', 'jpeg', 'gif', 'png', 'pdf')) === False )
					exit(json_encode((object) ["error" => "File type '".$ext."' not supported"]));
				
				if ($_FILES[$column_key]["size"] > 1*1024*1024)
					exit(json_encode((object) ["error" => "File too large"]));

				if (!move_uploaded_file($_FILES[$column_key]["tmp_name"], 'uploads/'.$_GET['table'].'/'.$target_file))
					exit(json_encode((object) ["error" => "Folder does not exist."]));
				$value = $target_file;	
			}
			// upload possible files end

			$allowedColumns[] = $column_key;
			$values[] = $value;
		}
	}

	if(!count($allowedColumns))
		exit(json_encode((object) ["error" => "No such table."]));

	//Executing Query
	require 'db_connection.inc.php';
	$sql = 'INSERT INTO '.$_GET['table'].' ('.implode(', ',$allowedColumns).') VALUES (\''.implode('\', \'', $values).'\');';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry added successfully"]);
	else
		echo json_encode((object) ["error" => $conn->error]);

	$conn->close();
?>