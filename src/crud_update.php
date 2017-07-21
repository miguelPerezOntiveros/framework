<?php
	isset($_GET['table']) && isset($_POST['id']) || exit(json_encode((object) ["error" => "No such table"]));
	
	require 'config.inc.php';
	require 'session.inc.php';
	error_log(json_encode($_FILES));
	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_update'], $_SESSION['type']))
		exit(json_encode((object) ["error" => "No such table"]));
	
	// Checking column permissions
	$allowedColumns = [];
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	while ($column = current($toTraverse)) {
		if(preg_match( $toTraverse[key($toTraverse)]['permissions_update'], $_SESSION['type'])) {
			// upload possible files start
			if($column['type'] == '\*' && $_FILES[key($toTraverse)]['size'] > 0 ) {

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
				$allowedColumns[] = key($toTraverse).' = \''.$target_file.'\'';
			}
			// upload possible files end
			if($column['type'] != '\*' ){
				$allowedColumns[] = key($toTraverse).' = \''.$_POST[key($toTraverse)].'\'';
			}
		}
		next($toTraverse);
	}

	if(!count($allowedColumns))
		exit(json_encode((object) ["error" => "No such table"]));

	// TODO: check how to handle all the possible sql errors

	// Delete possibe files
	require 'db_connection.inc.php';
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	$fileColumns = [];
	while ($column = current($toTraverse)) {
		if($toTraverse[key($toTraverse)]['type'] == '\*' && $_FILES[key($toTraverse)]['size'] > 0)
			$fileColumns[] = key($toTraverse);
		next($toTraverse);
	}
	if(count($fileColumns))
	{
		$sql = 'SELECT '.implode(', ', $fileColumns).' FROM '.$_GET['table'].' WHERE id = '.$_POST['id'].';';
		error_log('INFO - sql:'.$sql);
		if(!$result = $conn->query($sql))
			exit(json_encode((object) ["error" => "Error while retrieving entry"]));
		else
		{
			if(!$row = $result->fetch_assoc())
				exit(json_encode((object) ["error" => "No files to delete anymore"]));
			else{
				$toTraverse2 = $row;
				reset($toTraverse2);
				while ($column2 = current($toTraverse2)) {
					if(!unlink($row[key($toTraverse2)]))
						exit(json_encode((object) ["error" => "Error unlinking file"]));
					next($toTraverse2);
				}
			}
		}
	}

	//Executing Query
	$sql = 'UPDATE '.$_GET['table'].' SET '.implode(', ',$allowedColumns).' WHERE id=\''.$_POST['id'].'\';';	
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry updated successfully"]);
	else
		exit(json_encode((object) ["error" => $conn->error]));

	$conn->close();
?>
