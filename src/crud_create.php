<?php
	isset($_GET['table']) || exit(json_encode((object) ["error" => "No such table."]));
	
	require 'config.inc.php';

	if($config['tables'][$_GET['table']]['permissions_create'] != '-'){
		require 'session.inc.php';

		// Checking table permissions
		if(!preg_match($config['tables'][$_GET['table']]['permissions_create'], $_SESSION['type']))
			exit(json_encode((object) ["error" => "login"]));
	}
	
	//TODO: Single respnose for all errors

	// Checking column permissions
	$allowedColumns = [];
	$columnValues = [];
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	while ($column = current($toTraverse)) {
		if($toTraverse[key($toTraverse)]['permissions_create'] == '-' ||  preg_match( $toTraverse[key($toTraverse)]['permissions_create'], $_SESSION['type'])){
			$columnValue = (isset($_POST[key($toTraverse)])? $_POST[key($toTraverse)]: 'Not present.');
			// upload possible files start
			if($column['type'] == '\*'){
				for($now = time(); file_exists($target_file = 'uploads/'.$_GET['table'].$now.basename($_FILES[key($toTraverse)]['name'])); $now++)
					;

				// var_dump($_FILES[key($toTraverse)]);
				// echo 'target file:  '.$target_file.'<br>';
				// echo 'ext: '.pathinfo($target_file, PATHINFO_EXTENSION);
				$ext = pathinfo($target_file, PATHINFO_EXTENSION);
				if(array_search($ext, array('jpg', 'jpeg', 'gif', 'png', 'pdf')) === False )
					exit(json_encode((object) ["error" => "File type '".$ext."' not supported"]));
				
				if ($_FILES[key($toTraverse)]["size"] > 1*1024*1024)
					exit(json_encode((object) ["error" => "File too large"]));

				if (!move_uploaded_file($_FILES[key($toTraverse)]["tmp_name"], $target_file))
					exit(json_encode((object) ["error" => "Error during transfer"]));
				$columnValue = $target_file;	
			}
			// upload possible files finish

			$allowedColumns[] = key($toTraverse);
			$columnValues[] = $columnValue;
		}
		next($toTraverse);
	}

	if(!count($allowedColumns))
		exit(json_encode((object) ["error" => "No such table."]));

	//Executing Query
	require 'db_connection.inc.php';
	$sql = 'INSERT INTO '.$_GET['table'].' ('.implode(', ',$allowedColumns).') VALUES (\''.implode('\', \'', $columnValues).'\');';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry added successfully"]);
	else
		echo json_encode((object) ["error" => $conn->error]);

	$conn->close();
?>