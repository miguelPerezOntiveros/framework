<?php
	isset($_GET['table']) && isset($_POST['id']) || exit('No such table');
	
	require 'config.inc.php';
	require 'session.inc.php';

	// Checking table permissions
	if(!preg_match($config['tables'][$_GET['table']]['permissions_delete'], $_SESSION['type']))
		exit('No such table');
	
	// Todo check what would happen with the 2 queries if one didn´t complete, take a look at passing logic to the db
	// Todo handle all errors first
	// Todo check travere pattern
	// Todo do we want to be able to delete everything?

	require 'db_connection.inc.php';

	// Delete possibe files
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	$fileColumns = [];
	while ($column = current($toTraverse)) {
		if($toTraverse[key($toTraverse)]['type'] == '\*')
			$fileColumns[] = key($toTraverse);
		next($toTraverse);
	}
	if(count($fileColumns))
	{
		error_log(count('cols: '.$fileColumns));
		$sql = 'SELECT '.implode(', ', $fileColumns).' FROM '.$_GET['table'].' WHERE id = '.$_POST['id'].';';
		error_log('INFO - sql:'.$sql);
		if(!$result = $conn->query($sql))
			echo json_encode((object) ["error" => "Error while retrieving entry"]);
		else
		{
			if(!$row = $result->fetch_assoc())
				echo json_encode((object) ["error" => "No files to delete anymore"]);
			else{
				$toTraverse2 = $row;
				reset($toTraverse2);
				while ($column2 = current($toTraverse2)) {
					if(!unlink($row[key($toTraverse2)]))
						echo json_encode((object) ["error" => "Error unlinking file"]);
					else
						echo json_encode((object) ["success" => "File deleted successfully"]);
					next($toTraverse2);
				}
			}
		}
	}

	// Executing Query	
	$sql = 'DELETE FROM '.$_GET['table'].' WHERE id = '.$_POST['id'].';';	
	error_log('INFO - sql:'.$sql);
	if($result = $conn->query($sql))
		echo json_encode((object) ["success" => "Entry deleted successfully"]);
	else
		echo json_encode((object) ["error" => $conn->error]);

	$conn->close();
?>