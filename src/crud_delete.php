<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	isset($_GET['table']) && isset($_POST['id']) || exit('No such table');
	
	require 'config.inc.php';

	if($config[$_GET['table']]['_permissions']['delete'] != '-'){
		require 'session.inc.php';

		// Checking table permissions
		if(!preg_match($config[$_GET['table']]['_permissions']['delete'], $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table."]));
	}
	
	// Todo check what would happen with the 2 queries if one didn´t complete, take a look at passing logic to the db
	// Todo handle all errors first
	// Todo check travere pattern
	// Todo do we want to be able to delete everything?

	require 'db_connection.inc.php';

	// Delete possibe files
	$fileColumns = [];
	foreach ($config[$_GET['table']] as $column_key => $column) {
		if($column_key[0] == '_')
			continue;
		if($column['type'] == '\*')
			$fileColumns[] = $column_key;
	}
	if(count($fileColumns))
	{
		error_log(count('cols: '.$fileColumns));
		$sql = 'SELECT '.implode(', ', $fileColumns).' FROM '.$_GET['table'].' WHERE id = '.$_POST['id'].';';
		error_log('INFO - sql:'.$sql);
		if(!$result = $conn->query($sql))
			exit(json_encode((object) ["error" => "Error while retrieving entry"]));
		else
		{
			if(!$row = $result->fetch_assoc())
				exit(json_encode((object) ["error" => "No files to delete anymore"]));
			else
				foreach ($row as $file_key => $file)
					if(!unlink('uploads/'.$_GET['table'].'/'.$file))
						exit(json_encode((object) ["error" => "Error unlinking file"]));	
		}
	}

	// Executing Query	
	$sql = 'DELETE FROM '.$_GET['table'].' WHERE id = '.$_POST['id'].';';	
	error_log('INFO - sql:'.$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry deleted successfully"]);
	else
		exit(json_encode((object) ["error" => $conn->error]));

	$conn->close();
?>