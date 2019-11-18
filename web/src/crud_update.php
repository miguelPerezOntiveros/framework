<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require 'origin_check.php';
	require_once 'load_config.php';

	//Validating table permissions
	if($config[$_GET['table']]['_permissions']['update'] != '-'){
		require_once 'session.inc.php';

		if(!isset($config[$_GET['table']]) || !preg_match('/'.$config[$_GET['table']]['_permissions']['update'].'/', $_SESSION['type']))
			exit(json_encode((object) ["error" => "No such table."]));
	}
	
	// Checking column permissions and if any files to delete
	$row = [];
	$fileColumns = [];
	foreach ($config[$_GET['table']] as $column_key => $column) {
		if($column_key[0] == '_')
			continue;
		if($column['type'] == '*' && $_FILES[$column_key]['size'] > 0)
			$fileColumns[] = $column_key;
		if(preg_match('/'.$column['permissions_update'].'/', $_SESSION['type'])) {
			// upload possible files start
			if($column['type'] == '*' && $_FILES[$column_key]['size'] > 0 ) {
				for($now = ''; file_exists($target_file = $now.basename($_FILES[$column_key]['name'])); $now = (!$now? time(): $now+1))
					;

				// var_dump($_FILES[$column_key]);
				// echo 'target file:  '.$target_file.'<br>';
				// echo 'ext: '.pathinfo($target_file, PATHINFO_EXTENSION);
				$ext = pathinfo($target_file, PATHINFO_EXTENSION);
				$validExts = $column['ext'] ?: array('jpg', 'jpeg', 'gif', 'png');
				if(array_search($ext, $validExts) === False ){
					error_log('Valid exts: '.implode(', ', $validExts));
					exit(json_encode((object) ["error" => "File type '".$ext."' not supported"]));
				}
				
				if ($_FILES[$column_key]["size"] > 1*1024*1024)
					exit(json_encode((object) ["error" => "File too large"]));

				if (!move_uploaded_file($_FILES[$column_key]["tmp_name"], '../projects/'.$_GET['project'].'/admin/uploads/'.$_GET['table'].'/'.$target_file)){
					error_log('Error during transfer: '. json_encode( $_FILES[$column_key]));
					exit(json_encode((object) ["error" => "Error during transfer, check the log"]));
				}
				$row[$column_key] = $target_file;
			}
			// upload possible files end
			if($column['type'] != '*' ){
				$row[$column_key] = (isset($_POST[$column_key])? 
					(is_array($_POST[$column_key])?
						json_encode($_POST[$column_key]):
						$_POST[$column_key])
					: 
					'Not present.'
				);
			}
		}
	}

	if(!count($row))
		exit(json_encode((object) ["error" => "No such table."]));

	require_once 'db_connection.inc.php';
	$sql = 'SELECT * FROM '.$_GET['table'].' WHERE id = ?;';
	error_log('INFO - sql:' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$_POST['id']]);
	if($row_old = $stmt->fetch(PDO::FETCH_ASSOC))
		foreach ($fileColumns as $file_key){
			$fileToUnlink = '../projects/'.$_GET['project'].'/admin/uploads/'.$_GET['table'].'/'.$row_old[$file_key];
			error_log('File to unlink: '.$fileToUnlink);
			if(file_exists($fileToUnlink))
				unlink($fileToUnlink);
			else
				error_log('Had no file to unlink: '.$fileToUnlink);
		}
	
	//Possible extension of the service
	$postfix = 'u';
	require 'ext.inc.php';

	//Executing Query
	$sql_keys = [];
	foreach ($row as $key => $value)
		$sql_keys[] = $key.' = ?';
	$sql = 'UPDATE '.$_GET['table'].' SET '.implode(', ',$sql_keys).' WHERE id=\''.$_POST['id'].'\';';	
	error_log('INFO - sql:' .$sql);
	$stmt = $pdo->prepare($sql);
	error_log('bindings: '.implode(', ', array_values($row)));
	$stmt->execute(array_values($row));
	$stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount())
		echo json_encode((object) ["success" => 'Entries udated successfully: '.$deleted = $stmt->rowCount()]);
	else
		echo json_encode((object) ["error" => 'No entries updated.']);
?>
