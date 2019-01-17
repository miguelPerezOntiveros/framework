<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	isset($_GET['table']) && isset($_POST['id']) || exit(json_encode((object) ["error" => "No such table"]));
	
	if($_GET['project'] == 'mike_maker')
		require '../config.inc.php';
	else
		require '../projects/'.$_GET['project'].'/admin/config.inc.php';

	if($config[$_GET['table']]['_permissions']['update'] != '-'){
		require 'session.inc.php';

		// Checking table permissions
		if(!preg_match($config[$_GET['table']]['_permissions']['update'], $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table."]));
	}
	
	// Checking column permissions and if any files to delete
	$row = [];
	$fileColumns = [];
	foreach ($config[$_GET['table']] as $column_key => $column) {
		if($column_key[0] == '_')
			continue;
		if($column['type'] == '\*' && $_FILES[$column_key]['size'] > 0)
			$fileColumns[] = $column_key;
		if(preg_match( $column['permissions_update'], $_SESSION['type'])) {
			// upload possible files start
			if($column['type'] == '\*' && $_FILES[$column_key]['size'] > 0 ) {
				for($now = ''; file_exists($target_file = $now.basename($_FILES[$column_key]['name'])); $now = (!$now? time(): $now+1))
					;

				// var_dump($_FILES[$column_key]);
				// echo 'target file:  '.$target_file.'<br>';
				// echo 'ext: '.pathinfo($target_file, PATHINFO_EXTENSION);
				$ext = pathinfo($target_file, PATHINFO_EXTENSION);
				if(array_search($ext, array('jpg', 'jpeg', 'gif', 'png', 'pdf')) === False )
					exit(json_encode((object) ["error" => "File type '".$ext."' not supported"]));
				
				if ($_FILES[$column_key]["size"] > 1*1024*1024)
					exit(json_encode((object) ["error" => "File too large"]));

				if (!move_uploaded_file($_FILES[$column_key]["tmp_name"], '../projects/'.$_GET['project'].'/admin/uploads/'.$_GET['table'].'/'.$target_file))
					exit(json_encode((object) ["error" => "Error during transfer"]));
				$row[$column_key] = $target_file;
			}
			// upload possible files end
			if($column['type'] != '\*' ){
				$row[$column_key] = $_POST[$column_key];
			}
		}
	}

	if(!count($row))
		exit(json_encode((object) ["error" => "No such table"]));

	// TODO: check how to handle all the possible sql errors

	require 'db_connection.inc.php';
	
	$sql = 'SELECT * FROM '.$_GET['table'].' WHERE id = '.$_POST['id'].';';
	error_log('INFO - sql:'.$sql);
	if(!$result = $conn->query($sql))
		exit(json_encode((object) ["error" => "Error while retrieving entry"]));
	else
	{
		if(!$row_old = $result->fetch_assoc())
			exit(json_encode((object) ["error" => "No files to delete anymore"]));
		else{
			foreach ($fileColumns as $file_key)
				if(!unlink('../projects/'.$_GET['project'].'/admin/uploads/'.$_GET['table'].'/'.$row_old[$file_key]))
					exit(json_encode((object) ["error" => "Error unlinking file"]));
			//Possible extension of the service
			$ext = '../ext/'.$config['_projectName'].'.'.$_GET['table'].'.u.php';
			if(file_exists($ext))
				require($ext);
		}
	}

	//Executing Query
	$sql_keys = [];
	foreach ($row as $key => $value) {
		$sql_keys[] = $key.' = \''.$value.'\'';
	}
	$sql = 'UPDATE '.$_GET['table'].' SET '.implode(', ',$sql_keys).' WHERE id=\''.$_POST['id'].'\';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry updated successfully"]);
	else
		exit(json_encode((object) ["error" => $conn->error]));

	$conn->close();
?>
